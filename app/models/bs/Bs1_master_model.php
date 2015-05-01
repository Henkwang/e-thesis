<?php

namespace EThesis\Models\Bs;


class Bs1_master_model extends \EThesis\Library\Adodb
{

    var $schema = 'dbo';
    var $table = 'BS1_MASTER';
    var $primary = 'BS1_ID';

    var $use_view = 'VW';

    var $field_insert = ['ACAD_YEAR', 'ASEAN_STATUS', 'ADVISER_STATUS', 'FACULTY_ID', 'PROGRAM_ID', 'PERSON_ID', 'CITIZEN_ID', 'TITLE_ID', 'FNAME_TH', 'LNAME_TH', 'POS_EXECUTIVE_ID', 'POS_ACADEMIC_ID', 'HRD_FACULTY_ID', 'UNIVERSITY_NAME', 'TEL_PERSONNEL', 'TEL_WORK', 'TEL_WORK_NEXT', 'MAX_DEGREE_ID', 'MAX_GRADUATE_DATE', 'MAX_COURSE_NAME_TH', 'MAX_COURSE_NAME_EN', 'MAX_PROGRAM_NAME_TH', 'MAX_PROGRAM_NAME_EN', 'MAX_UNIVERSITY_NAME_TH', 'MAX_UNIVERSITY_NAME_EN', 'MAX_COUNTRY_NAME_TH', 'MAX_COUNTRY_NAME_EN', 'BS1_EXPERIENCE', 'PRESENT_ACADEMIC_ID', 'PRESENT_ACADEMIC_FOR_GRADUATE', 'PRESENT_ACADEMIC_TYPE', 'PRESENT_ACADEMIC_YEAR', 'PRESENT_ACADEMIC_TYPE_NAME', 'PRESENT_ACADEMIC_PLACE_NAME', 'PRESENT_ACADEMIC_PROVINCE_NAME', 'PRESENT_ACADEMIC_COUNTRY_NAME', 'PRESENT_ACADEMIC_TITLE_ID', 'PRESENT_ACADEMIC_FNAME_TH', 'PRESENT_ACADEMIC_LNAME_TH', 'PRESENT_ACADEMIC_NAME', 'PRESENT_ACADEMIC_IS_EXPERIENCE', 'PRESENT_ACADEMIC_IS_EXPERIENCE_YEAR', 'MORE_RESEARCH_STATUS', 'AWARD_NAME_STATUS', 'SIGN_ID', 'CK_POSITION_ID', 'CK_START_DATE', 'CK_END_DATE', 'POP_INS_ID', 'POP_HEAD_THESIS_ID', 'POP_COM_THESIS_ID', 'POP_INS_IS_ID', 'PERSON_IMAGE', 'PERSON_CONTRACT_FILE', 'ADVISER_TYPE_ID'];
    var $field_update = ['ACAD_YEAR', 'ASEAN_STATUS', 'ADVISER_STATUS', 'FACULTY_ID', 'PROGRAM_ID', 'PERSON_ID', 'CITIZEN_ID', 'TITLE_ID', 'FNAME_TH', 'LNAME_TH', 'POS_EXECUTIVE_ID', 'POS_ACADEMIC_ID', 'HRD_FACULTY_ID', 'UNIVERSITY_NAME', 'TEL_PERSONNEL', 'TEL_WORK', 'TEL_WORK_NEXT', 'MAX_DEGREE_ID', 'MAX_GRADUATE_DATE', 'MAX_COURSE_NAME_TH', 'MAX_COURSE_NAME_EN', 'MAX_PROGRAM_NAME_TH', 'MAX_PROGRAM_NAME_EN', 'MAX_UNIVERSITY_NAME_TH', 'MAX_UNIVERSITY_NAME_EN', 'MAX_COUNTRY_NAME_TH', 'MAX_COUNTRY_NAME_EN', 'BS1_EXPERIENCE', 'PRESENT_ACADEMIC_ID', 'PRESENT_ACADEMIC_FOR_GRADUATE', 'PRESENT_ACADEMIC_TYPE', 'PRESENT_ACADEMIC_YEAR', 'PRESENT_ACADEMIC_TYPE_NAME', 'PRESENT_ACADEMIC_PLACE_NAME', 'PRESENT_ACADEMIC_PROVINCE_NAME', 'PRESENT_ACADEMIC_COUNTRY_NAME', 'PRESENT_ACADEMIC_TITLE_ID', 'PRESENT_ACADEMIC_FNAME_TH', 'PRESENT_ACADEMIC_LNAME_TH', 'PRESENT_ACADEMIC_NAME', 'PRESENT_ACADEMIC_IS_EXPERIENCE', 'PRESENT_ACADEMIC_IS_EXPERIENCE_YEAR', 'MORE_RESEARCH_STATUS', 'AWARD_NAME_STATUS', 'SIGN_ID', 'CK_POSITION_ID', 'CK_START_DATE', 'CK_END_DATE', 'POP_INS_ID', 'POP_HEAD_THESIS_ID', 'POP_COM_THESIS_ID', 'POP_INS_IS_ID', 'PERSON_IMAGE', 'PERSON_CONTRACT_FILE', 'ADVISER_TYPE_ID'];

