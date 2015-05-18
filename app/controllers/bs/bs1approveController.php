<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 16/2/2558
 * Time: 13:33
 */


namespace EThesis\Controllers\Bs;

use \EThesis\Library\Form AS Form;

class bs1approveController extends \Phalcon\Mvc\Controller
{


    private $_lang = 'th';

    private $process_success = 5;

    private $approve_process_order = 2;


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

        $form->add_input('ASEAN_STATUS', [
            'type' => Form::TYPE_TEXT,
        ]);

        $form->add_input('ACAD_YEAR', [
            'type' => Form::TYPE_TEXT
        ]);

        $form->add_input('FACULTY_ID', [
            'type' => Form::TYPE_SELECT,
            'datamodel' => 'MAS_FACULTY',
        ]);

        $form->add_input('PROGRAM_ID', [
            'type' => Form::TYPE_SELECT,
            'datamodel' => 'MAS_PROGRAM',
            'select_filter' => 'FACULTY_ID',
        ]);

        $form->add_input('ADVISER_STATUS', [
            'type' => Form::TYPE_SELECT,
            'datalang' => 'ADVISER_STATUS',
        ]);

        $form->add_input('NAME_TH', [
            'type' => Form::TYPE_TEXT,
        ]);


        $this->view->setVars($form->get_form());

        $this->view->setVar('burl', $this->url->get('bs/bs1approve/'));

