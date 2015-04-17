<?php

namespace EThesis\Models\Bs;


class Bs1_research_model extends \EThesis\Library\Adodb
{

    var $schema = 'dbo';
    var $table = 'BS1_RESEARCH';
    var $primary = 'BS1_RESEARCH_ID';

    var $use_view = FALSE;

    var $field_insert = ['BS1_ID','BS1_RESEARCH_NAME_TH','BS1_RESEARCH_NAME_EN'];
    var $field_update = ['BS1_ID','BS1_RESEARCH_NAME_TH','BS1_RESEARCH_NAME_EN'];

    var $select_and_field = ['BS1_ID','BS1_RESEARCH_NAME_TH','BS1_RESEARCH_NAME_EN'];

    var $date_current;
    var $user_access;
    var $user_group;
    var $user_type;


    public function __construct()
    {
        parent::__construct();

//        $this->adodb->debug = TRUE;

        $sess = new \EThesis\Library\Session();

        $this->date_current = $this->adodb->sysTimeStamp;
        $this->user_access = ($sess->has('username') ? $sess->get('username') : die(AUTH_FALSE_J));
        $this->user_group = ($sess->has('usergroup') ? $sess->get('usergroup') : die(AUTH_FALSE_J));
        $this->user_type = ($sess->has('usertype') ? $sess->get('usertype') : die(AUTH_FALSE_J));
    }

    private function check_filter(array $filter)
    {
        $sql = "RECORD_STATUS ='N'";
        if (empty($filter)) {

        } else if (is_array($filter)) {
            foreach ($this->select_and_field as $val) {
                if (!empty($filter[$val])) {
                    $sql .= " AND {$val}='{$filter[$val]}'";
                }
            }
            $sql .= (isset($filter['IN_ID']) ? " AND {$this->primary} IN ({$filter['IN_ID']})" : '');
            $sql .= (isset($filter['NOT_IN_ID']) ? " AND {$this->primary} NOT IN ({$filter['IN_ID']})" : '');

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
        $result = ($limit == FALSE ? $this->adodb->Execute($sql) : $this->adodb->SelectLimit($sql, $limit, $offset));

        return $result;
    }

    public function get_last_id()
    {
        $sql = "SELECT IDENT_CURRENT('{$this->schema}.{$this->table}')";
        return $this->adodb->GetOne($sql);
    }


    public function insert(array $arrInsert)
    {
        $sql_field = '';
        $sql_value = '';
        foreach ($this->field_insert as $field) {
            if (isset($arrInsert[$field])) {
                $sql_field .= "{$field},";
                $sql_value .= "'" . str_replace("'", "''", $arrInsert[$field]) . "',";
            }
        }
        $sql_field .= "RECORD_STATUS, LAST_DATE";
        $sql_value .= "'N',{$this->date_current}";
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
                $sql .= "{$field}='" . str_replace("'", "''", $arrUpdate[$field]) . "',";
            } else {
                $sql .= "{$field}='',";
            }
        }
        $sql .= "LAST_DATE={$this->date_current}";
        $sql .= "WHERE {$this->primary}='$id'";
        $sql .= ";";

        $result = $this->adodb->Execute($sql);
        return $result;
    }

    public function delete($id)
    {
        $sql = "UPDATE  {$this->schema}.{$this->table} SET RECORD_STATUS='D' ";
        $sql .= "LAST_DATE={$this->date_current}";
        $result = $this->adodb->Execute($sql);
        return $result;
    }


}