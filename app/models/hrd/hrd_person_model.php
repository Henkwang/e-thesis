<?php

namespace EThesis\Models\Hrd;


class Hrd_person_model extends \EThesis\Library\Adodb
{

    var $schema = 'hrd';
    var $table = 'HRD_HIS_PERSON';
    var $primary = 'PERSON_ID';

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
            $sql .= (isset($filter['ID_CARD']) ? " AND ID_CARD='{$filter['ID_CARD']}'" : '');
            $sql .= (isset($filter['CITIZEN_ID']) ? " AND CITIZEN_ID='{$filter['CITIZEN_ID']}'" : '');
            $sql .= (isset($filter['TITLE_ID']) ? " AND TITLE_ID='{$filter['TITLE_ID']}'" : '');
            $sql .= (isset($filter['FACULTY_ID']) ? " AND FACULTY_ID='{$filter['FACULTY_ID']}'" : '');
            $sql .= (isset($filter['REG_FACULTY_ID']) ? " AND REG_FACULTY_ID='{$filter['REG_FACULTY_ID']}'" : '');
            $sql .= (isset($filter['DIVISION_ID']) ? " AND DIVISION_ID='{$filter['DIVISION_ID']}'" : '');
            $sql .= (isset($filter['WORKLINE_ID']) ? " AND WORKLINE_ID='{$filter['WORKLINE_ID']}'" : '');
            $sql .= (isset($filter['STATUSLIST_ID']) ? " AND STATUSLIST_ID='{$filter['STATUSLIST_ID']}'" : '');
            $sql .= (isset($filter['GENDER_ID']) ? " AND GENDER_ID='{$filter['GENDER_ID']}'" : '');
            $sql .= (isset($filter['NAME_TH']) ? " AND NAME_TH LIKE'%{$filter['NAME_TH']}%'" : '');
            $sql .= (isset($filter['NAME_EN']) ? " AND NAME_EN LIKE'%{$filter['NAME_EN']}%'" : '');

            $sql .= (isset($filter['NOT_STATUSLIST_ID']) ? " AND STATUSLIST_ID NOT IN ({$filter['NOT_STATUSLIST_ID']})" : '');

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

    public function select_by_cid(array $field, $citizen_id)
    {
        $result = $this->select_by_filter($field, ['CITIZEN_ID' => $citizen_id]);
        $row = (is_object($result) ? $result->FetchRow() : false);
        return $row;
    }


}