        $this->view->pick('bs/bs1approve');

    }


    public function getdeteilprocessAction($pk_id = '')
    {
        $response = ['success' => false, 'data' => []];
        $data = [];
        if (!empty($pk_id)) {
            $dbmodl = new \EThesis\Controllers\Ajax\AutocompleteController();
            $data['BS1_PROCESS'] = $dbmodl->get_dataModel('BS1_PROCESS');

            $fill_class = new \EThesis\Library\Autofill();
            $bs1_process_history_model = new \EThesis\Models\Bs\Bs1_process_history_model();
            $field = ['BS1_PROCESS_ID', 'BS1_HIS_ORDER', 'BS1_HIS_STATUS', 'BS1_HIS_REMARK', 'BS1_HIS_DATE'];
            $filter = ['BS1_ID' => $pk_id, 'BS1_HIS_ORDER' => $bs1_process_history_model->get_num_max_order_by_bs1($pk_id)];
            $result = $bs1_process_history_model->select_by_filter($field, $filter);
            $data['BS1_HIS'] = [];
            $data['BS1_POP'] = [];
            $data['pk_id'] = $pk_id;

            if (is_object($result) && $result->RecordCount() > 0) {
                while ($row = $result->FetchRow()) {
                    $data['BS1_HIS'][$row['BS1_PROCESS_ID']] = [
                        'BS1_HIS_ORDER' => $row['BS1_HIS_ORDER'],
                        'BS1_HIS_STATUS_TF' => $row['BS1_HIS_STATUS'],
                        'BS1_HIS_STATUS' => $fill_class->fill_lang('BS1_HIS_STATUS', $row['BS1_HIS_STATUS']),
                        'BS1_HIS_REMARK' => $row['BS1_HIS_REMARK'],
                        'BS1_HIS_DATE' => view_date($row['BS1_HIS_DATE'], 'dt', true),
                    ];
                }
            }

            /*  GET POP E-Thesis */
            $bs1_Model = new \EThesis\Models\Bs\Bs1_master_model();
            $result = $bs1_Model->select_by_filter(['POP_INS_ID', 'POP_HEAD_THESIS_ID', 'POP_COM_THESIS_ID', 'POP_INS_IS_ID'], ['IN_ID' => $pk_id]);
            if (is_object($result) && $result->RecordCount() > 0) {
                while ($row = $result->FetchRow()) {
                    $data['BS1_POP'] = [
                        'POP_INS_ID' => $row['POP_INS_ID'],
                        'POP_HEAD_THESIS_ID' => $row['POP_HEAD_THESIS_ID'],
                        'POP_COM_THESIS_ID' => $row['POP_COM_THESIS_ID'],
                        'POP_INS_IS_ID' => $row['POP_INS_IS_ID'],
                    ];
                }
            }
            /* END */


            $response['success'] = true;
        }
        $response['data'] = $data;
        //        echo '<pre>';
//        print_r($response);
        echo json_encode($response);
    }


    public function setdataAction($set)
    {

        $response = default_response('ผิดพลาด! การเข้าถึงไม่ถูกต้อง');
        $set = strtolower($set);
        if ($set == 'send_po2') {
            $response = default_response('ผิดพลาด! ไม่สามารถดำเนินการส่งแบบฟอร์มได้');
            if (!empty($_POST['pk_id'])) {
                $pk_id = $_POST['pk_id'];
                $bph_model = new \EThesis\Models\Bs\Bs1_process_history_model();
                $max_his_order = $bph_model->get_num_max_order_by_bs1($pk_id);
//                $max_his_order += 1;
                $data = [
                    'BS1_PROCESS_ID' => $this->approve_process_order,
                    'BS1_HIS_ORDER' => $max_his_order,
                    'BS1_ID' => $pk_id,
                    'BS1_HIS_STATUS' => $_POST['BS1_HIS_STATUS'],
                    'BS1_HIS_REMARK' => $_POST['BS1_HIS_REMARK'],
                    'BS1_HIS_DATE' => 'GETDATE()',
                    'BS1_USER_APPROVE' => $this->session->get('name'),
                    'BS1_NAME_APPROVE' => $this->session->get('username'),
                ];
                $bs1_model = new \EThesis\Models\Bs\Bs1_master_model();
                $bs1_model->field_update = ['BS1_PROCESS_ORDER', 'BS1_POP_THESIS_ID', 'BS1_LAST_APPROVE'];
                $bph_model->adodb->BeginTrans();
                $ok = (
                    $bph_model->insert($data) &&
                    $bs1_model->update(
                        [
                            'BS1_PROCESS_ORDER' => $this->approve_process_order,
                            'BS1_POP_THESIS_ID' => ($_POST['BS1_HIS_STATUS'] == 'F' ? '' : implode(',', $_POST['BS1_POP_THESIS_ID'])),
                            'BS1_LAST_APPROVE' => $_POST['BS1_HIS_STATUS'],
                        ], $pk_id)
                );
                $bph_model->adodb->CommitTrans($ok);
                if ($ok) {
                    $response['success'] = true;
                    $response['msg'] = 'ดำเนินการส่งแบบฟอร์มสำเร็จ';
                }
            }
        }
        echo json_encode($response);
    }


    public function getdataAction()
    {
        $Module_model = new \EThesis\Models\Bs\Bs1_master_model();
        $fill_class = new \EThesis\Library\Autofill();
        $post = $_POST;
        $col = [];
        $i = 0;
        while (isset($post['columns'][$i])) {
            if ($post['columns'][$i]['name'] !== 'MANAGE') {
                if ($post['columns'][$i]['name'] == 'pk_id') {
                    $col[$i] = $Module_model->primary . ' [pk_id]';
                } else {
                    if (!in_array($post['columns'][$i]['name'], [ 'BS1_HIS_STATUS'])) {
                        $col[$i] = $post['columns'][$i]['name'];
                    }
                }
            }
            $i++;
        }
        $filter = [];
        if (!empty($post['search']['value'])) {
            $search = json_decode($post['search']['value'], JSON_OBJECT_AS_ARRAY);
            $filter = array_column($search, 'value', 'name');
        }
        $filter['BS1_PROCESS_ORDER'] = 1;
        $order = $post['columns'][$post['order'][0]['column']]['name'] . ' ' . $post['order'][0]['dir'];
        $result = $Module_model->select_by_filter($col, $filter, $order, $post['length'], $post['start']);
        $rows = [];
        if ($result && $result->RecordCount() > 0) {

//            $bs1_process_history_model = new \EThesis\Models\Bs\Bs1_process_history_model();
            while ($row = $result->FetchRow()) {
                $row['ASEAN_STATUS'] = $fill_class->fill_asean($row['ASEAN_STATUS']);
                $row['ADVISER_STATUS'] = $fill_class->fill_lang('ADVISER_STATUS', $row['ADVISER_STATUS']);


                $cp = ($row['BS1_PROCESS_ORDER'] ? $row['BS1_PROCESS_ORDER'] : 0);
                $pr_cc = '';
                $wp = 'กำลังดำเนินการ..';
                if ($cp == 5 && $row['BS1_LAST_APPROVE'] == 'T') {
                    $wp = 'ผ่าน';
                }
                if ($row['BS1_LAST_APPROVE'] == 'F') {
                    $pr_cc = 'progress-bar-danger';
                    $wp = 'ไม่ผ่าน';
                }

                $w = round(($cp) * (100 / ($this->process_success)));
                $row['BS1_PROCESS_ORDER'] = ' <small> ' . $wp . '[' . round($cp) . "/$this->process_success" . ']</small>
                <div class="progress" style="margin: 2px 15px"><div class="progress-bar ' . $pr_cc . '" style="width: ' . $w . '%"></div>
                </div>';


                $rows[] = $row;
            }


        }
        $count = $Module_model->count_by_filter($filter);
        $data = [
            "draw" => $post['draw']++,
            "recordsTotal" => $post['length'],
            "recordsFiltered" => $count,
            'data' => $rows,
        ];
        echo json_encode($data);
    }

}