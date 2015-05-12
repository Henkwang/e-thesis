<?php

//ini_set('display_errors', '0');

error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
(new \Phalcon\Debug)->listen();


date_default_timezone_set('Asia/Bangkok');

define('__BASE_DIR__', __DIR__ . "/../");

try {

    /*
     * Helper
     */
    include __DIR__ . "/../app/helper/define_varible.php";
    include __DIR__ . "/../app/helper/ethesis_helper.php";
    include __DIR__ . "/../app/helper/datetime_helper.php";

    /**
     * Read the configuration
     */
    $config = include __DIR__ . "/../app/config/config.php";

    /*
     * Define Base Url Site
     */
    define('__BASE_URL__', $config['application']['baseUri']);

    /**
     * Read auto-loader
     */
    include __DIR__ . "/../app/config/loader.php";

    /**
     * Read services
     */
    include __DIR__ . "/../app/config/services.php";

    /**
     * Handle the request
     */

    define(__URI_CALL__, str_replace($config['application']['dirRoot'], '',  $_SERVER['REQUEST_URI']));

    $application = new \Phalcon\Mvc\Application($di);

    echo $html = $application->handle()->getContent();


    if ($req_url = $_SERVER['REQUEST_URI']) {
        $log = new \EThesis\Models\System\Sys_log_model();
        $req_url = str_replace($config['application']['dirRoot'], '', $req_url);
        $tmp = explode('/', $req_url);
        $module = $tmp[0];
        $controler = $tmp[1];
        if (count($tmp) == 2) {
            $log->set(LOG_OPEN_PAGE);
        } else if (count($tmp) > 2) {
            $method = strtolower(trim($tmp[2]));
            $set = strtolower(trim($tmp[3]));
            if ($method == 'index') {
                $log->set(LOG_OPEN_PAGE);
            } else if ($method == 'setdata') {
                $ck = stripos($html, '"success":true');
                if ($set == 'add') {
                    if($ck !== FALSE){
                        $log->set(LOG_ADD_COMPLETE);
                    }else{
                        $log->set(LOG_ADD_ERROR);
                    }
                }else if ($set == 'edit') {
                    if($ck !== FALSE){
                        $log->set(LOG_UPDATE_COMPLETE);
                    }else{
                        $log->set(LOG_UPDATE_ERROR);
                    }
                }else if ($set == 'delete') {
                    if($ck !== FALSE){
                        $log->set(LOG_DELETE_COMPLETE);
                    }else{
                        $log->set(LOG_DELETE_USED);
                    }
                }
            }
        }
    }


} catch (\Exception $e) {
    echo $e->getMessage();
}
