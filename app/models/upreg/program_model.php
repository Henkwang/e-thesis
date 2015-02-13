<?php

namespace EThesis\Models\Upreg;


class Program_model extends \EThesis\Library\Adodb
{

    var $schema = 'upreg';
    var $table = 'MAS_PROGRAM';
    var $primary = 'PROGRAM_ID';

    var $use_view = 'VW';

    var $date_current;
    var $user_access;
    var $user_group;
    var $user_type;


    public function initialize()
    {
        parent::__construct();
        //$this->adodb->debug = TRUE;
    }

    private function check_filter(array $filter)
    {
        $sql = "RECORD_STATUS='N'";
        if (empty($filter)) {

        } else if (is_array($filter)) {
            $sql .= (isset($filter['FACULTY_ID']) ? " AND FACULTY_ID IN ({$filter['FACULTY_ID']})" : '');
            $sql .= (isset($filter['PROGRAM_CODE']) ? " AND PROGRAM_CODE IN ({$filter['PROGRAM_CODE']})" : '');
            $sql .= (isset($filter['PROGRAM_NAME_TH']) ? " AND PROGRAM_NAME_TH LIKE '%{$filter['PROGRAM_NAME_TH']}%'" : '');
            $sql .= (isset($filter['PROGRAM_NAME_EN']) ? " AND PROGRAM_NAME_EN LIKE '%{$filter['PROGRAM_NAME_EN']}%'" : '');

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
        echo $sql .= ($order != FALSE ? "ORDER BY {$order}" : '');
        $result = ($limit == FALSE ? $this->adodb->Execute($sql) : $this->adodb->SelectLimit($sql, $limit, $offset));

        return $result;
    }




} 