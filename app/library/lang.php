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
    private $_sess_lang;

    function __construct($lang = 'th')
    {
        $lang = (empty($lang) ? 'th' : $lang);
        $this->_sess_lang = $lang;
        $this->_lang = require(__DIR__ . '../../lang/' . $lang . '.php');
    }

    public function label($name = '', $more = '')
    {
        if (isset($this->_lang[$name])) {
            if (is_array($this->_lang[$name])) {
                $label = (isset($this->_lang[$name]['label']) ? $this->_lang[$name]['label'] : $name);

            } else {
                $label = $this->_lang[$name];
            }
            $label = str_replace('__M__', $more, $label);
            return $label;
        } else if (stripos($name, '_TH') !== FALSE) {
            $name_new = $label = str_replace('_TH', '_ML', $name);
            return (isset($this->_lang[$name_new]) ? $this->_lang[$name_new] . '(TH)' : $name);
        } else if (stripos($name, '_EN') !== FALSE) {
            $name_new = $label = str_replace('_EN', '_ML', $name);
            return (isset($this->_lang[$name_new]) ? $this->_lang[$name_new] . '(EN)' : $name);
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

    public function label_manual($th, $en = false)
    {
        if ($en !== false && $this->_sess_lang == 'en') {
            return $en;
        } else {
            return $th;
        }
    }


}