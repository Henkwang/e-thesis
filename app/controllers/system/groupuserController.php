<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 18/2/2558
 * Time: 9:06
 */

namespace EThesis\Controllers\System;

use \EThesis\Library\Form AS Form;


class GroupuserController extends \Phalcon\Mvc\Controller
{


    protected function initialize()
    {
        $Lang = new \EThesis\Library\Lang($this->session->get('lang'));

        $this->view->enable();
        $this->view->setVar('Lang', $Lang);
        $this->view->setVar('di', $this);
    }

    public function indexAction()
    {

        $form = new Form();
        $form->add_input('GRP_NAME_TH', [
            'type' => Form::TYPE_TEXT,
        ]);
        $form->add_input('GRP_NAME_EN', [
            'type' => Form::TYPE_TEXT
        ]);
        $form->add_input('ENABLE', [
            'type' => Form::TYPE_SELECT,
            'datalang' => 'ENABLE',
            'value' => 'T'
        ]);

        $form->add_input('GRP_USER_TYPE', [
            'type' => Form::TYPE_TEXT,
        ]);

        $this->view->setVars($form->get_form());

        $this->view->setVar('burl', $this->url->get('system/groupuser/'));

        $this->view->pick('system/groupuserIndex');


    }

    private function config()
    {
        $form = new Form();

        $form->set_model(new \EThesis\Models\System\Sys_groupuser_model());
        $form->set_urlset($this->url->get('system/groupuser/setdata'));

        $form->add_input('GRP_NAME_TH', [
            'type' => Form::TYPE_TEXT,
        ]);
        $form->add_input('GRP_NAME_EN', [
            'type' => Form::TYPE_TEXT,
        ]);
        $form->add_input('ENABLE', [
            'type' => Form::TYPE_RADIO,
            'datalang' => 'ENABLE'
        ]);
        $form->add_input('GRP_TOPMENU', [
            'type' => Form::TYPE_NUMBER,
            'required' => false,
            'formsearch' => false,
        ]);
        $form->add_input('GRP_USER_TYPE', [
            'type' => Form::TYPE_SELECT,
            'required' => false,
            'datalang' => 'USER_TYPE',
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
        $Module_model = new \EThesis\Models\System\Sys_groupuser_model();
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
        $result = $Module_model->select_by_filter($col, $filter, $order, $post['length'], $post['start']);
        $rows = [];
        if($result && $result->RecordCount() > 0){
            $rows =  $result->GetAll();

        }
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