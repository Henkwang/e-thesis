<?php

namespace EThesis\Models\Reg;


class Reg_std_info_model extends \EThesis\Library\Adodb
{

    var $schema = 'upreg';
    var $table = 'STD_INFO';
    var $primary = 'STD_INFO_ID';

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

            $sql .= ($filter['ADMIT_YEAR'] == "") ? "" : "AND (ADMIT_YEAR ='{$filter['ADMIT_YEAR']}') ";
            $sql .= ($filter['ADMIT_SEMESTER'] == "") ? "" : "AND (ADMIT_SEMESTER ='{$filter['ADMIT_SEMESTER']}') ";
            $sql .= ($filter['CITIZEN_ID'] == "") ? "" : "AND (CITIZEN_ID ='{$filter['CITIZEN_ID']}') ";
            $sql .= ($filter['STUDENT_CODE'] == "") ? "" : "AND (STUDENT_CODE ='{$filter['STUDENT_CODE']}') ";
            $sql .= ($filter['TITLE_ID'] == "") ? "" : "AND (TITLE_ID ='{$filter['TITLE_ID']}') ";
            $sql .= ($filter['STD_FNAME_TH'] == "") ? "" : "AND (STD_FNAME_TH LIKE '%{$filter['STD_FNAME_TH']}%') ";
            $sql .= ($filter['STD_FNAME_EN'] == "") ? "" : "AND (STD_FNAME_EN LIKE '%{$filter['STD_FNAME_EN']}%') ";
            $sql .= ($filter['STD_LNAME_TH'] == "") ? "" : "AND (STD_LNAME_TH LIKE '%{$filter['STD_LNAME_TH']}%') ";
            $sql .= ($filter['STD_LNAME_EN'] == "") ? "" : "AND (STD_LNAME_EN ='{$filter['STD_LNAME_EN']}') ";
            $sql .= ($filter['FACULTY_ID'] == "") ? "" : "AND (FACULTY_ID ='{$filter['FACULTY_ID']}') ";
            $sql .= ($filter['DEGREE_ID'] == "") ? "" : "AND (DEGREE_ID ='{$filter['DEGREE_ID']}') ";
            $sql .= ($filter['CAMPUS_ID'] == "") ? "" : "AND (CAMPUS_ID ='{$filter['CAMPUS_ID']}') ";
            $sql .= ($filter['SECTION_ID'] == "") ? "" : "AND (SECTION_ID ='{$filter['SECTION_ID']}') ";
            $sql .= ($filter['COURSE_ID'] == "") ? "" : "AND (COURSE_ID ='{$filter['COURSE_ID']}') ";
            $sql .= ($filter['GENDER_ID'] == "") ? "" : "AND (GENDER_ID ='{$filter['GENDER_ID']}') ";
            $sql .= ($filter['WEANG_ID'] == "") ? "" : "AND (WEANG_ID ='{$filter['WEANG_ID']}') ";
            $sql .= ($filter['REGULATION_FEE_ID'] == "") ? "" : "AND (REGULATION_FEE_ID ='{$filter['REGULATION_FEE_ID']}') ";
            $sql .= ($filter['CREG_CR'] == "") ? "" : "AND (CREG_CR ='{$filter['CREG_CR']}') ";
            $sql .= ($filter['CEARN_CR'] == "") ? "" : "AND (CEARN_CR ='{$filter['CEARN_CR']}') ";
            $sql .= ($filter['CCHK_CR'] == "") ? "" : "AND (CCHK_CR ='{$filter['CCHK_CR']}') ";
            $sql .= ($filter['CCOMP_CR'] == "") ? "" : "AND (CCOMP_CR ='{$filter['CCOMP_CR']}') ";
            $sql .= ($filter['GPA'] == "") ? "" : "AND (GPA ='{$filter['GPA']}') ";
            $sql .= ($filter['LESS_GPA'] == "") ? "" : "AND (GPA < '{$filter['LESS_GPA']}') ";
            $sql .= ($filter['MORE_GPA'] == "") ? "" : "AND (GPA > '{$filter['MORE_GPA']}') ";
            $sql .= ($filter['ADMIT_DATE'] == "") ? "" : "AND (ADMIT_DATE ='{$filter['ADMIT_DATE']}') ";
            $sql .= ($filter['GRADUATE_DATE'] == "") ? "" : "AND (GRADUATE_DATE ='{$filter['GRADUATE_DATE']}') ";
            $sql .= ($filter['STUDENTGROUP'] == "") ? "" : "AND (STUDENTGROUP ='{$filter['STUDENTGROUP']}') ";
            $sql .= ($filter['EMAIL'] == "") ? "" : "AND (EMAIL ='{$filter['EMAIL']}') ";
            $sql .= ($filter['CLASS'] == "") ? "" : "AND (CLASS ='{$filter['CLASS']}') ";
            $sql .= ($filter['FINANCE_STATUS'] == "") ? "" : "AND (FINANCE_STATUS ='{$filter['FINANCE_STATUS']}') ";
            $sql .= ($filter['LIBRALY_CARD_CODE'] == "") ? "" : "AND (LIBRALY_CARD_CODE ='{$filter['LIBRALY_CARD_CODE']}') ";
            $sql .= ($filter['PROBATION_STATUS'] == "") ? "" : "AND (PROBATION_STATUS ='{$filter['PROBATION_STATUS']}') ";
            $sql .= ($filter['STD_STATUS_ID'] == "") ? "" : "AND (STD_STATUS_ID IN ({$filter['STD_STATUS_ID']})) ";
            $sql .= ($filter['STD_GROUP_ID'] == "") ? "" : "AND (STD_GROUP_ID ='{$filter['STD_GROUP_ID']}') ";
            $sql .= ($filter['NOT_IN_ID'] == "") ? "" : "AND (STD_INFO_ID NOT IN ({$filter['NOT_IN_ID']})) ";
            $sql .= ($filter['IN_ID'] == "") ? "" : "AND (STD_INFO_ID  IN ({$filter['IN_ID']})) ";
            $sql .= ($filter['PROGRAM_ID'] == "") ? "" : "AND (PROGRAM_ID ='{$filter['PROGRAM_ID']}') ";
            $sql .= ($filter['COURSE_CODE'] == "") ? "" : "AND (COURSE_CODE ='{$filter['COURSE_CODE']}') ";
            $sql .= ($filter['ADVISER_CITIZEN_ID'] == "") ? "" : "AND (ADVISER_CITIZEN_ID ='{$filter['ADVISER_CITIZEN_ID']}' OR ADVISER_CITIZEN_ID_2 ='{$filter['ADVISER_CITIZEN_ID']}' OR ADVISER_CITIZEN_ID_3 ='{$filter['ADVISER_CITIZEN_ID']}') ";
            $sql .= ($filter['COURSE_PARALLEL_STATUS'] == "") ? "" : "AND (COURSE_PARALLEL_STATUS ='{$filter['COURSE_PARALLEL_STATUS']}') ";
            $sql .= ($filter['ADMIS_TYPE'] == "") ? "" : "AND (ADMIS_TYPE ='{$filter['ADMIS_TYPE']}') ";
            $sql .= ($filter['NAME_TH'] == "") ? "" : "AND ((STD_FNAME_TH LIKE '%{$filter['NAME_TH']}%') OR (STD_LNAME_TH LIKE '%{$filter['NAME_TH']}%')) ";
            $sql .= ($filter['NAME_EN'] == "") ? "" : "AND ((STD_FNAME_EN LIKE '%{$filter['NAME_EN']}%') OR (STD_LNAME_EN LIKE '%{$filter['NAME_EN']}%')) ";
            $sql .= ($filter['PROJECT_ID'] == "") ? "" : "AND (PROJECT_ID = '{$filter['PROJECT_ID']}') ";

