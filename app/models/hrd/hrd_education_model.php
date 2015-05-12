<?php

namespace EThesis\Models\Hrd;


class Hrd_education_model  extends \EThesis\Library\Adodb
{

    var $schema = 'hrd';
    var $table = 'HRD_HIS_EDUCATION';
    var $primary = 'EDU_ID';

    var $use_view = 'VW';

    var $date_current;
    var $user_access;
    var $user_group;
    var $user_type;


    public function __construct()
    {
        parent::__construct();
//        $this->adodb->debug = TRUE;
    }

    private function check_filter(array $filter)
    {
        $sql = "RECORD_STATUS='N'";
        if (empty($filter)) {

        } else if (is_array($filter)) {
            $sql .= (isset($filter['PERSON_ID']) ? " AND PERSON_ID='{$filter['PERSON_ID']}'" : '');
            $sql .= (isset($filter['LEV_ID']) ? " AND LEV_ID='{$filter['LEV_ID']}'" : '');
            $sql .= (isset($filter['PROG_ID']) ? " AND PROG_ID='{$filter['PROG_ID']}'" : '');
            $sql .= (isset($filter['EDUC_ID']) ? " AND EDUC_ID='{$filter['EDUC_ID']}'" : '');
            $sql .= (isset($filter['HIGHEST_DEGREE']) ? " AND HIGHEST_DEGREE='{$filter['HIGHEST_DEGREE']}'" : '');
            $sql .= (isset($filter['PLACEMENT_EDU']) ? " AND PLACEMENT_EDU='{$filter['PLACEMENT_EDU']}'" : '');


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