    var $select_and_field = ['ACAD_YEAR', 'ASEAN_STATUS', 'ADVISER_STATUS', 'FACULTY_ID', 'PROGRAM_ID', 'PERSON_ID', 'CITIZEN_ID', 'TITLE_ID', 'FNAME_TH', 'LNAME_TH', 'POS_EXECUTIVE_ID', 'POS_ACADEMIC_ID', 'HRD_FACULTY_ID', 'UNIVERSITY_NAME', 'TEL_PERSONNEL', 'TEL_WORK', 'TEL_WORK_NEXT', 'MAX_DEGREE_ID', 'MAX_GRADUATE_DATE', 'MAX_COURSE_NAME_TH', 'MAX_COURSE_NAME_EN', 'MAX_PROGRAM_NAME_TH', 'MAX_PROGRAM_NAME_EN', 'MAX_UNIVERSITY_NAME_TH', 'MAX_UNIVERSITY_NAME_EN', 'MAX_COUNTRY_NAME_TH', 'MAX_COUNTRY_NAME_EN', 'BS1_EXPERIENCE', 'PRESENT_ACADEMIC_ID', 'PRESENT_ACADEMIC_FOR_GRADUATE', 'PRESENT_ACADEMIC_TYPE', 'PRESENT_ACADEMIC_YEAR', 'PRESENT_ACADEMIC_TYPE_NAME', 'PRESENT_ACADEMIC_PLACE_NAME', 'PRESENT_ACADEMIC_PROVINCE_NAME', 'PRESENT_ACADEMIC_COUNTRY_NAME', 'PRESENT_ACADEMIC_TITLE_ID', 'PRESENT_ACADEMIC_FNAME_TH', 'PRESENT_ACADEMIC_LNAME_TH', 'PRESENT_ACADEMIC_NAME', 'PRESENT_ACADEMIC_IS_EXPERIENCE', 'PRESENT_ACADEMIC_IS_EXPERIENCE_YEAR', 'MORE_RESEARCH_STATUS', 'AWARD_NAME_STATUS', 'SIGN_ID', 'CK_POSITION_ID', 'CK_START_DATE', 'CK_END_DATE', 'POP_INS_ID', 'POP_HEAD_THESIS_ID', 'POP_COM_THESIS_ID', 'POP_INS_IS_ID', 'PERSON_IMAGE', 'PERSON_CONTRACT_FILE', 'ADVISER_TYPE_ID'];

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
        $sql .= "LAST_DATE={$this->date_current}, LAST_USER='{$this->user_access}', LAST_USER_TYPE='{$this->user_type}'";
        $sql .= "WHERE {$this->primary}='$id'";
        $sql .= ";";
        $result = $this->adodb->Execute($sql);
        return $result;
    }


}