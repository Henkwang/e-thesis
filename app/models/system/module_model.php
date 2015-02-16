<?php

namespace EThesis\Models\System;


class Module_model extends \EThesis\Library\Adodb
{

    var $schema = 'system';
    var $table = 'SYS_MODULE';
    var $primary = 'MOD_ID';

    var $use_view = FALSE;

    var $field_insert = ['MOD_PARENT_ID', 'MOD_NAME_TH', 'MOD_NAME_EN', 'MOD_LEVLE', 'MOD_ORDER', 'ENABLE', 'MOD_URL'];
    var $field_update = ['MOD_PARENT_ID', 'MOD_NAME_TH', 'MOD_NAME_EN', 'MOD_LEVLE', 'MOD_ORDER', 'ENABLE', 'MOD_URL'];

    var $date_current;
    var $user_access;
    var $user_group;
    var $user_type;


    public function initialize()
    {
        parent::__construct();

        $this->adodb->debug = TRUE;

        $sess = new \EThesis\Library\Session();

        $this->date_current = $this->adodb->sysTimeStamp;
        $this->user_access = ($sess->has('username') ? $sess->get('username') : die(AUTH_FALSE_J));
        $this->user_group = ($sess->has('usergroup') ? $sess->get('usergroup') : die(AUTH_FALSE_J));
        $this->user_type = ($sess->has('usertype') ? $sess->get('usertype') : die(AUTH_FALSE_J));
    }

    private function check_filter(array $filter)
    {
        $sql = "RECORD_STATUS='N'";
        if (empty($filter)) {

        } else if (is_array($filter)) {
            $sql .= (isset($filter['MOD_PARENT_ID']) ? " AND MOD_PARENT_ID IN ({$filter['MOD_PARENT_ID']})" : '');
            $sql .= (isset($filter['MOD_NAME_TH']) ? " AND MOD_NAME_TH LIKE '%{$filter['MOD_NAME_TH']}%'" : '');
            $sql .= (isset($filter['MOD_NAME_EN']) ? " AND MOD_NAME_EN LIKE '%{$filter['MOD_NAME_EN']}%'" : '');
            $sql .= (isset($filter['MOD_LEVEL']) ? " AND MOD_LEVEL = '{$filter['MOD_LEVEL']}'" : '');
            $sql .= (isset($filter['MOD_ORDER']) ? " AND MOD_ORDER = '{$filter['MOD_ORDER']}'" : '');
            $sql .= (isset($filter['ENABLE']) ? " AND ENABLE = '{$filter['ENABLE']}'" : '');
            $sql .= (isset($filter['MOD_URL']) ? " AND MOD_URL LIKE '%{$filter['MOD_URL']}%'" : '');

            $sql .= (isset($filter['IN_ID']) ? " AND {$this->primary} IN ({$filter['IN_ID']})" : '');
            $sql .= (isset($filter['NOT_IN_ID']) ? " AND {$this->primary} NOT IN ({$filter['IN_ID']})" : '');

            $sql .= (isset($filter['ID']) ? " AND ({$this->primary} IN ({$filter['ID']}) OR MOD_PARENT_ID IN ({$filter['ID']}))" : '');
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
        $sql_field .= "RECORD_STATUS, CREATE_DATE, CREATE_USER, CREATE_GROUP, LAST_DATE, LAST_USER, LAST_GROUP";
        $sql_value .= "'N','{$this->date_current}','{$this->user_access}','{$this->user_group}','{$this->date_current}','{$this->user_access}','{$this->user_group}'";
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
        $sql .= "LAST_DATE='{$this->date_current}', LAST_USER='{$this->user_access}', LAST_GROUP='{$this->user_group}'";
        $sql .= "WHERE {$this->primary}='$id'";
        $sql .= ";";

        $result = $this->adodb->Execute($sql);
        return $result;
    }

    public function delete($id){
        $arr = ['RECORD_STATUS'=> 'D'];
        $result = $this->update($arr, $id);
        return $result;
    }


}