<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 26/3/2558
 * Time: 13:47
 */

namespace EThesis\Library;


use Phalcon\Forms\Element\File;

class Upload
{

    private $FILES;

    public $DEFUALT_UPLOAD = [];
    public $path = 'tmp';
    private $file_status = false;
    private $condition_status = false;

    public $Dmax_size = 10485760; //10Mb
    public $Dbase_path = 'uploads';
    public $Dfix_type = [];


    private $Oname;
    private $Nname;
    private $size; // Mb
    private $tmp_path;
    private $typename;
    private $ext;


    public function __construct($files)
    {
        $this->FILES = $files;
    }

    public function fetch_file()
    {
        if (is_array($this->FILES)) {
            foreach ($this->FILES as $file) {
                if (is_array($file) && !empty($file['tmp_name']) && $this->checkfile($file)) {

                }
            }
        }
    }

    public function upload()
    {
        $error = false;
        $fullnamefile = implode('/', [$this->Dbase_path, $this->path, $this->Nname, $this->ext]);
        if (move_uploaded_file($this->Oname,  $fullnamefile)) {
            $this->file_status = true;
            $data_file = [
                'file'
            ];
        } else {
            $error = true;
        }


    }

    public function get_detail()
    {

    }

    public function get_status()
    {

    }

    public function checkfile(array $file)
    {
        $this->condition_status = (empty($this->Dmax_size) || $file['size'] <= $this->Dmax_size);
        $this->condition_status = (empty($this->Dfix_type) || in_array($file['type'], $this->Dfix_type));
        return $this->condition_status;
    }

    public function msg()
    {

    }


}