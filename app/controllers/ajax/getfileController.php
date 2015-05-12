<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 30/1/2558
 * Time: 13:15
 */
namespace EThesis\Controllers\Ajax;

class GetfileController
{

    var $lang = 'th';
    private $_dbmodel;

    var $filemanage;

    protected function initialize()
    {
        $session = new \EThesis\Library\Session();
        $this->lang = ($session->has('lang') ? $session->get('lang') : 'TH');
        $this->_dbmodel = require(__DIR__ . "/../../config/dbmodel.php");
        $this->filemanage = new  \EThesis\Library\FileManage();

    }

    public function __construct()
    {
        $this->initialize();
    }

    public function get_pdfAction($b64 = ''){
        $tmp = $this->filemanage->base_dir;
        $this->filemanage->base_dir .= 'public/uploads/';
        $filename = text_decode($b64);
        $this->filemanage->get_pdf($filename);
        $this->filemanage->base_dir = $tmp;

    }

    public function get_imageAction($b64 = ''){
        $tmp = $this->filemanage->base_dir;
        $this->filemanage->base_dir .= 'public/uploads/';
        $filename = text_decode($b64);
//        $filename = 'public/uploads/bs1/pic_person/2015042910571430279845668.jpg';
        if($this->filemanage->get_image($filename)){
            echo 'true';
        }else{
            echo 'false';
        }
        $this->filemanage->base_dir = $tmp;

    }

    public function get_bankimageAction(){
        $this->filemanage->get_image('public/resource/img/no_image.png');
    }

    public function get_bankpersonAction(){
        $this->filemanage->get_image('public/resource/img/blank.jpg');
    }

}