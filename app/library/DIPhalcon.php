<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 11/2/2558
 * Time: 9:43
 * PHP : 5.5 up
 */

namespace EThesis\Library;


class DIPhalcon
{


    /**
     * @param string $name
     * @return bool|\stdClass
     */
    public static function get($name = '')
    {
        $DI = \Phalcon\DI\FactoryDefault::getDefault();
        if ($name == '') {
            return $DI;
        } else {
            if (isset($DI[$name])) {
                return $DI[$name];
            } else {
                return FALSE;
            }
        }
    }

    public static function set($name, $set)
    {
        \Phalcon\DI::setDefault($name, $set);
    }

} 