<?php

return new \Phalcon\Config(array(

     /*
     * Path Project
     */
    'application' => array(
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir' => __DIR__ . '/../../app/models/',
        'viewsDir' => __DIR__ . '/../../app/views/',
        'pluginsDir' => __DIR__ . '/../../app/plugins/',
        'libraryDir' => __DIR__ . '/../../app/library/',
        'cacheDir' => __DIR__ . '/../../app/cache/',
        'baseUri' => '/e-thesis/',
    ),
    /*
     * Module In Project
     * Ex. 'test'
     * Add : namespace Module\Test;
     * In Folder : \controllers\test;
     */
    'module' => [
        'testform',
        'main',
        'ajax',
        'system',
        'bs'
    ],

    /*
     * Session In E-Thesis Web
     * Use In Library Session
     */
    'session' =>[
        'interval_refresh' => 99,
        'lifetime' => 36000,
        'close_start' => '01:00',// เวลาปีดเวปในแต่ละวัน
        'close_end' => '04:00',
    ]


));

