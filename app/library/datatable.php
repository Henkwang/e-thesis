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
    private $_lang = 'th';


    /*
     * Script DataTable;
     */
    private $_arr_script_datatable = '';
    private $_script;
    private $_url;


    /*
     * Data Columns
     */
    private $_data_columns = [];
    private $_index_field = '';
    private $primary_id = '';
    private $_button = [];

    /*
     * Extension Class
     */
    // Class Lang
    private $_liv_lang;
    // Class Model
    public $model;


    /*
     * Init
     */
    public function __construct($p_id)
    {
        $session = \EThesis\Library\DIPhalcon::get(ET_SV_SESSION);

        $this->_lang = ($session->has('lang') ? $session->get('lang') : 'th');


        $this->_liv_lang = new \EThesis\Library\Lang($this->_lang);

        $this->primary_id = $p_id;

        $this->init();


    }

    private function init()
    {

        $d['processing'] = true;
        $d['serverSide'] = true;
        if ($this->_lang == 'th') {
            $d['language'] = ['url' => '//cdn.datatables.net/plug-ins/f2c75b7247b/i18n/Thai.json'];

        }
        $this->set_script_id_datatable($d);
        $this->_table_full_id = 'table_' . rand(0, 99999);
    }




    /*
     * Method
     * Get
     */

    /**
     * @return string
     */
    public function get_table()
    {
        $col = [];
        if (!empty($this->_button['td'])) {
            $this->add_column('BUTTON', ['align' => 'center', 'width' => '100px', 'order' => false]);
        }
        if (!empty($this->_button['top'])) {
            $script = '';
            foreach ($this->_button['top'] as $val) {
                $script .= '$("#top_' . $this->_table_full_id . '").append(\'' . ($val) . '\');';
            }
            $this->add_scriptAfter($script);
        }
        foreach ($this->_data_columns as $row) {
            $col[] = $row;
        }

        $this->set_script_id_datatable(['columns' => $col]);

        $html = '<div class="row"><div class="col-xs-12">' . "\n";
        $html .= '<table id="' . $this->_table_full_id . '" class="display cell-border" cellspacing="0" width="100%"> </table>' . "\n";
        $html .= '</div></div>' . "\n";
        $html .= '<script charset="utf-8" type="text/javascript">'
            . 'var oTable;'
            . '$(document).ready(function() { '
            . 'var tableData = decode_json(\'' . json_encode($this->_arr_script_datatable) . '\');'
            . 'tableData.dom = \'<"#top_' . $this->_table_full_id . '\">rt<"bottom"ilp>\';'
            . 'oTable = $("#' . $this->_table_full_id . '").DataTable(tableData);'
            . 'oTable.on("init.dt", function(){' . $this->_script['after'] . '});'
            . ' }); '
            . $this->_script['more']

            . '</script>';

        return $html;
    }

    /**
     * @return array
     */
    public function get_culumns()
    {
        return $this->_data_columns;
    }

    /**
     * @return array
     */
    public function get_data()
    {
        $p = $_POST;
        $col = array_keys($this->_data_columns);
        $sort = [];
        foreach ($p['order'] as $i => $val) {
            $sort [] = "{$col[$val['column']]} {$val['dir']}";
        }
        $order = implode(',', $sort);

        $json = json_decode($p['search']['value'], JSON_OBJECT_AS_ARRAY);
        $filter = [];
        if (is_array($json)) {
            $filter = $json;
        }

        $count = $this->model->count_by_filter($filter);

        $col [] = $this->primary_id . " AS DT_RowId";
        $result = $this->model->select_by_filter($col, $filter, $order, $p['length'], $p['start']);

        $response = [
            'draw' => $_POST['draw'],
            'recordsTotal' => 0,
            'recordsFiltered' => $count,
            'data' => []
        ];
        if ($result && $result->RecordCount() > 0) {
            $response['recordsTotal'] = $result->RecordCount();
            while ($row = $result->FetchRow()) {
                if (!empty($this->_button['td']))
                    $row['BUTTON'] = implode(' | ', $this->_button['td']);
                $response['data'][] = $row;
            }
        }
        return $response;

    }


    /*
     * Method
     * Set
     */

    /**
     * @param $name
     */
    public function table_name($name)
    {
        $this->_table_full_id = $name;
    }


    /**
     * @param $id
     * @param array $param
     */
    public function add_column($id, array $param = array())
    {
        $this->_index_field = $id;
        $this->_data_columns[$id] = [
            'name' => $id,
            'data' => $id,
            'className' => (isset($param['align']) ? "{$param['align']}" : "left"),
            'searchable' => false,
            'orderable' => (isset($param['order']) && $param['order'] == false ? false : true),
            'width' => (!empty($param['width']) ? $param['width'] : null),
            'visible' => (isset($param['visible']) && $param['visible'] == false ? false : true),
            'title' => (isset($param['title']) ? $param['title'] : $this->_liv_lang->label($id)),
        ];
    }

    public function add_lineNumber()
    {
        $this->add_column('LineNumber', ['order' => false]);
    }

    public function add_scripBefor($script)
    {
        $this->_script['befor'] .= $script;
    }

    public function add_scriptAfter($script)
    {
        $this->_script['after'] .= $script;
    }

    public function add_scriptMore($script)
    {
        $this->_script['more'] .= $script;
    }

    public function add_button($name, $pos = 'gt', $href = '', $icon = '', $class = '')
    {
        $btn = '';
        if ($name == 'ADD') {
            $href = 'datatable_manage';
            $class = 'primary';
            $icon = 'md-add';
        } else if ($name == 'EDIT') {
            $href = 'datatable_manage';
            $class = 'warning';
            $icon = 'md-create';
        } else if ($name == 'DELETE') {
            $href = 'datatable_manage';
            $class = 'danger';
            $icon = 'md-remove';
        }
        $label = $this->_liv_lang->label($name);
        if ($pos == 'gt' || $pos == 'g') {
            $this->_button['td'][] = '<a href="javascript:' . $href . '(\'' . $name . '\', this);" class="text-' . $class . '" title="' . $label . '"><i class="' . $icon . '"></i></a>';
        }
        if ($pos == 'gt' || $pos == 't') {
            $this->_button['top'][] = '<a href="javascript:' . $href . '(\\\'' . $name . '\\\', this);" class="btn btn-' . $class . '" title="' . $name . '"><i class="' . $icon . '"> ' . $label . '</i></a>';
        }


    }

    public function set_ajax($full_url_get = '', $full_url_set = '')
    {
        $this->_url['get'] = $full_url_get;
        $this->_url['set'] = $full_url_set;
        $this->set_script_id_datatable([
            'ajax' => [
                'url' => $full_url_get,
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