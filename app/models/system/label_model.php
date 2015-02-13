<?php

namespace EThesis\Models\System;


class Label_model extends \EThesis\Library\Adodb
{

    var $schema = 'system';
    var $table = 'SYS_LABEL';
    var $primary = '';

    var $use_view = FALSE;

    var $field_insert = ['LBL_NAME', 'LBL_GRID_TH', 'LBL_BTN_TH', 'LBL_FORM_TH', 'LBL_GRID_EN', 'LBL_BTN_EN', 'LBL_FORM_EN'];
    var $field_update = ['LBL_NAME', 'LBL_GRID_TH', 'LBL_BTN_TH', 'LBL_FORM_TH', 'LBL_GRID_EN', 'LBL_BTN_EN', 'LBL_FORM_EN'];

    var $date_current;
    var $user_access;
    var $user_group;
    var $user_type;


    public function initialize()
    {
        parent::__construct();
        //$this->adodb->debug = TRUE;

        $sess = new \EThesis\Library\Session();

        $this->date_current = $this->adodb->sysTimeStamp;
        $this->user_access = ($sess->has('username') ? $sess->get('username') : die('Session Time Out'));
        $this->user_group = ($sess->has('usergroup') ? $sess->get('usergroup') : die('Session Time Out'));
        $this->user_type = ($sess->has('usertype') ? $sess->get('usertype') : die('Session Time Out'));
    }

    private function check_filter(array $filter)
    {
        $sql = "RECORD_STATUS='N'";
        if (empty($filter)) {

        } else if (is_array($filter)) {
            $sql .= (isset($filter['LBL_NAME']) ? " AND LBL_NAME IN ({$filter['LBL_NAME']})" : '');
            $sql .= (isset($filter['LBL_GRID_TH']) ? " AND LBL_GRID_TH LIKE '%{$filter['LBL_GRID_TH']}%'" : '');
            $sql .= (isset($filter['LBL_BTN_TH']) ? " AND LBL_BTN_TH LIKE '%{$filter['LBL_BTN_TH']}%'" : '');
            $sql .= (isset($filter['LBL_FORM_TH']) ? " AND LBL_FORM_TH LIKE '%{$filter['LBL_FORM_TH']}%'" : '');
            $sql .= (isset($filter['LBL_GRID_EN']) ? " AND LBL_GRID_EN LIKE '%{$filter['LBL_GRID_EN']}%'" : '');
            $sql .= (isset($filter['LBL_BTN_EN']) ? " AND LBL_BTN_EN LIKE '%{$filter['LBL_BTN_EN']}%'" : '');
            $sql .= (isset($filter['LBL_FORM_EN']) ? " AND LBL_FORM_EN LIKE '%{$filter['LBL_FORM_EN']}%'" : '');

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


}