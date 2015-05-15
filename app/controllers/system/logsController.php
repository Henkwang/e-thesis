<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 18/2/2558
 * Time: 9:06
 */

namespace EThesis\Controllers\System;

use \EThesis\Library\Form AS Form;


class LogsController extends \Phalcon\Mvc\Controller
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
        $form->add_input('LOG_USER', [
            'type' => Form::TYPE_TEXT,
            'label' => 'ชื่อผู้ใช้งาน',
        ]);
        $form->add_input('LOG_PAGE', [
            'type' => Form::TYPE_TEXT,
            'label' => 'หน้าใช้งาน',
        ]);
        $form->add_input('LOG_PROCESS', [
            'type' => Form::TYPE_SELECT,
            'datalang' => 'LOG_PROCESS',
        ]);

        $form->add_input('LOG_DATE', [
            'type' => Form::TYPE_DATE,
            'label' => 'เวลาบันทึก',
        ]);
        $form->add_input('LOG_BROWSER_INFO', [
            'type' => Form::TYPE_TEXT,
            'label' => 'เบราเซอร์ที่ใช้',
        ]);

        $this->view->setVars($form->get_form());

        $this->view->pick('system/logIndex');


    }

    public function getdataAction()
    {
        $Module_model = new \EThesis\Models\System\Sys_log_model();
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
            $filter['LOG_DATE'] = datetime_to_sql($filter['LOG_DATE']);
        } else {
            $filter['ENABLE'] = 'T';
        }

        $order = $post['columns'][$post['order'][0]['column']]['name'] . ' ' . $post['order'][0]['dir'];
        $result = $Module_model->select_by_filter($col, $filter, $order, $post['length'], $post['start']);
        $count = $Module_model->count_by_filter($filter);
        $rows = [];
        while ($row = @$result->FetchRow()) {
            $row['LOG_DATE'] = view_date($row['LOG_DATE'], 'dt', true);
            $rows[] = $row;
        }
        $data = [
            "draw" => $post['draw']++,
            "recordsTotal" => $post['length'],
            "recordsFiltered" => $count,
            'data' => $rows,
        ];
        echo json_encode($data);
    }
} 