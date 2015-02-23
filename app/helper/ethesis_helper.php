<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 18/2/2558
 * Time: 10:29
 */

if(!function_exists('setVars')){
    function setVar(&$val, $set){
        $val = $set;
    }
}

if(!function_exists('et_array_marge')){
    function et_array_marge(array $base_array, array $update_array = [])
    {
        foreach ($update_array as $key => $val) {
            if (isset($base_array[$key])) {
                $base_array[$key] = $val;
            }
        }
        return $base_array;
    }
}