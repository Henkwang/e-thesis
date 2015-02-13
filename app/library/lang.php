<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 9/2/2558
 * Time: 15:44
 */

namespace EThesis\Library;


class Lang
{
    private $_lang;

    function __construct($lang = 'th')
    {
        $this->_lang = require_once(__DIR__ . '../../lang/' . $lang. '.php');
    }

    public function label($name = '')
    {
        if (isset($this->_lang[$name])) {
            if (is_array($this->_lang[$name])) {
                return (isset($this->_lang[$name]['label']) ? $this->_lang[$name]['label'] : $name);
            } else {
                return $this->_lang[$name];
            }
        } else {
            return $name;
        }
    }

    public function lang($name = '')
    {
        if (isset($this->_lang[$name])) {
            return $this->_lang[$name];
        } else {
            return $name;
        }
    }


}