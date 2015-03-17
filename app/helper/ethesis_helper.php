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

if (!function_exists('get_url')) {
    function get_url()
    {
        $base_url = \Phalcon\DI\FactoryDefault::getDefault()['url']->get();
        $uri = $_SERVER['REQUEST_URI'];
        return substr($_SERVER['REQUEST_URI'], stripos($uri, $base_url) + strlen($base_url));
    }
}