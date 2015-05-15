<?php

namespace EThesis\Models\System;


class Sys_log_model extends \EThesis\Library\Adodb
{

    var $schema = 'system';
    var $table = 'SYS_LOG';
    var $primary = 'LOG_ID';

    var $use_view = FALSE;

    var $field_insert = ['LOG_USER', 'LOG_USER_TYPE', 'LOG_PAGE', 'LOG_PROCESS', 'LOG_VALUE', 'LOG_TYPE', 'LOG_DATE', 'LOG_IP', 'LOG_BROWSER_INFO', 'LOG_LOCAL_IP'];

    var $date_current;
    var $user_access;
    var $user_group;
    var $user_type;

    var $sess_class;

    var $LOG_TYPE = ['MSG_LOGIN' => 'A',
        'MSG_OPEN_PAGE' => 'A',
        'MSG_ADD_ERROR' => 'E',
        'MSG_DELETE_USED' => 'E',
        'MSG_DUPLICATE' => 'E',
        'MSG_IMPORT_DUPLICATE' => 'E',
        'MSG_IMPORT_ERROR' => 'E',
        'MSG_IMPORT_ITEM_ERROR' => 'E',
        'MSG_UPDATE_ERROR' => 'E',
        'MSG_ADD_COMPLETE' => 'N',
        'MSG_CANCEL_COMPLETE' => 'N',
        'MSG_CHECK_ERROR_REGIS' => 'N',
        'MSG_DELETE_COMPLETE' => 'N',
        'MSG_IMPORT_COMPLETE' => 'N',
        'MSG_UPDATE_COMPLETE' => 'N'];


    public function __construct()
    {
        parent::__construct();

        $this->adodb->debug = false;

        $sess = new \EThesis\Library\Session();

        $this->lang = strtoupper($sess->get('lang'));

        $this->sess_class = $sess;

        $this->date_current = $this->adodb->sysTimeStamp;
    }

    private function check_filter(array $filter)
    {
        $sql = "LOG_ID IS NOT NULL ";
        if (empty($filter)) {

        } else if (is_array($filter)) {
            $sql .= (!empty($filter['LOG_USER']) ? " AND LOG_USER LIKE '%{$filter['LOG_USER']}%'" : '');
            $sql .= (!empty($filter['LOG_USER_TYPE']) ? " AND LOG_USER_TYPE = '{$filter['LOG_USER_TYPE']}'" : '');
            $sql .= (!empty($filter['LOG_PAGE']) ? " AND LOG_PAGE LIKE '%{$filter['LOG_PAGE']}%'" : '');
            $sql .= (!empty($filter['LOG_PROCESS']) ? " AND LOG_PROCESS LIKE '%{$filter['LOG_PROCESS']}%'" : '');
            $sql .= (!empty($filter['LOG_BROWSER_INFO']) ? " AND LOG_BROWSER_INFO LIKE '%{$filter['LOG_BROWSER_INFO']}%'" : '');
            $sql .= (!empty($filter['LOG_DATE']) ? " AND CONVERT(DATE, LOG_DATE)=CONVERT(DATE, '{$filter['LOG_DATE']}')" : '');

            $sql .= (!empty($filter['IN_ID']) ? " AND {$this->primary} IN ({$filter['IN_ID']})" : '');
            $sql .= (!empty($filter['NOT_IN_ID']) ? " AND {$this->primary} NOT IN ({$filter['IN_ID']})" : '');

            $sql .= (isset($filter['SQL']) ? " AND {$filter['SQL']}" : '');
            $sql .= (isset($filter['AUTO']) ? " AND {$filter['AUTO']}" : '');

        }
        return $sql;
    }

    public function count_by_filter(array $filters = array())
    {
        $sql = 'SELECT COUNT(*) ';
        $sql .= "FROM " . ($this->use_view !== FALSE ? "{$this->schema}.{$this->use_view}_{$this->table}" : "{$this->schema}.{$this->table}");
        $sql .= " WHERE " . $this->check_filter($filters);
        $num = $this->adodb->GetOne($sql);
        return $num;
    }

    public function select_by_filter(array $field = array(), array $filters = array(), $order = FALSE, $limit = FALSE, $offset = FALSE)
    {
        $sql_field = (empty($field) ? '*' : implode(',', $field));
        $sql = "SELECT  {$sql_field} ";
        $sql .= "FROM " . ($this->use_view !== FALSE ? "{$this->schema}.{$this->use_view}_{$this->table}" : "{$this->schema}.{$this->table}");
        $sql .= " WHERE " . $this->check_filter($filters);
        $sql .= ($order != FALSE ? "ORDER BY {$order}" : '');
//        die($sql);
        $result = ($limit == FALSE ? $this->adodb->Execute($sql) : $this->adodb->SelectLimit($sql, $limit, $offset));

        return $result;
    }

    public function set($log_process)
    {
        $arrInsert = [
            'LOG_USER' => $this->sess_class->get('userid'),
            'LOG_USER_TYPE' => $this->user_type,
            'LOG_PAGE' => __URI_CALL__,
            'LOG_PROCESS' => $log_process,
            'LOG_VALUE' => json_encode($this->sess_class->get()),
            'LOG_TYPE' => $this->LOG_TYPE[$log_process],
            'LOG_IP' => $this->sess_class->get('user_ip'),
            'LOG_BROWSER_INFO' => $this->sess_class->get('user_agent'),
            'LOG_LOCAL_IP' => $_SERVER['SERVER_ADDR']];
        $sql_field = '';
        $sql_value = [];
        foreach ($this->field_insert as $field) {
            if (isset($arrInsert[$field])) {
                $sql_field [] = "{$field}";
                $sql_value [] = "'{$arrInsert[$field]}'";
            }
        }
        $sql_field [] = 'LOG_DATE';
        $sql_value [] = $this->date_current;
        $sql = "INSERT INTO {$this->schema}.{$this->table} (" . implode(',', $sql_field) . ") VALUES (" . implode(',', $sql_value) . ")";
        $sql .= ";";
//        die($sql);
        $result = $this->adodb->Execute($sql);
        return $result;

    }


}