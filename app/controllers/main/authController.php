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


    /*
     * Back End
     * LDap Connection
     *
     */

    private function set_login($user_data)
    {
//        $this->sys_user_model->update_login($user_data);
        $newdata['auth'] = TRUE;
        $newdata['userid'] = $user_data['USR_CODE'];
        $newdata['name'] = ($user_data['USR_DISPLAY'] == "" ? $user_data['USR_NAME'] : $user_data['USR_DISPLAY']);
        $newdata['username'] = $user_data['USR_LOGIN'];
        $newdata['usertype'] = $user_data['USR_TYPE'];
        $newdata['lang'] = ($user_data['USR_LANGUAGE'] == "" ? "th" : $user_data['USR_LANGUAGE']);
        $newdata['usergroup'] = $user_data['usergroup'];
        $newdata['userfac'] = $user_data['FACULTY_ID'];
        foreach ($newdata as $key => $val) {
            if (is_string($val)) {
                $newdata[$key] = trim($val);
            }

        }


        $this->session->multi_set($newdata);
//        print_r($_SESSION);
    }

    function loginAction()
    {
        $sess = $this->session;


        $user = $_POST['login'];
        $pws = $_POST['password'];

        if (empty($user) || empty($pws)) {
            echo json_encode(['error' => true, 'msg' => 'กรุณากรอก Account และ Password ให้ครบถ้วน']);
            return;
        }

        //block spam
        if ($_POST['skey'] == $sess->get('key')) {
            // #1 get data user in sys_user
            $user_model = new \EThesis\Models\System\Sys_user_model();
            $user_data = $user_model->select_by_username([], $user);

            $user_data['USR_LOGIN'] = $user;


            $ad_info = FALSE;

          //  $ad_info = $this->login_ad($user, $pws);
			
			/* auto login*/
			$ad_info = [];
			$ad_info[0]['samaccountname'][0] = 'attapon.th';
			$ad_info[0]['extensionattribute6'][0] = 'attapon.th';
			$ad_info[0]['displayname'][0] = 'Attapon Thanawong';
			
			/* END */

            $ad_authen = ($ad_info) ? TRUE : FALSE;

            $addbms_auten = (!empty($user_data) && $ad_authen ? TRUE : FALSE);

            $dbms_authen = (!empty($user_data['USR_PASSWORD']) && sha1($pws) == $user_data['USR_PASSWORD'] ? TRUE : FALSE);

            // #2 check authen by active directory
            if (false && $this->_ad->get_last_errno() == 81) {
                echo json_encode(['error' => true, 'msg' => "ไม่สามารถตรวจสอบ Account ของมหาวิทยาลัยได้ กรุณาติดต่องานบริการระบบเครือข่ายคอมพิวเตอร์ CITCOMS หรือใช้ Account ภายในระบบทะเบียน"]);
                return;
            } else {
                if ($ad_authen === FALSE && $dbms_authen === FALSE) {
                    echo json_encode(['msg' => 'ชื่อผู้ใช้งาน หรือรหัสผ่านผิด']);
                } else {
                    if (sizeof($ad_info) > 0 && $addbms_auten === FALSE) {
                        $lang = $sess->get('lang');
                        $hrd_person_model = new \EThesis\Models\Hrd\Hrd_person_model();

                        // หาก login ad ผ่านให้ใช้ user จาก ad (SAMAccountName) ค้นหาในระบบ ป้องกันการ key วรรณยุกต์เกินมา ซึ่งสามารถ login ad ผ่าน
                        $user = $ad_info[0]['samaccountname'][0];
                        $user_data['USR_NAME'] = $user;
                        $extensionattribute6 = (isset($ad_info[0]['extensionattribute6'][0]) ? strtoupper($ad_info[0]['extensionattribute6'][0]) : null);

                        switch ($extensionattribute6) {
                            case 'STAFF':
                                $hrd_person = $hrd_person_model->select_by_cid(array('NAME_' . $lang, 'REG_FACULTY_ID'), $ad_info[0]['extensionattribute1'][0]);
                                $user_data['USR_CODE'] = $ad_info[0]['extensionattribute1'][0];
                                $user_data['USR_DISPLAY'] = $hrd_person['NAME_' . $lang] ? $hrd_person['NAME_' . $lang] : $ad_info[0]['displayname'][0];
                                $user_data['USR_TYPE'] = "U";
                                break;
                            case 'TEACHER':
                                if ($ad_info[0]['extensionattribute1'][0] != "") {
                                    $hrd_person = $hrd_person_model->select_by_cid(array('NAME_' . $lang, 'REG_FACULTY_ID'), $ad_info[0]['extensionattribute1'][0]);
                                    $user_data['USR_CODE'] = $ad_info[0]['extensionattribute1'][0];
                                    $user_data['USR_DISPLAY'] = $hrd_person['NAME_' . $lang] ? $hrd_person['NAME_' . $lang] : $ad_info[0]['displayname'][0];
                                    $user_data['USR_TYPE'] = "T";
                                    $user_data['FACULTY_ID'] = $hrd_person['REG_FACULTY_ID'];
                                    $user_data['CITIZEN_AD'] = $user_data['USR_CODE'];
                                    $user_data['USR_AD'] = $user;
                                } else {
                                    echo json_encode(['error' => true, 'msg' => 'ไม่พบรหัสประจำตัวประชาชนใน Account ของมหาวิทยาลัย กรุณาติดต่อ งานบริการระบบเครือข่ายคอมพิวเตอร์ CITCOMS']);
                                }
                                break;
                            default:
                                $std_info_model = new \EThesis\Models\Reg\Reg_std_info_model();
                                $user_data['USR_CODE'] = $user;
                                $user_data['USR_DISPLAY'] = $ad_info[0]['description'][0];
                                $user_data['USR_TYPE'] = "S";

                                break;
                        }

                        //ไม่มี ประเภทผู้ใช้งาน
                        if ($user_data['USR_TYPE'] == "") {
                            $user_data['CITIZEN_AD'] = $user_data['USER_CODE'];
                            $user_data['USR_AD'] = $user;
                            $user_data['USR_TYPE'] = '';
                            echo json_encode(['error' => true, 'msg' => 'ไม่พบข้อมูลประเภทผู้ใช้งานใน Account ของมหาวิทยาลัย กรุณาติดต่อ งานบริการระบบเครือข่ายคอมพิวเตอร์ CITCOMS']);
                            return;
                        }
                    }
                }
            }


            $user_data['usergroup'] = [];
            // #3 list group default
            $user_group_model = new \EThesis\Models\System\Sys_groupuser_model();
            if ($user_data['USR_TYPE'] != '') {
                $user_group = $user_group_model->select_default_group($user_data['USR_TYPE']);
                if (sizeof($user_group) > 0) {
                    foreach ($user_group as $rs) {
                        $user_data['usergroup'][$rs['value']] = $rs['title'];
                    }
                }
            }

            // #4 get outher permission
            if ($user_data['GROUP_ID'] != '') {
                $user_group = $user_group_model->select_user_group($user_data['GROUP_ID']);
                if (sizeof($user_group) > 0) {
                    foreach ($user_group as $rs) {
                        $user_data['usergroup'][$rs['value']] = $rs['title'];
                    }
                }
            }


            // #5 get group permission
            if ($ad_authen || $dbms_authen) {
                $this->set_login($user_data);
//                print_r($_SESSION);
                $res = [
                    'group' => $user_data['usergroup'],
                    'error' => false,
                    //'grouplogin_url' => $this->url->get('main/auth/grouplogin/'),
                    'name' => $user_data['USR_DISPLAY']
                ];
                echo json_encode($res);
                $this->logs->set(LOG_LOGIN);

            }
        } else {
            echo json_encode(['error' => true, 'msg' => 'การล็อคอินไม่ถูกต้อง กรุณาโหลดหน้านี้ใหม่']);
        }
    }

    public function  logingroupAction()
    {
        $response = ['error' => TRUE];
        if ($this->session->get('auth') !== FALSE) {
            $group_permis_model = new \EThesis\Models\System\Sys_grouppermis_model();
            $group_model = new \EThesis\Models\System\Sys_groupuser_model();
            $user_model = new \EThesis\Models\System\Sys_user_model();
            $utype = $this->session->get('usertype');
            $ugroup = $this->session->get('usergroup');
            $username = $this->session->get('username');

            if (!empty($ugroup) && !empty($utype)) {
                $g = $user_model->select_by_username([], $username);
                $g1 = $group_model->select_default_group($utype);
                $g2 = $group_model->select_user_group($g['GROUP_ID']);
                $group = $g1;
                $tmp_gid = array_column($group, 'value');
                foreach ($g2 as $val) {
                    if (!in_array($val['value'], $tmp_gid)) {
                        $group[] = $val;
                    }
                }
                if (!empty($_POST['grouploginid']) && in_array($_POST['grouploginid'], array_column($group, 'value'))) {
                    $response['gid'] = $_POST['grouploginid'];
                    $response['reurl'] = $this->url->get('main');
                    $response['error'] = FALSE;
                    //set Session
                    $this->session->set('grouplogin', $response['gid']);

                } else {
                    $response['msg'] = 'คุณไม่ได้รับสิทธิ์ให้ใช้งานกลุ่มผู้ใช้งานนี้';
                }
            }
        } else {
            $response['msg'] = 'หมดเวลาการเข้าใช้งานระบบ กรุณาลงชื่อเข้าใช้งานใหม่';
        }
//        print_r($_SESSION);
        echo json_encode($response);
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

    function logoutAction($redirect = "")
    {
        if ($this->session->get('auth') == TRUE) {
            $lang = $this->session->get('lang');
            $this->session->destroy();
            $this->session->set('auth', FALSE);
            $this->session->set('lang', $lang);
        }
        echo json_encode(['auth' => FALSE]);
    }

    function get_checkloginAction($skey = '')
    {
        $response = ['auth' => FALSE];
        if($skey == $this->session->get('key')){
            if ($this->session->get('auth') == TRUE) {
                $response['auth'] = TRUE;
            }
        }
        echo json_encode($response);
    }


}
