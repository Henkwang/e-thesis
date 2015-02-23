<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 18/2/2558
 * Time: 9:06
 */

namespace EThesis\Controllers\System;


class GroupuserController extends \Phalcon\Mvc\Controller {


    protected function initialize()
    {
//        if($this->sess->get('auth') == TRUE && $this->sess->(''))

    }

    public function indexAction(){
        $table = $this->_gettable();

        $menu = new \EThesis\Library\MenuEThesis();

        echo '<pre>';
        print_r($menu->tree);
        echo '</pre>';

//        echo $table->get_table();
    }

    private function _gettable(){
        $table = new \EThesis\Library\Datatable('GRP_ID');

        $table->model = new \EThesis\Models\System\Groupuser_model();

        $table->set_ajax($this->url->get('system/groupuser/getdata'));

        $table->add_column('GRP_NAME_TH',[]);

        $table->add_column('GRP_NAME_EN',[]);

        $table->add_column('ENABLE',[]);

        $table->add_column('GRP_TOPMENU',[]);

        $table->add_button('ADD', 't');
        $table->add_button('EDIT', 'g');
        $table->add_button('DELETE', 'g');

        return $table;
    }


    public function getdataAction()
    {
        $table = $this->_getTable();
        echo json_encode($table->get_data());
    }


} 