<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 30/1/2558
 * Time: 13:15
 */
namespace EThesis\Controllers\Ajax;

class AutocompleteController
{

    var $lang = 'th';
    private $_dbmodel;

    protected  function initialize()
    {

    }

    public function __construct(){
        $session = new \EThesis\Library\Session();
        $this->lang = ($session->has('lang') ? $session->get('lang') : 'TH');
        $this->_dbmodel = require( __DIR__ . "/../../config/dbmodel.php");
    }

    public function  dbmodelAction($db_model_name)
    {
        $data = $this->get_dataModel($db_model_name);
        return json_encode($data);
    }

    public function get_dataModel($db_model_name, array $search = [])
    {

        /*
         * เตรียมข้อมูล
         */
        $dbmodel = $this->_dbmodel;
//        print_r($dbmodel[$db_model_name]);
        if (!isset($dbmodel[$db_model_name])) {
//            echo 'aaaa';
            return [];
        }
        $cfe = $dbmodel[$db_model_name];
        $module = ucfirst($cfe['module']);
        $model = ucfirst($cfe['model']);
        $label = $cfe['label'];
        $p_id = $cfe['key'];
        $filter = (isset($cfe['filter']) && is_array($cfe['filter']) ? $cfe['filter'] : '');
        $order = (isset($cfe['order']) ? $cfe['order'] : '');
        $parent = (isset($cfe['parent']) && $cfe['parent'] !== FALSE ? $cfe['parent'] : FALSE);

        if (is_array($search)) {
            foreach ($search as $key => $val) {
                $filter[$key] = $val;
            }
        }
        /*
         * ดึงข้อมูล
         */
        $class = "EThesis\\Models\\{$module}\\$model";
        $md_class = new $class();
        $label = str_replace('_ML', '_' . strtoupper($this->lang), $label);
        $order = str_replace('_ML', '_' . strtoupper($this->lang), $order);

        $field = [($p_id) . ' AS [key]', $label . ' AS [label]'];
        if ($parent) {
            $field[] = "{$parent} AS [parent_id]";
        }


        $result = $md_class->select_by_filter($field, $filter, $order);
        $data = [];
        if ($result && $result->RecordCount() > 0) {
            if ($parent) {
                while ($row = $result->FetchRow()) {
                    $data[$row['parent_id']][$row['key']] = $row['label'];
                }
            } else {
                while ($row = $result->FetchRow()) {
                    $data[$row['key']] = $row['label'];
                }
            }

        }
        return $data;
    }

    public function searchAction($db_model_name)
    {
        if (!empty($_POST)) {
            $filter = [];
            foreach ($_POST as $key => $val) {
                $filter[$key] = $val;
            }
            $data = $this->get_search($db_model_name, $filter);
            return json_encode($data);
        } else {
            return [];
        }
    }

    public function get_search($db_model_name, array $search = [])
    {
        /*
       * เตรียมข้อมูล
       */
        if (!isset($this->_dbmodel[$db_model_name])) {
            return [];
        }
        $cfe = $this->_dbmodel[$db_model_name];
        $module = ucfirst($cfe['module']);
        $model = ucfirst($cfe['model']);
        $label = $cfe['label'];
        $pkid = $cfe['key'];
        $filter = '';
        $order = (isset($cfe['order']) ? $cfe['order'] : '');

        if (is_array($search)) {
            foreach ($search as $key => $val) {
                $filter[$key] = $val;
            }
        }
        /*
         * ดึงข้อมูล
         */
        $class = "EThesis\\Models\\{$module}\\$model";
        $md_class = new $class();
        $label = str_replace('_ML', '_' . strtoupper($this->lang), $label);
        $order = str_replace('_ML', '_' . strtoupper($this->lang), $order);
        $field = [($pkid) . ' AS [key]', $label . ' AS [label]'];

        $result = $md_class->select_by_filter($field, $filter, $order);
        $data = [];
        if ($result && $result->RecordCount() > 0) {
            while ($row = $result->FetchRow()) {
                $data[$row['key']] = $row['label'];
            }
        }
        return $data;
    }


    public function autoselect2Action($db_model = '')
    {
        $q = $_POST['q'];
        $filter = [];
        isset($_POST['filter']) && ($filter = json_decode($_POST['filter']));
        $data = ['total_count' => 0, 'items' => [], 'incomplete_results' => true];
        if (strlen($q) > 0 && !empty($db_model)) {
            $cfe = $this->_dbmodel[$db_model];
            $module = ucfirst($cfe['module']);
            $model = ucfirst($cfe['model']);
            $label = $cfe['label'];
            $key = $cfe['key'];
            $filter = (isset($cfe['filter']) && is_array($cfe['filter']) ? et_array_marge($cfe['filter'], $filter) : '');
            $order = (isset($cfe['order']) ? $cfe['order'] : '');

            /*
             * ดึงข้อมูล
             */
            $class = "EThesis\\Models\\$module\\$model";
            $md_class = new $class();
            $label = str_replace('_ML', '_' . strtoupper($this->lang), $label);
            $order = str_replace('_ML', '_' . strtoupper($this->lang), $order);

            $filter['SQL'] = "{$label} LIKE '%{$q}%'";

            $field = [($key) . ' AS [key]', $label . ' AS [label]'];

            $result = $md_class->select_by_filter($field, $filter, $order, 20, 0);

            if ($result && $result->RecordCount() > 0) {
                $data['total_count'] = $result->RecordCount();
                while ($row = $result->FetchRow()) {
                    $data['items'][] = ['id' => $row['key'],'text' => $row['label']];
                }
            }

        }

        echo json_encode($data);
    }


}