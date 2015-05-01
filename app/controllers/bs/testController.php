<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 27/3/2558
 * Time: 10:52
 */

namespace EThesis\Controllers\Bs;

use \EThesis\Library\Form AS Form;

class TestController extends \Phalcon\Mvc\Controller
{


    public function initialize()
    {
        $this->lang_class = new \EThesis\Library\Lang();
        $this->_lang = $this->session->get('lang');
        if ($this->session->get('auth') !== TRUE) {
            die('false');
        }
        //print_r($this->init_class[sess]->get());
    }

    public function indexAction()
    {

        $form = new Form();

        $form->add_input('UPLOAD', [
            'type' => Form::TYPE_FILE,
            'filesize' => 2,
            'filetype' => 'image'
        ]);

        $form->url_set = ($this->url->get('bs/test/setdata'));

        $this->view->enable();
        $this->view->setVar('di', $this);
        $this->view->setVars($form->get_form());

        $this->view->pick('bs/testIndex');

        $this->logs->set(LOG_OPEN_PAGE);

    }

    public function setdataAction()
    {
//        print_r($_POST);
        $pic = $_FILES['UPLOAD'];

        $base_dir = $_SERVER['DOCUMENT_ROOT'] . $this->url->get() . 'public/uploads/bs1/';
        $pname = get_private_name();
        $ext = get_ext_file($pic['name']);
        if (move_uploaded_file($_FILES['UPLOAD']['tmp_name'], $base_dir . $pname . $ext)) {
            echo json_encode(['success' => TRUE, 'msg' => 'การอัพโหลดเสร็จสิ้น']);
        }

//        echo $base_dir . $pname . $ext . PHP_EOL;
//        print_r($_FILES);
    }


} 