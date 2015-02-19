<?php

//ini_set('display_errors', '0');

error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);


date_default_timezone_set('Asia/Bangkok');

try {

    /*
     * Helper
     */
    include __DIR__. "/../app/helper/define_varible.php";
    include __DIR__. "/../app/helper/ethesis_helper.php";

    /**
     * Read the configuration
     */
    $config = include __DIR__ . "/../app/config/config.php";

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
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage();
}
