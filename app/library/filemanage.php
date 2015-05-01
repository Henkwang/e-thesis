<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 1/5/2558
 * Time: 9:59
 */

namespace EThesis\Library;


class FileManage
{


    /*
     * Root DIRECTORY
     */
    var $base_dir = '';
    /*
     * Base URL
     */
    var $base_url = '';
    /*
     * Current Directory
     * โฟร์เดอร์ปัจจุบัน
     */
    var $current_dir = '';

    /*
     * Case File Data
     */
    var $case = true;
    private $data_file = '';

    var $file_ext = [
        'msoffice' => [
            'doc' => 'application/msword',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint'
        ],
        'pdf' => [
            'pdf' => 'application/pdf'
        ],
        'image' => [
            'gif' => 'image/gif',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'tif' => 'image/tiff',
            'tiff' => 'image/tiff',
            'bmp' => 'image/x-ms-bmp'
        ]
    ];

    public function __construct($base_dir = '', $case = true)
    {
        $this->base_dir = __BASE_DIR__;
        $this->base_url = __BASE_URL__;

        if (!empty($current_dir)) {
            $base_dir = trim(str_replace('\\', '/', $base_dir));
            if (stripos($base_dir, '/') === 0) {
                $base_dir = substr($base_dir, 1);
            }
        }
        $this->base_dir .= $base_dir;
    }


    public function get_pdf($filename)
    {
        $ok = false;
        $full_name = $this->base_dir . '/' . $this->current_dir . '/' . $filename;
        $full_name = $this->replace_dbslash($full_name);
        if ($this->file_exist($full_name) && $this->check_type_file($filename, 'pdf')) {
            $type_file = $this->get_typefile($filename);
            $filename2 = 'pdf_' . time() . '.pdf'; /* Note: Always use .pdf at the end. */
            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="' . $filename2 . '"');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($full_name));
            header('Accept-Ranges: bytes');
            @readfile($full_name);

        }
        return $ok;
    }

    public function get_image($filename)
    {
        $ok = false;
        $full_name = $this->base_dir . '/' . $this->current_dir . '/' . $filename;
        $full_name = $this->replace_dbslash($full_name);
        if ($this->file_exist($full_name)) {
            $type_file = $this->get_typefile($filename);
            $filename2 = 'image_' . time() . '.' . $type_file; /* Note: Always use .pdf at the end. */
            $ext = (isset($this->file_ext['image'][$type_file]) ? $this->file_ext['image'][$type_file] : 'image/jpeg');
            header('Content-Type: ' . $ext);
            header('Content-Length: ' . filesize($full_name));
            header('Accept-Ranges: bytes');
            @readfile($full_name);
        }
        return $ok;
    }


    public function file_exist($full_filename)
    {
        $filename = $this->replace_dbslash($full_filename);
        $ok = file_exists($filename);
        return $ok;
    }

    public function replace_dbslash($string)
    {
        while (stripos($string, '//') !== FALSE) {
            $string = str_replace('//', '/', $string);
        }
        return $string;
    }

    public function check_type_file($filename, $type = null)
    {
        $ok = false;
        if (!empty($type)) {
            $gettype = $this->get_typefile($filename);
            if (is_array($type)) {
                foreach ($type as $val) {
                    $val = strtolower($val);
                    if ($val == $gettype) {
                        $ok = true;
                        break;
                    }
                }
            } else {
                $type = strtolower($type);
                $ok = ($gettype == $type ? true : false);
            }
        }
        return $ok;
    }

    public function get_typefile($filename)
    {
        $type = substr($filename, strrpos($filename, '.') + 1);
        $type = strtolower($type);
        $type = trim($type);
        return $type;
    }


}