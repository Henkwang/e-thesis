<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 18/2/2558
 * Time: 10:29
 */

if (!function_exists('setVars')) {
    function setVar(&$val, $set)
    {
        $val = $set;
    }
}

if (!function_exists('array_column_set')) {
    function array_column_set(array $array_column, array $array_set, $key, $set)
    {
        $update_column = $array_column;
        foreach ($array_column as $i => $val) {
            if (isset($array_set[$val[$key]])) {
                $update_column[$i][$set] = $array_set[$val[$key]];
            }
        }
        return $update_column;
    }
}

if (!function_exists('et_array_marge')) {
    function et_array_marge(array $base_array, array $update_array)
    {
        foreach ($update_array as $key => $val) {
            if (isset($base_array[$key])) {
                $base_array[$key] = $val;
            }
        }
        return $base_array;
    }
}


if (!function_exists('array_is_numeric')) {
    function array_is_numeric(array $arr)
    {
        foreach ($arr as $val) {
            if (!is_numeric($val)) {
                return false;
            }
        }
        return true;

    }
}

if (!function_exists('get_private_name')) {
    function get_private_name()
    {
        return date('YmdHi') . str_replace('.', '', microtime(true));
    }
}

if (!function_exists('get_ext_file')) {
    function get_ext_file($filename)
    {
        return substr($filename, strrpos($filename, '.'));
    }
}


if (!function_exists('text_encode')) {
    function text_encode($text)
    {
        return urlencode(base64_encode($text));
    }
}


if (!function_exists('text_decode')) {
    function text_decode($text)
    {
        return base64_decode(urldecode($text));
    }
}

if (!function_exists('default_response')) {
    function default_response($msg = '')
    {
        return [
            'success' => false,
            'msg' => $msg,
        ];
    }
}