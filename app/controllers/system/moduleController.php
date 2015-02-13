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
        echo $this->_getTable()->get_table();
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


    private function _getTable()
    {
        $table = new \EThesis\Library\Datatable('MOD_ID');

        $table->model = new \EThesis\Models\System\Module_model();

        $table->set_ajax($this->url->get('system/module/getdata'));

        $table->add_column('MOD_ID', ['visible' => false]);
        $table->add_column('MOD_PARENT_ID', ['visible' => false,]);
        $table->add_column('MOD_LEVEL', [
            'align' => 'center',
            'width' => '100px'
        ]);
        $table->add_column('MOD_ORDER', [
            'align' => 'center',
            'width' => '100px',
        ]);
        $table->add_column('MOD_NAME_TH');
        $table->add_column('MOD_NAME_EN');

        $table->add_button('ADD', 't');
        $table->add_button('EDIT', 'g');
        $table->add_button('DELETE', 'g');

        $table->add_scriptMore('
        function datatable_manage(manage, elm){
            console.log(elm);
        }        ');

        return $table;
    }


    public function getdataAction()
    {
        $table = $this->_getTable();
        echo json_encode($table->get_data());
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