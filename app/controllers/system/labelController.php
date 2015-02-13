<?php
namespace EThesis\Controllers\System;


class LabelController extends \Phalcon\Mvc\Controller
{
    protected function initialize()
    {

    }

    public function indexAction()
    {
        $this->view->enable();
        $this->backend;
        $this->frontend;
        \Phalcon\Tag::setTitle('Form Lable');
        $this->_get_form();
    }

    public function testAction(){
        echo 'aaaaa';
    }

    public function setdataAction( $set = '', $id = ''){
        $this->_set_data($set);
    }

    private function _config()
    {
        $form = new \EThesis\Library\Form('SYS_LABEL');


        $form->add_field('LBL_NAME', 'ชื่อข้อความ');
        $form->add_text([
            'type' => 'text',
            'col' => 6,
            'offset' => 0,
            'required' => TRUE,
            'maxlength' => 50,
        ]);
        $form->add_field('LBL_GRID_TH', 'ตาราง(TH)');
        $form->add_text([
            'type' => 'text',
            'col' => 6,
            'offset' => 0,
            'required' => TRUE,
            'maxlength' => 100,
        ]);
        $form->add_field('LBL_BTN_TH', 'ปุ่มกด(TH)');
        $form->add_text([
            'type' => 'text',
            'col' => 6,
            'offset' => 0,
            'required' => TRUE,
            'maxlength' => 100,
        ]);
        $form->add_field('LBL_FORM_TH', 'ฟอร์ม(TH)');
        $form->add_text([
            'type' => 'text',
            'col' => 6,
            'offset' => 0,
            'required' => TRUE,
            'maxlength' => 100,
        ]);
        $form->add_field('LBL_GRID_EN', 'ตาราง(EN)');
        $form->add_text([
            'type' => 'text',
            'col' => 6,
            'offset' => 0,
            'required' => TRUE,
            'maxlength' => 100,
        ]);
        $form->add_field('LBL_BTN_EN', 'ปุ่มกด(EN)');
        $form->add_text([
            'type' => 'text',
            'col' => 6,
            'offset' => 0,
            'required' => TRUE,
            'maxlength' => 100,
        ]);
        $form->add_field('LBL_FORM_EN', 'ฟอร์ม(EN)');
        $form->add_text([
            'type' => 'text',
            'col' => 6,
            'offset' => 0,
            'required' => TRUE,
            'maxlength' => 100,
        ]);
        return $form;
    }

    private function _get_form()
    {
        $form = $this->_config();
        $data = $form->get_data_form();
        $this->view->setVars($data);
        $this->view->pick('/system/label');
    }

    private function _set_data($set)
    {
        $form = $this->_config();
        if ($set == 'add') {
            $inputname = array_keys($form->get_inputSet());
            $data = array();
            foreach ($inputname as $key => $val) {
                if ($_POST[$val]) {
                    $data[$key] = $_POST[$val];
                }
            }
            $label_model = new \EThesis\Models\System\Label_model();
            $result = $label_model->insert($data);
            return $result;
        }
    }


}