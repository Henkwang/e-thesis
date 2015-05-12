<?php

namespace EThesis\Models\Bs;


class Bs1_process_history_model extends \EThesis\Library\Adodb
{

    var $schema = 'dbo';
    var $table = 'BS1_PROCESS_HISTORY';
    var $primary = 'BS1_HIS_ID';

    var $use_view = 'VW';

    var $field_insert = ['BS1_PROCESS_ID', 'BS1_HIS_ORDER', 'BS1_ID', 'BS1_HIS_STATUS', 'BS1_HIS_REMARK', 'BS1_HIS_DATE'];
    var $field_update = ['BS1_PROCESS_ID', 'BS1_HIS_ORDER', 'BS1_ID', 'BS1_HIS_STATUS', 'BS1_HIS_REMARK', 'BS1_HIS_DATE'];

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
        $sql = "RECORD_STATUS='N'";
        if (empty($filter)) {

        } else if (is_array($filter)) {

            $sql .= (isset($filter['BS1_PROCESS_NAME_TH']) ? " AND BS1_PROCESS_NAME_TH LIKE '%{$filter['BS1_PROCESS_NAME_TH']}%'" : '');
            $sql .= (isset($filter['BS1_PROCESS_NAME_EN']) ? " AND BS1_PROCESS_NAME_EN LIKE '%{$filter['BS1_PROCESS_NAME_EN']}%'" : '');


            $sql .= (isset($filter['BS1_PROCESS_ID']) ? " AND BS1_PROCESS_ID IN ({$filter['BS1_PROCESS_ID']})" : '');
            $sql .= (isset($filter['BS1_ID']) ? " AND BS1_ID IN ({$filter['BS1_ID']})" : '');

            $sql .= (isset($filter['BS1_HIS_STATUS']) ? " AND BS1_HIS_STATUS ='{$filter['BS1_HIS_STATUS']}' " : '');
            $sql .= (isset($filter['BS1_HIS_ORDER']) ? " AND BS1_HIS_ORDER ='{$filter['BS1_HIS_ORDER']}' " : '');


            $sql .= (isset($filter['IN_ID']) ? " AND {$this->primary} IN ({$filter['IN_ID']})" : '');
            $sql .= (isset($filter['NOT_IN_ID']) ? " AND {$this->primary} NOT IN ({$filter['IN_ID']})" : '');

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
        $sql .= ($order != FALSE ? "ORDER BY {$order}" : 'ORDER BY BS1_HIS_ORDER DESC ');
        $result = ($limit == FALSE ? $this->adodb->Execute($sql) : $this->adodb->SelectLimit($sql, $limit, $offset));

        return $result;
    }


    public function get_last_id()
    {
        $sql = "SELECT IDENT_CURRENT('{$this->schema}.{$this->table}')";
        return $this->adodb->GetOne($sql);
    }


    public function get_field_max_order_by_bs1(array $field = [], $bs1_pk_id)
    {
        $sql_field = (empty($field) ? '*' : implode(',', $field));
        $sql = "SELECT TOP 1 {$sql_field} ";
        $sql .= "FROM " . ($this->use_view !== FALSE ? "{$this->schema}.{$this->use_view}_{$this->table} " : "{$this->schema}.{$this->table} ");
        $sql .= "WHERE RECORD_STATUS='N' AND BS1_ID='$bs1_pk_id' ";
        $sql .= 'ORDER BY  BS1_HIS_ORDER DESC, BS1_PROCESS_ORDER DESC ';
        $sql .= ";";
        $result = $this->adodb->Execute($sql);
        return $result->FetchRow();
    }

    public function get_num_max_order_by_bs1($bs1_pk_id)
    {
        $sql = "SELECT MAX(BS1_HIS_ORDER) ";
        $sql .= "FROM " . ($this->use_view !== FALSE ? "{$this->schema}.{$this->use_view}_{$this->table} " : "{$this->schema}.{$this->table} ");
        $sql .= "WHERE RECORD_STATUS='N' AND BS1_ID='$bs1_pk_id' ";
        $sql .= ";";
        $num = $this->adodb->GetOne($sql);
        $num = (!empty($num) ? $num : 0);
        return $num;
    }


    public function insert(array $arrInsert)
    {
        $sql_field = '';
        $sql_value = '';
        foreach ($this->field_insert as $field) {
            if (isset($arrInsert[$field])) {
                $sql_field .= "{$field},";
                if(in_array($arrInsert[$field], ['GETDATE()'])){
                    $sql_value .= "" . str_replace("'", "''", $arrInsert[$field]) . ",";
                }else{
                    $sql_value .= "'" . str_replace("'", "''", $arrInsert[$field]) . "',";
                }

            }
        }
        $sql_field .= "RECORD_STATUS, CREATE_DATE, CREATE_USER, CREATE_USER_TYPE, LAST_DATE, LAST_USER, LAST_USER_TYPE";
        $sql_value .= "'N',{$this->date_current},'{$this->user_access}','{$this->user_type}',{$this->date_current},'{$this->user_access}','{$this->user_type}'";
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
        $sql .= "LAST_DATE={$this->date_current}, LAST_USER='{$this->user_access}', LAST_USER_TYPE='{$this->user_type}'";
        $sql .= "WHERE {$this->primary}='$id'";
        $sql .= ";";

        $result = $this->adodb->Execute($sql);
        return $result;
    }

    public function delete($id)
    {
        $sql = "UPDATE  {$this->schema}.{$this->table} SET RECORD_STATUS='D' ";
        $sql .= ",LAST_DATE={$this->date_current}, LAST_USER='{$this->user_access}', LAST_USER_TYPE='{$this->user_type}'";
        $sql .= "WHERE {$this->primary}='$id'";
        $sql .= ";";
        $result = $this->adodb->Execute($sql);
        return $result;
    }


} 