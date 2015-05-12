<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 8/5/2558
 * Time: 10:07
 */

namespace EThesis\Library;


class Autofill
{


    private $cache_lang = [];
    private $lang_class;

    private $cache_datamodel = [];
    private $datamodel_class;

    public function __construct()
    {
        $this->lang_class = new \EThesis\Library\Lang();
        $this->datamodel_class = new \EThesis\Controllers\Ajax\AutocompleteController();
    }

    public function fill_lang($name, $key)
    {
        $value = '';
        if (empty($this->cache_lang[$name])) {
            $tmp = $this->lang_class->lang($name);
            $this->cache_lang[$name] = $tmp;
        }
        $value = (isset($this->cache_lang[$name][$key]) ? $this->cache_lang[$name][$key] : '');
        return $value;
    }

    public function fill_datamodel($name, $key)
    {
        $value = '';
        if (empty($this->cache_datamodel[$name])) {
            $tmp = $this->datamodel_class->get_dataModel($name);
            $this->cache_datamodel[$name] = $tmp;
        }
        $value = (isset($this->cache_datamodel[$name][$key]) ? $this->cache_datamodel[$name][$key] : '');
        return $value;
    }

    public function fill_asean($key)
    {
        $value = '';
        if($key == 'T'){
            $value = '<b style="color:red;">AEC</b>';
        }else{
            $value = '<b style="">-</b>';
        }
        return $value;
    }


} 