<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 30/1/2558
 * Time: 13:44
 */

return [
    'MAS_FACULTY' => [
        'module' => 'reg',
        'model' => 'reg_faculty_model',
        'key' => 'FACULTY_ID',
        'label' => "FACULTY_CODE + ' ' + FACULTY_NAME_ML",
        'filter' => ['FACULTY_TYPE' => 'F'],
        'order' => 'FACULTY_CODE ASC',
    ],
    'MAS_DIVISION' => [
        'module' => 'reg',
        'model' => 'reg_faculty_model',
        'key' => 'FACULTY_ID',
        'label' => "FACULTY_CODE + ' ' + FACULTY_NAME_ML",
        'filter' => [],
        'order' => 'FACULTY_CODE ASC',
    ],
    'MAS_PROGRAM' => [
        'module' => 'reg',
        'model' => 'reg_program_model',
        'key' => 'PROGRAM_ID',
        'label' => "PROGRAM_NAME_ML",
        'filter' => [],
        'order' => 'PROGRAM_NAME_ML ASC',
        'parent' => 'FACULTY_ID',
    ],
    'MAS_TITLE' => [
        'module' => 'reg',
        'model' => 'reg_title_model',
        'key' => 'TITLE_ID',
        'label' => "TITLE_NAME_ML",
        'filter' => [],
        'order' => 'TITLE_ID ASC',
    ],
    'MAS_TITLE_SHORT' => [
        'module' => 'reg',
        'model' => 'reg_title_model',
        'key' => 'TITLE_ID',
        'label' => "TITLE_SHORT_NAME_ML",
        'filter' => [],
        'order' => 'TITLE_ID ASC',
    ],
    /*
     * HRD
     */
    'HRD_HIS_INSTRUCTOR' => [
        'module' => 'hrd',
        'model' => 'hrd_person_model',
        'key' => 'PERSON_ID',
        'label' => "NAME_ML",
        'filter' => ['NOT_STATUSLIST_ID' => '17,18,19,20,21,22', 'WORKLINE_ID' => 1],
        'order' => 'NAME_ML ASC',
    ],
    'HRD_POS_ACAD' => [
        'module' => 'hrd',
        'model' => 'Hrd_position_acad_model',
        'key' => 'POS_ACAD_ID',
        'label' => "POS_ACAD_CODE + ' ' + POS_ACAD_NAME_ML",
        'filter' => [],
        'order' => 'POS_ACAD_CODE ASC',
    ],
    'HRD_POS_EXEC' => [
        'module' => 'hrd',
        'model' => 'Hrd_position_exec_model',
        'key' => 'POS_EXEC_ID',
        'label' => "POS_EXEC_NAME_ML",
        'filter' => [],
        'order' => 'POS_EXEC_NAME_ML ASC',
    ],
    'HRD_TITLE' => [
        'module' => 'hrd',
        'model' => 'hrd_title_model',
        'key' => 'TITLE_ID',
        'label' => "TITLE_NAME_ML",
        'filter' => [],
        'order' => 'TITLE_ID ASC',
    ],
    'HRD_FACULTY' => [
        'module' => 'hrd',
        'model' => 'hrd_faculty_model',
        'key' => 'FACULTY_ID',
        'label' => "FACULTY_CODE + ' ' + FACULTY_NAME_ML",
        'filter' => [],
        'order' => 'FACULTY_CODE ASC',
    ],
    'HRD_DIVISION' => [
        'module' => 'hrd',
        'model' => 'hrd_division_model',
        'key' => 'DIVISION_ID',
        'label' => "DIVISION_NAME_ML",
        'filter' => [],
        'order' => 'DIVISION_CODE ASC',
    ],
    'HRD_DIVISION' => [
        'module' => 'hrd',
        'model' => 'hrd_position_model',
        'key' => 'POS_ID',
        'label' => "POS_NAME_TH",
        'filter' => [],
        'order' => 'POS_NAME_TH ASC',
    ],

];

