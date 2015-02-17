<?php

namespace EThesis\Models\Upreg;


class Faculty_model extends \EThesis\Library\Adodb
{

    var $schema = 'upreg';
    var $table = 'MAS_FACULTY';
    var $primary = 'FACULTY_ID';

    var $use_view = 'VW';

    var $date_current;
    var $user_access;
    var $user_group;
    var $user_type;


    public function initialize()
    {
        parent::__construct();
//        $this->adodb->debug = TRUE;
    }

    private function check_filter(array $filter)
    {
        $sql = "RECORD_STATUS='N'";
        if (empty($filter)) {

        } else if (is_array($filter)) {
            $sql .= (isset($filter['FACULTY_CODE']) ? " AND FACULTY_CODE='{$filter['FACULTY_CODE']}'" : '');
            $sql .= (isset($filter['FACULTY_TYPE']) ? " AND FACULTY_TYPE='{$filter['FACULTY_TYPE']}'" : '');
            $sql .= (isset($filter['FACULTY_GROUP']) ? " AND FACULTY_GROUP='{$filter['FACULTY_GROUP']}'" : '');
            $sql .= (isset($filter['CAMPUS_ID']) ? " AND CAMPUS_ID='{$filter['CAMPUS_ID']}'" : '');
            $sql .= (isset($filter['FACULTY_NAME_TH']) ? " AND FACULTY_NAME_TH LIKE'%{$filter['FACULTY_NAME_TH']}%'" : '');
            $sql .= (isset($filter['FACULTY_NAME_EN']) ? " AND FACULTY_NAME_EN='{$filter['FACULTY_NAME_EN']}'" : '');
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