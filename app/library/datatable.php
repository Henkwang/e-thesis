<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 12/2/2558
 * Time: 11:11
 */

namespace EThesis\Library;


class Datatable
{

    private $_table_full_id = '';

    private $_id = '';

    private $_html = '';

    private $_arr_script_datatable = '';

    private $_lang = 'th';

    public $model;

    private $_data_columns;

    private $_liv_lang;

    private $_index_field;

    public function __construct()
    {
        $session = \EThesis\Library\DIPhalcon::get(ET_SV_SESSION);

        $this->_lang = ($session->has('lang') ? $session->get('lang') : 'th');


        $this->_liv_lang = new \EThesis\Library\Lang($this->_lang);

        $this->init();


    }

    private function init()
    {
        $d['processing'] = true;
        $d['serverSide'] = true;
        if ($this->_lang == 'th') {
            $d['language'] = ['url'=>'//cdn.datatables.net/plug-ins/f2c75b7247b/i18n/Thai.json'];
            $this->set_script_id_datatable($d);
        }
        $this->_table_full_id = 'table_' . rand(0, 99999);
    }

    public function get_table()
    {
        $html = '<table id="' . $this->_table_full_id . '" class="display" cellspacing="0" width="100%"> </table>';
        $html .= '<script>$(document).ready(function() {$("#' . $this->_table_full_id . '").dataTable(decode_json(\'' . json_encode($this->_arr_script_datatable) . '\'));} ); </script>';
        return $html;
    }

    public function table_name($name)
    {
        $this->_table_full_id = $name;
    }

    public function add_column($id, array $param = array())
    {
        $this->_index_field = $id;
        $this->_data_columns[$id] = [
            'name' => $id,
            'data' => $param['data'],
            'visible' => (isset($param['visible']) && $param['visible'] == false ? false : true),
            'title' => $this->_liv_lang->label($id)
        ];
    }

    public function set_ajax($full_url = '', $data ='')
    {
        $this->set_script_id_datatable([
            'ajax' => [
                'url' => $full_url,
                'type' => 'POST',
                //'data' => $data
            ]
        ]);
    }


    public function set_script_id_datatable(array $data_array = [])
    {
        if (!empty($data_array)) {
            foreach ($data_array as $key => $val) {
                $this->_arr_script_datatable[$key] = $val;
            }
            return TRUE;
        }
        return FALSE;
    }


}