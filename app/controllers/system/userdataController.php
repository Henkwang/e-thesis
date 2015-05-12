<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 18/2/2558
 * Time: 9:06
 */

namespace EThesis\Controllers\System;

use \EThesis\Library\Form AS Form;


class UserdataController extends \Phalcon\Mvc\Controller
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
        $form->add_input('USR_CODE', [
            'type' => Form::TYPE_TEXT,
        ]);
        $form->add_input('USR_DISPLAY', [
            'type' => Form::TYPE_TEXT
        ]);
        $form->add_input('USR_USERNAME', [
            'type' => Form::TYPE_TEXT,
        ]);


        $form->add_input('FACULTY_ID', [
            'type' => Form::TYPE_SELECT,
            'datamodel' => 'MAS_FACULTY'
        ]);

        $this->view->setVars($form->get_form());

        $this->view->pick('system/userIndex');


    }

    public function getdataAction()
    {
        $Module_model = new \EThesis\Models\System\Sys_user_model();
        $post = $_POST;
        $col = [];
        $i = 0;
        while (isset($post['columns'][$i])) {
            if ($post['columns'][$i]['name'] !== 'MANAGE') {
                $col[$i] = $post['columns'][$i]['name'];
            }
            $i++;
        }
        if (!empty($post['search']['value'])) {
            $search = json_decode($post['search']['value'], JSON_OBJECT_AS_ARRAY);
            $filter = array_column($search, 'value', 'name');

        } else {
            $filter = [];
        }

        $order = $post['columns'][$post['order'][0]['column']]['name'] . ' ' . $post['order'][0]['dir'];
        $result = $Module_model->select_by_filter($col, $filter, $order, $post['length'], $post['start']);
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