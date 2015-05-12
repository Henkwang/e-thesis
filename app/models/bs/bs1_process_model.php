<?php

namespace EThesis\Models\Bs;


class Bs1_process_model extends \EThesis\Library\Adodb
{

    var $schema = 'dbo';
    var $table = 'BS1_PROCESS';
    var $primary = 'BS1_PROCESS_ID';

    var $use_view = false;

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
        $sql .= ($order != FALSE ? "ORDER BY {$order}" : '');
        $result = ($limit == FALSE ? $this->adodb->Execute($sql) : $this->adodb->SelectLimit($sql, $limit, $offset));

        return $result;
    }




} 