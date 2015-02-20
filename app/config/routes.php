<?php

$router = new Phalcon\Mvc\Router(false);

$router->removeExtraSlashes(true);

$router->add('/', array(
    'namespace' => 'EThesis\Controllers\Main',
    'controller' => 'home',
    'action' => 'index',
));


$router->add('/main', array(
    'namespace' => 'EThesis\Controllers\Main',
    'controller' => 'home',
    'action' => 'main',
));



/*
 * New Module
 */
foreach ($config->module as $module) {
    /*
     * Add Module
     */
    $module = strtolower($module);
    $router->add('/' . $module . '/:controller', array(
        'namespace' => 'EThesis\Controllers\\' . ucfirst($module),
        'controller' => 1
    ));


    $router->add('/' . $module . '/:controller/:action/:params', array(
        'namespace' => 'EThesis\Controllers\\' . ucfirst($module),
        'controller' => 1,
        'action' => 2,
        'params' => 3,
    ));



}
//echo '<pre>';
//print_r($router);
unset($module);





return $router;
