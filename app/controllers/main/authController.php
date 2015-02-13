<?php

namespace EThesis\Controllers\Main;

use \EThesis\Library\adLDAP as adLDAP;

class AuthController extends \Phalcon\Mvc\Controller
{

    private $ck = FALSE;

    private $_ad;

    protected function initialize()
    {
        \Phalcon\Tag::setTitle('Authentication');
        $this->ldap = new \EThesis\Library\adLDAP();
    }

    public function indexAction()
    {

    }

    public function loginAction()
    {

        $this->session->set('auth', 'True');
        $this->ck &= isset($_POST['submit']);


        if ($this->ck) {
            echo json_encode(['auth' => TRUE]);
        } else {
            echo json_encode(['auth' => FALSE]);
        }
    }


    /*
     * Back End
     * LDap Connection
     *
     */

    function request_key()
    {
        $key = md5(rand(1, 99999));
        $_SESSION['key'] = $key;
        echo $key;
    }

    private function set_login($user_data)
    {
        $this->load->library('user_agent');
        $user_data['LAST_IP'] = $this->session->userdata['ip_address'];
        $this->sys_user_model->update_login($user_data);
        $newdata['logged_in'] = TRUE;
        $newdata['userid'] = $user_data['USER_CODE'];
        $newdata['name'] = ($user_data['DISPLAY_NAME'] == "") ? $user_data['USER_NAME'] : $user_data['DISPLAY_NAME'];
        $newdata['username'] = $user_data['USER_NAME'];
        $newdata['usertype'] = $user_data['USER_TYPE'];
        $newdata['lang'] = ($user_data['USER_LANGUAGE'] == "") ? "TH" : $user_data['USER_LANGUAGE'];
        $newdata['theme'] = '01';
        $newdata['usergroup'] = $user_data['usergroup'];
        $newdata['userfac'] = $user_data['FACULTY_ID'];
        $newdata['logingroup'] = $user_data['usergroup'];
        ses_add($newdata);
    }

