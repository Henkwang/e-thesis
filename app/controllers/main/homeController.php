<?php
namespace EThesis\Controllers\Main;


use EThesis\Library\Session;

class HomeController extends \Phalcon\Mvc\Controller
{
    protected function initialize()
    {
        $this->view->reset();
    }

    public function indexAction()
    {
        $this->view->setTemplateBefore('/login');
        \Phalcon\Tag::setTitle('Index');
        $this->view->pick('/main/login');
        $this->view->setVar('actionLogin', $this->url->get('main/auth/login'));
    }

    public function mainAction()
    {
        \Phalcon\Tag::setTitle('Main');
        $this->view->setTemplateBefore('/navbar');
    }




}

