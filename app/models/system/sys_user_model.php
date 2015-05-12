<?php

namespace EThesis\Models\System;


class Sys_user_model extends \EThesis\Library\Adodb
{

    var $schema = 'system';
    var $table = 'SYS_USER';
    var $primary = 'USR_ID';

    var $use_view = FALSE;

    var $field_insert = ['USR_CODE', 'USR_DISPLAY', 'USR_USERNAME', 'USR_PASSWORD', 'USR_EMAIL', 'USR_TEL', 'USR_TYPE', 'USER_LANGUAGE', 'GROUP_ID', 'CAMPUS_ID', 'FACULTY_ID', 'DEGREE_ID', 'DEPARTMENT_ID'];
    var $field_update = ['USR_CODE', 'USR_DISPLAY', 'USR_USERNAME', 'USR_PASSWORD', 'USR_EMAIL', 'USR_TEL', 'USR_TYPE', 'USER_LANGUAGE', 'GROUP_ID', 'CAMPUS_ID', 'FACULTY_ID', 'DEGREE_ID', 'DEPARTMENT_ID'];

    var $date_current;
    var $user_access;
    var $user_group;
    var $user_type;


    public function __construct()
    {
        parent::__construct();

        $this->adodb->debug = TRUE;

        $sess = \EThesis\Library\DIPhalcon::get('sess');

        $this->date_current = $this->adodb->sysTimeStamp;
    }

    private function check_filter(array $filter)
    {
        $sql = "RECORD_STATUS='N'";
        if (empty($filter)) {

        } else if (is_array($filter)) {
            $sql .= (!empty($filter['USR_CODE']) ? " AND USR_CODE LIKE '%{$filter['USR_CODE']}%'" : '');
            $sql .= (!empty($filter['USR_USERNAME']) ? " AND USR_USERNAME LIKE '%{$filter['USR_USERNAME']}%'" : '');
            $sql .= (!empty($filter['USR_PASSWORD']) ? " AND USR_PASSWORD='{$filter['USR_PASSWORD']}'" : '');

            $sql .= (!empty($filter['USR_EMAIL']) ? " AND USR_EMAIL LIKE '%{$filter['USR_EMAIL']}%'" : '');
            $sql .= (!empty($filter['USR_TEL']) ? " AND USR_TEL LIKE '%{$filter['USR_TEL']}%'" : '');

            $sql .= (!empty($filter['USR_TYPE']) ? " AND USR_TYPE = '{$filter['USR_TYPE']}'" : '');
            $sql .= (!empty($filter['USER_LANGUAGE']) ? " AND USER_LANGUAGE = '{$filter['USER_LANGUAGE']}'" : '');

            $sql .= (!empty($filter['IN_ID']) ? " AND {$this->primary} IN ({$filter['IN_ID']})" : '');
            $sql .= (!empty($filter['NOT_IN_ID']) ? " AND {$this->primary} NOT IN ({$filter['IN_ID']})" : '');

            $sql .= (!empty($filter['SQL']) ? " AND {$filter['SQL']}" : '');
            $sql .= (!empty($filter['AUTO']) ? " AND {$filter['AUTO']}" : '');

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

    public function select_by_username(array $filed, $username)
    {
        $result = $this->select_by_filter($filed, ['USR_USERNAME' => $username]);
        $row = (is_object($result) ? $result->FetchRow() : []);
        return $row;
    }

    public function insert(array $arrInsert)
    {
        $sql_field = '';
        $sql_value = '';
        foreach ($this->field_insert as $field) {
            if (isset($arrInsert[$field])) {
                $sql_field .= "{$field},";
                $sql_value .= "'{$arrInsert[$field]}',";
            }
        }
        $sql_field .= "RECORD_STATUS, CREATE_DATE, CREATE_USER, CREATE_USER_TYPE, LAST_DATE, LAST_USER, LAST_USER_TYPE";
        $sql_value .= "'N','{$this->date_current}','{$this->user_access}','{$this->user_type}','{$this->date_current}','{$this->user_access}','{$this->user_type}'";
        $sql = "INSERT INTO {$this->schema}.{$this->table} ({$sql_field}) VALUES ({$sql_value})";
        $sql .= ";";
        $result = $this->adodb->Execute($sql);
        return $result;

    }

    public function update(array $arrUpdate, $id)
    {
        $sql = "UPDATE {$this->schema}.{$this->table} SET ";
        foreach ($this->field_update as $field) {
            if (isset($arrUpdate[$field])) {
                $sql .= "{$field}='{$arrUpdate[$field]}',";
            } else {
                $sql .= "{$field}='',";
            }
        }
        $sql .= "LAST_DATE='{$this->date_current}', LAST_USER='{$this->user_access}', LAST_USER_TYPE='{$this->user_type}'";
        $sql .= "WHERE {$this->primary}='$id'";
        $sql .= ";";

        $result = $this->adodb->Execute($sql);
        return $result;
    }

    public function delete($id)
    {
        $sql = "UPDATE  {$this->schema}.{$this->table} SET RECORD_STATUS='D' ";
        $sql .= ",LAST_DATE={$this->date_current}, LAST_USER='{$this->user_access}', LAST_USER_TYPE='{$this->user_type}'";
        $sql .= " WHERE {$this->primary}='$id'";
        $result = $this->adodb->Execute($sql);
        return $result;
    }


}