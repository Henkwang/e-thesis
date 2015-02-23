<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 30/1/2558
 * Time: 13:15
 */
namespace EThesis\Controllers\Ajax;

class AutopageController extends \Phalcon\Mvc\Controller
{

    var $lang = 'th';

    private $_response = ['auth' => true];

    public function initialize()
    {
        $session = new \EThesis\Library\Session();
        $this->lang = ($session->has('auth') ? $session->get('auth') : die(json_encode(['auth' => false])));
    }

    public function  getpageAction()
    {
        $this->_add_response('label', 'Test Back Call');
        $this->_add_response('url', $this->url->get('system/label'));
        $this->_return_response();
    }

    private function _add_response($key, $val)
    {
        $this->_response[$key] = $val;
    }

    private function _return_response()
    {
        echo json_encode($this->_response);
    }


}