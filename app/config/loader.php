<?php
$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
//$loader->registerDirs(

$loader->registerNamespaces(array(
 //   'Module\Main' => __DIR__ . '/../controllers/main/',
    'EThesis\Controllers' => __DIR__ . '/../controllers/',
    'EThesis\Models' => __DIR__ . '/../models/',
    'EThesis\Library' => __DIR__ . '/../library/',
    'EThesis\Helper' => __DIR__ . '/../helper/',
));


$loader->register();