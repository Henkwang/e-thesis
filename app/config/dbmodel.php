<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 30/1/2558
 * Time: 13:44
 */

return [
    'MAS_FACULTY' => [
        'module' => 'Upreg',
        'model' => 'Faculty_model',
        'key' => 'FACULTY_ID',
        'label' => "FACULTY_CODE + ' ' + FACULTY_NAME_ML",
        'filter' => ['FACULTY_TYPE'=>'F'],
        'order' => 'FACULTY_CODE ASC',
    ],
    'MAS_PROGRAM' => [
        'module' => 'Upreg',
        'model' => 'Program_model',
        'key' => 'PROGRAM_ID',
        'label' => "PROGRAM_NAME_ML",
        'filter' => [],
        'order' => 'PROGRAM_NAME_ML ASC',
        'parent' => 'FACULTY_ID',
    ]
];

