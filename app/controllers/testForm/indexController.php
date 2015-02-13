<?php
namespace EThesis\Controllers\TestForm;


class IndexController extends \Phalcon\Mvc\Controller
{
    protected function initialize()
    {
        $this->view->enable();
        \Phalcon\Tag::setTitle('Test Form');
    }

    public function indexAction()
    {
        $this->view->pick('/testForm/index');
    }

    private function _config()
    {
        $form = new \EThesis\Library\Form('Test');

        $form->add_field('ACAD_YEAR', 'ปีการศึกษา');
        $form->add_text([
            'type' => 'number',
            'col' => 6,
            'offset' => 0,
            'required' => TRUE,
            'maxlength' => 4,
            'minlength' => 4,
        ]);
        $form->add_field('SEMESTEM_ID', 'เทอม');
        $form->add_text([
            'type' => 'number',
            'col' => 6,
            'offset' => 0,
            'required' => TRUE,
            'maxlength' => 1
        ]);
        $form->add_field('DATE', 'วันที่');
        $form->add_datetime([
            'col' => 6,
            'offset' => 0,
            'required' => TRUE,
        ]);

        $form->add_field('FACULTY_ID', 'คณะ');
        $form->add_select([
            'data_db' => 'MAS_FACULTY',
            'col' => 6,
            'offset' => 0,
            'required' => TRUE,
        ]);

        $form->add_field('PROGRAM_ID', 'สาขา');
        $form->add_select([
            'data_db' => 'MAS_PROGRAM',
            'col' => 6,
            'offset' => 0,
            'required' => TRUE,
            'filter' => 'FACULTY_ID',
        ]);

        $form->add_field('CHECKBOX_TEST', 'อาจารย์');
        $form->add_checkbox([
            'data_db' => ['อาจารย์บัณฑิตศึกษา', 'อาจารย์พิเศษบัณฑิตศึกษา'],
            'col' => 6,
            'offset' => 0,
            'required' => TRUE,
        ]);

        $form->add_field('RADIO_TEST', 'เลือก');
        $form->add_radio([
            'data_db' => ['อาจารย์บัณฑิตศึกษา', 'อาจารย์พิเศษบัณฑิตศึกษา', 'asdf', 'wqer'],
            'col' => 6,
            'offset' => 0,
            'required' => TRUE,
        ]);

        return $form;
    }

    public function testclassAction()
    {
        //$this->view->disable();
        $form = $this->_config();
        $data = $form->get_data_form();
        $this->view->setVars($data);
        $this->view->pick('/testForm/testclass');
    }


}

