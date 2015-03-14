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


    public function initialize()
    {
        parent::__construct();

        $this->adodb->debug = TRUE;

        $this->sess_class = \EThesis\Library\DIPhalcon::get('sess');

        $this->date_current = $this->adodb->sysTimeStamp;
        $this->user_access = ($this->sess_class->has('username') ? $this->sess_class->get('username') : die(AUTH_FALSE_J));
        $this->user_group = ($this->sess_class->has('usergroup') ? $this->sess_class->get('usergroup') : die(AUTH_FALSE_J));
        $this->user_type = ($this->sess_class->has('usertype') ? $this->sess_class->get('usertype') : die(AUTH_FALSE_J));
    }

    private function check_filter(array $filter)
    {
        $sql = "RECORD_STATUS='N'";
        if (empty($filter)) {

        } else if (is_array($filter)) {
            $sql .= (isset($filter['LOG_USER']) ? " AND LOG_USER LIKE '%{$filter['LOG_USER']}%'" : '');
            $sql .= (isset($filter['LOG_USER_TYPE']) ? " AND LOG_USER_TYPE = '{$filter['LOG_USER_TYPE']}'" : '');
            $sql .= (isset($filter['LOG_PAGE']) ? " AND LOG_PAGE LIKE '%{$filter['LOG_PAGE']}%'" : '');
            $sql .= (isset($filter['LOG_PROCESS']) ? " AND LOG_PROCESS LIKE '%{$filter['LOG_PROCESS']}%'" : '');
            $sql .= (isset($filter['LOG_BROWSER_INFO']) ? " AND LOG_BROWSER_INFO LIKE '%{$filter['LOG_BROWSER_INFO']}%'" : '');

            $sql .= (isset($filter['IN_ID']) ? " AND {$this->primary} IN ({$filter['IN_ID']})" : '');
            $sql .= (isset($filter['NOT_IN_ID']) ? " AND {$this->primary} NOT IN ({$filter['IN_ID']})" : '');

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
        $result = ($limit == FALSE ? $this->adodb->Execute($sql) : $this->adodb->SelectLimit($sql, $limit, $offset));

        return $result;
    }

    public function insert($log_page, $log_process, $log_type)
    {
        $arrInsert = [
            'LOG_USER' => $this->sess_class->get('username'),
            'LOG_USER_TYPE' => $this->user_type,
            'LOG_PAGE' => $log_page,
            'LOG_PROCESS' => $log_process,
            'LOG_VALUE' => json_encode($this->sess_class->get()),
            'LOG_TYPE' => $log_type,
            'LOG_DATE' => $this->date_current,
            'LOG_IP' => $this->sess_class->get('user_ip'),
            'LOG_BROWSER_INFO' => $this->sess_class->get('user_agent'),
            'LOG_LOCAL_IP' => $_SERVER['SERVER_ADDR'] ];
        $sql_field = '';
        $sql_value = '';
        foreach ($this->field_insert as $field) {
            if (isset($arrInsert[$field])) {
                $sql_field .= "{$field},";
                $sql_value .= "'{$arrInsert[$field]}',";
            }
        }
        $sql = "INSERT INTO {$this->schema}.{$this->table} ({$sql_field}) VALUES ({$sql_value})";
        $sql .= ";";
        $result = $this->adodb->Execute($sql);
        return $result;

    }


}