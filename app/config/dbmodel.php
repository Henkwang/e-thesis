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
];