    function login()
    {
        $system_auth = $this->config->item('system_auth');
        $authen_data = explode('|', base64_decode($this->input->get_post('authen')));
        $user = $authen_data[2];
        $pws = $authen_data[0];
        //block spam
        if ($authen_data[1] == $_SESSION['key']) {
            // #1 get data user in sys_user
            $user_data = $this->sys_user_model->select_by_username(array("*"), $user);
            $ad_info = FALSE;
            if ($system_auth == 'ad') {
                $ad_info = $this->login_ad($user, $pws);
            }
            $ad_authen = ($ad_info) ? TRUE : FALSE;

            // #2 check authen by active directory
            if ($system_auth == 'ad' && $this->_ad->get_last_errno() == 81) {
                $dbms_authen = (md5($pws) == $user_data['USER_PASS']) ? TRUE : FALSE;
                echo "<br>ไม่สามารถตรวจสอบ Account ของมหาวิทยาลัยได้ กรุณาติดต่องานบริการระบบเครือข่ายคอมพิวเตอร์ CITCOMS หรือใช้ Account ภายในระบบทะเบียน";
            } else {
                if ($ad_authen === FALSE) {
                    echo 'ชื่อผู้ใช้งาน หรือรหัสผ่านผิด';
                } else {
                    if (sizeof($ad_info) > 0) {
                        $lang = ses_data('lang') ? strtoupper(ses_data('lang')) : 'TH';
                        $this->load->model('hrd/hrd_his_person_all_model');
                        $hrd_person = $this->hrd_his_person_all_model->select_by_id(array('NAME_' . $lang, 'REG_FACULTY_ID'), $ad_info[0]['extensionattribute1'][0]);

                        // หาก login ad ผ่านให้ใช้ user จาก ad (SAMAccountName) ค้นหาในระบบ ป้องกันการ key วรรณยุกต์เกินมา ซึ่งสามารถ login ad ผ่าน
                        $user = $ad_info[0]['samaccountname'][0];
                        $user_data['USER_NAME'] = $user;
                        $extensionattribute6 = strtoupper($ad_info[0]['extensionattribute6'][0]);
                        switch ($extensionattribute6) {
                            case 'STAFF':
                                $user_data['USER_CODE'] = $ad_info[0]['extensionattribute1'][0];
                                $user_data['DISPLAY_NAME'] = $hrd_person['NAME_' . $lang] ? $hrd_person['NAME_' . $lang] : $ad_info[0]['displayname'][0];
                                $user_data['USER_TYPE'] = "U";
                                break;
                            case 'TEACHER':
                                if ($ad_info[0]['extensionattribute1'][0] != "") {
                                    $user_data['USER_CODE'] = $ad_info[0]['extensionattribute1'][0];
                                    $user_data['DISPLAY_NAME'] = $hrd_person['NAME_' . $lang] ? $hrd_person['NAME_' . $lang] : $ad_info[0]['displayname'][0];
                                    $user_data['USER_TYPE'] = "T";
                                    $user_data['FACULTY_ID'] = $hrd_person['REG_FACULTY_ID'];
                                    $_SESSION['CITIZEN_AD'] = $user_data['USER_CODE'];
                                    $_SESSION['USER_TYPE'] = 'T';
                                    $_SESSION['USER_AD'] = $user;
                                } else {
                                    echo 'ไม่พบรหัสประจำตัวประชาชนใน Account ของมหาวิทยาลัย กรุณาติดต่อ งานบริการระบบเครือข่ายคอมพิวเตอร์ CITCOMS';
                                }
                                break;
                            default:
                                $std_info = $this->sys_user_model->select_by_stdcode($user);
                                if (sizeof($std_info) > 0) {
                                    $user_data['USER_CODE'] = $user;
                                    $user_data['DISPLAY_NAME'] = $std_info['NAME_TH'];
                                    $user_data['USER_TYPE'] = "S";
                                }
                                break;
                        }

                        //ไม่มี ประเภทผู้ใช้งาน
                        if ($user_data['USER_TYPE'] == "") {
                            $_SESSION['CITIZEN_AD'] = $user_data['USER_CODE'];
                            $_SESSION['USER_AD'] = $user;
                            $_SESSION['USER_TYPE'] = '';
                            echo 'ไม่พบข้อมูลประเภทผู้ใช้งานใน Account ของมหาวิทยาลัย กรุณาติดต่อ งานบริการระบบเครือข่ายคอมพิวเตอร์ CITCOMS';
                            exit();
                        }
                    }
                }
            }

            if ($system_auth == 'dbms') {
                $dbms_authen = (md5($pws) == $user_data['USER_PASS']) ? TRUE : FALSE;
            }


            // #3 list group default
            if ($user_data['USER_TYPE'] != '') {
                $user_group = $this->sys_user_model->select_default_group($user_data['USER_TYPE']);
                if (sizeof($user_group) > 0) {
                    foreach ($user_group as $rs) {
                        $user_data['usergroup'][$rs['value']] = $rs['title'];
                    }
                }
            }

            // #4 get outher permission
            if ($user_data['GROUP_ID'] != '') {
                $user_group = $this->sys_user_model->select_user_group($user_data['GROUP_ID']);
                if (sizeof($user_group) > 0) {
                    foreach ($user_group as $rs) {
                        $user_data['usergroup'][$rs['value']] = $rs['title'];
                    }
                }
            }

            // #5 get group permission
            if ($ad_authen || $dbms_authen) {
                $this->set_login($user_data);
                echo '<script> $.ajax({ url: base_url+"auth/get_login", success: function(data){ $("#box-login").html(data) } }); </script>';
            }

            if ($system_auth == 'dbms' && $dbms_authen == FALSE) {
                echo 'ชื่อผู้ใช้งาน หรือรหัสผ่านผิด';
                echo "<script> $(window.location).attr('href', '" . base_url() . "'); </script>";
            }
        }
    }

    function login_ad($username, $password)
    {
        if ($username == NULL) {
            return (FALSE);
        }
        if ($password == NULL) {
            return (FALSE);
        }
        try {
            $this->_ad = new adLDAP();
            if ($this->_ad->authenticate($username, $password)) {
                $ldapInfo = $this->_ad->user_info($username);
                return $ldapInfo;
            } else {
                return FALSE;
            }
        } catch (adLDAPException $e) {
            return FALSE;
        }
    }

    function logout($redirect = "")
    {
        if (ses_data('logged_in') == TRUE) {
            session_destroy();
        }
        if ($redirect == "refresh") {
            redirect(base_url() . 'home', 'refresh');
        } else {
            echo '<script> $.ajax({ url: base_url+"auth/get_login", success: function(data){ $("#box-login").html(data) } }); </script>';
        }
    }

    function get_login()
    {
        $user_data = $_SESSION;
        $this->load->view('design_template/login', $user_data);
    }


}
