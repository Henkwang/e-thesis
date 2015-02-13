<?php
namespace EThesis\Controllers\System;


class ModuleController extends \Phalcon\Mvc\Controller
{
    var $module_model;

    protected function initialize()
    {

    }

    public function indexAction()
    {
        $this->view->disable();
        $menuClass = new \EThesis\Library\MenuEThesis();
//        echo '<pre>';
        $this->_getTable();
//        echo '</pre>';
    }


    public function setdataAction($set = '', $id = '')
    {
        $this->_set_data($set);

    }

    private function _config()
    {
        $form = new \EThesis\Library\Form('SYS_LABEL');

        return $form;
    }


    private function _getTable(){
        $table = new \EThesis\Library\Datatable();
        $table->set_ajax($this->url->get('system/module/getdata'));
        $table->add_column('MOD_ID');
        $table->add_column('MOD_PARANT_ID');
        $table->add_column('MOD_LEVEL');
        $table->add_column('MOD_ORDER');
        $table->add_column('MOD_NAME_TH');
        $table->add_column('MOD_NAME_EN');

        echo $table->get_table();
    }

    private function _getTable1()
    {
        //$result = $this->module_model->select_by_filter();
        echo '<div class="row"><button class="btn" onclick="recall()">test</button><input id="AA"><input id="BB"> </div>';
        echo '<div class="row"><div class="col-xs-12"><table id="example" class="display" cellspacing="0" width="100%"> </table> </div> </div>';
        echo '<script>
            var oTable;
            $(document).ready(function() {
               $(\'#example\').dataTable( {
                    "dom": \'rt<"bottom"ilp>\',
                    "processing": true,
                    "serverSide": true,
                    "columns": [{"visible": false,"title":"ID","name":"ID"},{"title":"PARENT_ID"},{"title":"LEVEL"},{"title":"ORDER"},{"title":"NAME"}],
                    "ajax": {"url":"' . \EThesis\Library\DIPhalcon::get('url')->get('system/module/getdata') . '", "type":"POST"}
                } );
                oTable = $(\'#example\').DataTable();
            } );

            function recall(){
               oTable.search(encode_json(getOTableSearch())).draw();
            }
            function getOTableSearch(){
                    var data = {"aa":$("#AA").val(),"ba":$("#BB").val()};
                    return data;
                                }
            </script>';
    }

    public function getdataAction()
    {
        //print_r($_POST);
        $this->module_model = new \EThesis\Models\System\Module_model();
        $count = $this->module_model->count_by_filter();
        $result = $this->module_model->select_by_filter();
        $responce = [
            'drow' => 1,
            'recordsTotal' => $count,
            'recordsFiltered' => $result->RecordCount(),
            'data' => []
        ];
        if ($result && $result->RecordCount() > 0) {
            while ($row = $result->FetchRow()) {
                $responce['data'][] = [
                    $row['MOD_ID'], $row['MOD_PARENT_ID'], $row['MOD_LEVEL'], $row['MOD_ORDER'], $row['MOD_NAME_TH'], $row['MOD_NAME_EN']
                ];
            }

        }
        echo json_encode($responce);
    }

    private function createMenu(&$html, $tree)
    {
        $html .= '<ul>';
        foreach ($tree as $node) {
            if (!empty($node['children'])) {
                $html .= '<li>' . $node['name'] . '</li>';
                $this->createMenu($html, $node['children']);
            } else {
                $html .= '<li>' . $node['name'] . '</li>';
            }
        }
        $html .= '</ul>';
    }


}