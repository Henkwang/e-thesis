<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;




/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */

$di = new FactoryDefault();


$di->set('router', function () use ($config) {
    return require __DIR__ . '/routes.php';
}, true);


$di->set('logs', function ()  {
    $log = new \EThesis\Models\System\Sys_log_model();
    return $log;
}, true);


$di->set('lang', function ()  {
    $lang = new \EThesis\Library\Lang();
    return $lang;
}, true);

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

/**
 * Setting up the view component *
 */
$di->set('view', function () use ($config) {

    $view = new View();

    $view->setRenderLevel(View::LEVEL_ACTION_VIEW);


    $view->setViewsDir($config->application->viewsDir);


    $view->setTemplateBefore('navbar');


    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ));

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    /*
     * Set Disable Render View
     */
    $view->disable();
    return $view;
}, true);




/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Start the session the first time some component request the session service
 */

$di->set('session', function () {

    $session = new \EThesis\Library\Session();

    return $session;
});

/**
 * Lang สอง ภาาา
 */
//$di->set('lang', function () {
//    $lang = new \EThesis\Library\Lang();
//    return $lang;
//});


/*
  |--------------------------------------------------------------------------
  | Dispatcher
  |--------------------------------------------------------------------------
  |
  | It registers the dispatcher as service and will attach event on him to create
  | 404 system if the controller/action is not found
  |
 */
$di->set('dispatcher', function () use ($di) {

        $dispatcher = new \Phalcon\Mvc\Dispatcher();

        $evManager = $di->getShared('eventsManager');

        $evManager->attach("dispatch:beforeException", function ($event, $dispatcher, $exception) use ($di) {


                switch ($exception->getCode()) {

                    case Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                    case Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:

                        $dispatcher->forward(
                            array(
                                'namespace' => 'EThesis\Controllers\Main',
                                'controller' => 'errors',
                                'action' => 'show404',
                            )
                        );

                        return FALSE;
                }
            }
        );

        $dispatcher->setEventsManager($evManager);
        return $dispatcher;
    }, TRUE
);

/*
  |--------------------------------------------------------------------------
  | Funciotn Helper
  |--------------------------------------------------------------------------
  |
  | It registers the dispatcher as service and will attach event on him to create
  | 404 system if the controller/action is not found
  |
 */

//$di->set('helper', function () use ($config) {
//    include __DIR__ . '/../../app/helper/helperClass.php';
//    include __DIR__ . '/../../app/helper/test.php';
//
//
//}, TRUE);