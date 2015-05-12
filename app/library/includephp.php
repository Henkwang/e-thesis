<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 9/2/2558
 * Time: 15:44
 */

namespace EThesis\Library;


class Includephp
{
    private $base = __BASE_DIR__;

    private $librery_dir;

    private $helper_dir;

    public function __construct()
    {
        $this->librery_dir = $this->base . 'app/library/';
        $this->helper_dir = $this->base . 'app/helper';
    }

    public function library($library_name = '')
    {
        $fullname = $this->librery_dir . $library_name . '.php';
        if ($this->file_exist($fullname)) {
            return include($fullname);
        }
        return false;
    }

    public function helper($helper_name = '')
    {
        $fullname = $this->helper_dir . $helper_name . '_helper.php';
        if ($this->file_exist($fullname)) {
            return include($fullname);
        }
        return false;
    }


    public function file_exist($full_filename)
    {
        $filename = $this->replace_dbslash($full_filename);
        $ok = file_exists($filename);
        return $ok;
    }

}