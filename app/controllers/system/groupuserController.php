<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 18/2/2558
 * Time: 9:06
 */

namespace EThesis\Controllers\System;


class GroupuserController extends \Phalcon\Mvc\Controller {


    protected function initialize()
    {
        $this->view->enable();
        $this->view->setVar('di', $this);
    }

    public function indexAction(){

        $this->view->pick('system/groupIndex');
    }

} 