<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 5/3/2558
 * Time: 9:16
 */

namespace EThesis\Library;


class Libcontrol
{

    /**
     * @return Session()
     */
    public static function GET_SESSION()
    {
        return DIPhalcon::get('sess');
    }

    /**
     * @return Url()
     */
    public static function GET_URL()
    {
        return DIPhalcon::get('url');
    }

    /**
     * @return Lang
     */
    public static function GET_LANG()
    {
        return new Lang();
    }

    /**
     * @return bool
     */
    public static function CHECK_PERMIS()
    {
        $session = Libcontrol::GET_SESSION();
        if ($session->get('auth') === TRUE && is_numeric($session->get('grouplogin'))) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param array $arr_res
     * @return array
     */
    public static function BACKEND_RESPONCE(array $arr_res = array())
    {
        $session = Libcontrol::GET_SESSION();
        $res = [
            'auth' => $session->get('auth'),
//            'skey' => $session->get('key'),
            'lang' => $session->get('lang'),
//            'grouplogin' => $session->get('grouplogin'),
        ];
        $data = $res + $arr_res;
        return json_encode($data);
    }


} 