            $sql .= ($filter['STD_INFO_ID'] == "") ? "" : "AND (STD_INFO_ID  IN ({$filter['STD_INFO_ID']})) ";
            $sql .= ($filter['RELIGION_ID'] == "") ? "" : "AND (CLASS ='{$filter['RELIGION_ID']}') ";
            $sql .= ($filter['BLOOD'] == "") ? "" : "AND (BLOOD ='{$filter['BLOOD']}') ";
            //Thanaroj
            $sql .= ($filter['BIRTH_PROVINCE_ID'] == "") ? "" : "AND (BIRTH_PROVINCE_ID ='{$filter['BIRTH_PROVINCE_ID']}') ";
            $sql .= ($filter['DOMICILE_PROVINCE_ID'] == "") ? "" : "AND (DOMICILE_PROVINCE_ID ='{$filter['DOMICILE_PROVINCE_ID']}') ";
            $sql .= ($filter['CHK_ADMIT_YEAR'] == "") ? "" : "AND (ADMIT_YEAR >='{$filter['CHK_ADMIT_YEAR']}') ";
            $sql .= ($filter['LESS_ADMIT_YEAR'] == "") ? "" : "AND (ADMIT_YEAR <='{$filter['LESS_ADMIT_YEAR']}') ";
            $sql .= ($filter['CHK_DURATION_YEAR'] == "") ? "" : "AND ({$filter['CHK_DURATION_YEAR']} >= (ADMIT_YEAR + CONCEN_YEAR_MAX)) ";
            $sql .= ($filter['STUDENT_CODE_FROM'] == "") ? "" : "AND (STUDENT_CODE >='{$filter['STUDENT_CODE_FROM']}') ";
            $sql .= ($filter['STUDENT_CODE_TO'] == "") ? "" : "AND (STUDENT_CODE <='{$filter['STUDENT_CODE_TO']}') ";

            $sql .= ($filter['ADVISER_CITIZEN_ID_1'] == "") ? "" : "AND (ADVISER_CITIZEN_ID ='{$filter['ADVISER_CITIZEN_ID_1']}') ";
            $sql .= ($filter['ADVISER_CITIZEN_ID_2'] == "") ? "" : "AND (ADVISER_CITIZEN_ID_2 ='{$filter['ADVISER_CITIZEN_ID_2']}') ";
            $sql .= ($filter['ADVISER_CITIZEN_ID_3'] == "") ? "" : "AND (ADVISER_CITIZEN_ID_3 ='{$filter['ADVISER_CITIZEN_ID_3']}') ";
            $sql .= ($filter['ASEAN_STATUS'] == "") ? "" : " AND (ASEAN_STATUS ='{$filter['ASEAN_STATUS']}') ";
            $sql .= ($filter['CONCEN_YEAR_MAX'] == "") ? "" : "AND (CONCEN_YEAR_MAX ='{$filter['CONCEN_YEAR_MAX']}') ";
            $sql .= ($filter['REGIS_LOCK_STATUS'] == "") ? "" : " AND (REGIS_LOCK_STATUS ='{$filter['REGIS_LOCK_STATUS']}') ";

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

    public function select_by_stdcode(array $field, $stdcode)
    {
        $result = $this->select_by_filter($field, ['STUDENT_CODE' => $stdcode]);
        $row =  (is_object($result) ? $result->FetchRow() : false);
        return $row;
    }


}