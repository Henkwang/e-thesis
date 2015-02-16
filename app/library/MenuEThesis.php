<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 11/2/2558
 * Time: 9:59
 */

namespace EThesis\Library;


use Phalcon\Db\Adapter\Pdo;

class MenuEThesis
{

    var $group;
    var $type;
    var $lang;
    var $premis = [];


    private $_permis_model;
    private $_module_model;

    public $tree;


    public function __construct()
    {
        $session = \EThesis\Library\DIPhalcon::get('sess');
        $this->group = ($session->has('usergroup') ? $session->get('usergroup') : '');
        $this->type = ($session->has('usertype') ? $session->get('usertype') : '');
        $this->lang = ($session->has('lang') ? $session->get('lang') : 'th');
        $this->get_menu();
    }


    function get_menu()
    {
        $id = [];
        if ($this->type == 'A') {
            $id =  [1, 2, 3, 4, 5, 6, 7, 8, 11, 12];
        } else {
            $id = [6, 7, 8];
        }

        $gid = [];
        if ($this->group !== '') {
            $gid = $this->get_groupPremis($this->group);
        }
        foreach ($id as $val) {
            $gid[] = $val;
        }

        $module_model = new \EThesis\Models\System\Module_model();
        $field = ['MOD_PARENT_ID', 'MOD_LEVEL', 'MOD_ID', 'MOD_NAME_' . strtoupper($this->lang) . ' [MOD_NAME]', 'MOD_URL'];
        $filter = ['IN_ID' => implode(',', $gid), 'ENABLE' => 'T'];
        $order = 'MOD_LEVEL ASC, MOD_ORDER ASC';
        $objMenu = $module_model->select_by_filter($field, $filter, $order);

        $fill = $this->fill_adoObj($objMenu, 0);
        $this->tree = $this->createTree($fill['list'], $fill['parent']);

    }


    /**
     * @param $groupID
     * @return array
     */
    private function get_groupPremis($groupID)
    {
        $this->_permis_model = new \EThesis\Models\System\Grouppermis_model();


        $id = [];
        $result = $this->_permis_model->select_by_filter(['MOD_ID'], ['GRD_ID' => $groupID]);
        if ($result && $result->RecordCout() > 0) {
            while ($row = $result->FetchRow()) {
                $id[] = $row['MOD_ID'];
            }
        }
        return $id;
    }

    /**
     * @param $execute
     * @param $root_lvl
     * @return array
     */
    function fill_adoObj($execute, $root_lvl)
    {
        $list = [];
        $arr = [];
        if ($execute && $execute->RecordCount() > 0) {

            while ($row = $execute->FetchRow()) {
                if ($row['MOD_LEVEL'] == $root_lvl) {
                    $arr[] = ['id' => $row['MOD_ID'], 'url' => $row['MOD_URL'], 'name' => $row['MOD_NAME']];
                }
                $list[$row['MOD_PARENT_ID']][] = ['id' => $row['MOD_ID'], 'url' => $row['MOD_URL'], 'name' => $row['MOD_NAME'], 'lvl' => $row['MOD_LEVEL']];
            }
        }
        return ['list' => $list, 'parent' => $arr];
    }


    /**
     * @param $list
     * @param $parent
     * @return array
     */
    function createTree(&$list, $parent)
    {
        $tree = array();
        foreach ($parent as $k => $l) {
            if (isset($list[$l['id']])) {
                $l['children'] = $this->createTree($list, $list[$l['id']]);
            }
            $tree[] = $l;
        }
        return $tree;
    }

    private function createSubMenu(&$html, $tree)
    {
        foreach ($tree as $node) {
            if (isset($node['children'])) {
                $html .= '<li class="dropdown-header">' . $node['name'] . '</li>';
                $this->createSubMenu($html, $node['children']);
                //$html .= '</li>';
            } else {
                $html .= '<li><a href="javascript:call_page(\'' . $node['name'] . '\',\'' . $node['url'] . '\')">' . $node['name'] . '</a></li>';
            }
        }

    }

    private function nbsp($num)
    {
        $nb = '';
        for ($i = 0; $i < $num; $i++) {
            $nb .= '&nbsp;';
        }
        return $nb;
    }

    function createMenu()
    {
        $tree = $this->tree;
        $html = '';
        foreach ($tree as $node) {
            if (isset($node['children'])) {
                $html .= '<li class="dropdown">';
                $html .= '<a href="javascript:void();" data-target="#" class="dropdown-toggle" data-toggle="dropdown">' . $node['name'] . ' <b class="caret"></b></a>';
                $html .= '<ul class="dropdown-menu">';
                $this->createSubMenu($html, $node['children']);
                $html .= '</ul></li>';
            } else {
                $html .= '<li><a href="javascript:call_page(\'' . $node['name'] . '\',\'' . $node['url'] . '\')">' . $node['name'] . '</a></li>';
            }
        }
        return $html;
    }




}