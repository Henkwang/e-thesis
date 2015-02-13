<?php

/**
 * Phalcon StarterKit
 *
 * A simple starterKit for PhalconPHP.
 *
 * @package        StarterKit
 * @author        Jeremie Ges & Laurent Schaffner
 * @link        https://github.com/GesJeremie/PhalconPHP-StarterKit
 * @since        Version 0.1
 */
// ------------------------------------------------------------------------

/**
 * ErrorsController
 *
 * Main controller to catch errors.
 *
 * @package        PhalconPHP
 * @subpackage    Controllers
 * @category    Controllers
 */

namespace EThesis\Controllers\Main;

class ErrorsController extends \Phalcon\Mvc\Controller {

    /**
     * Show404
     *
     * When it's impossible to execute one Controller or Action,
     * This method is executed by the app/config/di.php file when we
     * register the dispatcher service.
     *
     * You can find the view of this method into app/views/errors/show404.volt
     *
     * Note : We use the auto view system of PhalconPHP
     * @link https://phalcon-php-framework-documentation.readthedocs.org/en/latest/reference/views.html
     *
     * @access    public
     * @return    void
     */
    protected function initialize() {
        $this->view->enable();
        $this->view->setRenderLevel(1);
    }

    public function show404Action() {
        \Phalcon\Tag::setTitle('404 Error');
        //$this->view->pick('/errors/show404');
    }

}
