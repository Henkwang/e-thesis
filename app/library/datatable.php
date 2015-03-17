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

    public $table_id = 'datatable';

    public $default_table = [
        'processing' => true,
        'serverSide' => true,
        'language' => 'en',
        'dom' => '<#top_{$}">rt<bottom"ilp>',
        'class' => 'display cell-border hover',
        'cellspacing' => "0",
        'width' => "100%",
    ];
    public $default_column = [
        'name' => '',
        'data' => [],
        'className' => null,
        'searchable' => false,
        'orderable' => true,
        'width' => null,
        'visible' => true,
        'title' => ''
    ];

    // Model Get Data
    public $Model;


    private $script_event = [];
    private $_columns = [];
    private $_table = [];

    public function __construct($datatable_name = null)
    {
        $this->table_id = (empty($datatable_name) ? $this->table_id : $datatable_name);
        $this->table_id .= '_' . (substr(sha1(rand(0, 9999)), rand(0, 10), 10));
        $this->default_table['language'] = ['url' => '//cdn.datatables.net/plug-ins/f2c75b7247b/i18n/Thai.json'];

    }

    public function get_table()
    {
        $markup = $this->_table;
        $html = '<div class="row"><div class="col-xs-12">' . "\n";
        $html .= '<table id="' . $this->table_id . '" class="' . $markup['class'] . '" cellspacing="' . $markup['cellspacing'] . '" width="' . $markup['width'] . '"> </table>' . "\n";

        unset($markup['class']);
        unset($markup['cellspacing']);
        unset($markup['width']);

        $markup['columns'] = $this->_columns;
        $html .= '<script charset="utf-8" type="text/javascript">
                    var ' . $this->table_id . ' = $("#' . $this->_table_full_id . '").DataTable(decode_json(\'' . json_encode($markup) . '\'));
                    </script>';
        $html .= '</div></div>';
    }

    public function set_table(array $param = [])
    {
        $def = $this->default_table;
        $this->_table = $def;
    }

    public function add_column($name, array $param = [])
    {
        $def = $this->default_column;
        $cof = $def;
        $cof['name'] = $name;
        $cof['data'] = (empty($param['data']) ? $param['data'] : $name);
        $cof['className'] = (empty($param['className']) ? $param['className'] : $def['className']);
        $cof['searchable'] = (empty($param['searchable']) ? $param['searchable'] : $def['searchable']);
        $cof['orderable'] = (empty($param['orderable']) ? $param['orderable'] : $def['orderable']);
        $cof['width'] = (empty($param['width']) ? $param['width'] : $def['width']);
        $cof['visible'] = (empty($param['visible']) ? $param['visible'] : $def['visible']);
        $cof['title'] = (empty($param['title']) ? $param['title'] : $def['title']);
        foreach ($cof as $key => $val) {
            if ($val == null) {
                unset($cof[$key]);
            }
        }
        $this->_columns[$name] = $cof;
    }


}