<?php
namespace EThesis\Controllers\System;

use \EThesis\Library\Form AS Form;

class ModuleController extends \Phalcon\Mvc\Controller
{

    protected function initialize()
    {
        $Lang = new \EThesis\Library\Lang($this->session->get('lang'));
        $this->session->check_auth_die($Lang->label('ERROR_AUTH'));


        $this->view->enable();
        $this->view->setVar('Lang', $Lang);
        $this->view->setVar('di', $this);
    }

    public function indexAction()
    {


        $form = new Form();
        $form->param_default['required'] = false;

        $form->add_input('MOD_PARENT_ID', [
            'type' => Form::TYPE_NUMBER,
            'label' => 'รหัสโหนดแม่',
        ]);
        $form->add_input('MOD_CODE', [
            'type' => Form::TYPE_NUMBER,
            'label' => 'รหัสโหนด',
        ]);

        $form->add_input('MOD_NAME_TH', [
            'type' => Form::TYPE_TEXT
        ]);
        $form->add_input('MOD_NAME_EN', [
            'type' => Form::TYPE_TEXT
        ]);
        $form->add_input('MOD_URL', [
            'type' => Form::TYPE_TEXT
        ]);
        $form->add_input('ENABLE', [
            'type' => Form::TYPE_SELECT,
            'datalang' => 'ENABLE',
            'value' => 'T',
        ]);


        $this->view->setVars($form->get_form());

        $this->view->setVar('burl', $this->url->get('system/module/'));

        $this->view->pick('system/moduleIndex');

    }

    private function config(){
        $form = new Form();
        $form->set_model(new \EThesis\Models\System\Sys_module_model());
        $form->set_urlset($this->url->get('system/module/setdata'));

        $form->add_input('MOD_PARENT_ID', [
            'type' => Form::TYPE_NUMBER,
            'label' => 'รหัสโหนดแม่',
        ]);
        $form->add_input('MOD_ORDER', [
            'type' => Form::TYPE_NUMBER,
        ]);
        $form->add_input('MOD_NAME_TH', [
            'type' => Form::TYPE_TEXT
        ]);
        $form->add_input('MOD_NAME_EN', [
            'type' => Form::TYPE_TEXT
        ]);
        $form->add_input('MOD_LEVEL', [
            'type' => Form::TYPE_NUMBER,
        ]);
        $form->add_input('MOD_URL', [
            'type' => Form::TYPE_TEXT,
        ]);
        $form->add_input('ENABLE', [
            'type' => Form::TYPE_SELECT,
            'datalang' => 'ENABLE',
            'value' => 'T',
        ]);

        return $form;
    }


    public function getformAction($manage, array $data = [])
    {
        $form = $this->config();
        $form->url_set .= '/' . $manage;
        $this->view->disable();
        if ($manage == 'EDIT') {
            $form->get_formedit($_POST['pk_id']);
        }
        echo $form->get_inputgroup_complate($_POST['tablename']);
    }

    public function setdataAction($set)
    {
        $form = $this->config();
        if (!empty($set)) {
            $msg = $form->set_data($set);
            echo $msg;
        }
    }


    public function getdataAction()
    {
        $Module_model = new \EThesis\Models\System\Sys_module_model();
        $post = $_POST;
        $col = [];
        $i = 0;
        while (isset($post['columns'][$i])) {
            if ($post['columns'][$i]['name'] !== 'MANAGE') {
                if ($post['columns'][$i]['name'] == 'pk_id') {
                    $col[$i] = $Module_model->primary . ' [pk_id]';
                } else {
                    $col[$i] = $post['columns'][$i]['name'];
                }
            }
            $i++;
        }
        if (!empty($post['search']['value'])) {
            $search = json_decode($post['search']['value'], JSON_OBJECT_AS_ARRAY);
            $filter = array_column($search, 'value', 'name');

        } else {
            $filter = ['ENABLE' => 'T'];
        }

        $order = $post['columns'][$post['order'][0]['column']]['name'] . ' ' . $post['order'][0]['dir'];
        $result = $Module_model->select_by_filter($col, $filter, $order, $post['start'], $post['length']);
        $count = $Module_model->count_by_filter($filter);
        $data = [
            "draw" => $post['draw']++,
            "recordsTotal" => $post['length'],
            "recordsFiltered" => $count,
            'data' => ($result ? $result->GetAll() : []),
        ];
        echo json_encode($data);
    }


}