<?php

namespace EThesis\Library;


    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

/**
 * Description of form
 *
 * attapon.th@up.ac.th
 */
class FormHtml
{

    var $genID;
    private $index_ID;

    public $formname;

    private $arrInput = [];

    private $validates = [];

    private $arrInput_ID = [];

    private $arrLabel_ID = [];

    private $arrLabel = [];


    public function __construct($formname)
    {
        $this->genID = "__" . substr(sha1(rand(0, 1024)), 0, 10);
        $this->formname = $formname . $this->genID;

    }


    public function add_field($id, $col = 2, $offset = 0, $label = FALSE)
    {
        $this->index_ID = $id;
        $this->add_label($col, $offset, $label);
    }

    public function get_input()
    {
        return $this->arrInput;
    }

    public function get_label()
    {
        return $this->arrLabel;
    }

    public function get_validate()
    {
        return json_encode($this->validates);
    }

    private function add_label($col, $label_set = FALSE, $offset = 0)
    {
        if ($label_set == FALSE) {
            $label_set = $this->index_ID;
            $this->arrLabel_ID [] = $this->index_ID;
        }
        $this->arrLabel[$this->index_ID] = '<label for="'
            . $this->index_ID . '" class="' . (empty($offset) ? '' : "col-xs-offset-{$offset} ") . 'col-xs-' . $col
            . ' control-label padding-mini">' . $label_set . '</label>';
    }

    public function add_text($col, $offset = FALSE, $require = TRUE, $validates = FALSE, $moreClass = '')
    {

        $this->arrInput[$this->index_ID] = '<div class="' . (empty($offset) ? '' : "col-xs-offset-{$offset} ") . ' col-xs-' . $col . ' padding-mini">'
            . '<input type="text" class="form-control ' . $moreClass . '" id="' . $this->index_ID . '" name="' . $this->index_ID . '"/>'
            . '</div>';
        if ($validates) {
            $this->validates[$this->index_ID]['validators'] = $validates;
        }
        if ($require) {
            $this->validates[$this->index_ID]['validators'] ['notEmpty'] = [];
        }
        $this->arrInput_ID [$this->index_ID] = ['type' => 'text', 'set' => 'text'];
    }

    public function add_datetime($datetime = 'date', $col, $offset = FALSE, $require = TRUE, $validates = FALSE, $onChangeJS = '')
    {
        $format = '';
        $icon = '';
        if (stripos($datetime, 'date') !== FALSE) {
            $format .= 'dd/MM/yyyy';
            $icon .= ' data-date-icon="md-event md-lg" ';
        }
        if (stripos($datetime, 'time') !== FALSE) {
            $format .= ' hh:mm';
            $icon .= ' data-time-icon="md-access-time md-lg" ';
        }
        $this->arrInput[$this->index_ID] = '<div id="' . $this->index_ID . '_datetime" '
            . 'class="' . (empty($offset) ? '' : "col-xs-offset-{$offset} ") . ' col-xs-' . $col . ' padding-mini">'
            . '<div class="input-group">'
            . '<input data-format="' . $format . '" type="text" class="form-control"  name="' . $this->index_ID . '" id="' . $this->index_ID . '" readonly/>'
            . '<span class="add-on input-group-addon"><i ' . $icon . '></i> </span>'
            . '</div></div>'
            . '<script type="text/javascript">'
            . '$(function () {$(\'#' . $this->formname . ' #' . $this->index_ID . '_datetime\').datetimepicker({language: lang})'
            . '.on(\'changeDate\', function (ev) { $(\'#' . $this->formname . '\').formValidation(\'revalidateField\', \'' . $this->index_ID . '\');'
            . '$(\'#' . $this->formname . ' #' . $this->index_ID . '\').removeClass(\'empty\'); ' . $onChangeJS . ' });'
            . '});</script>';
        if ($validates) {
            $this->validates[$this->index_ID]['validators'] = $validates;
        }
        if ($require) {
            $this->validates[$this->index_ID]['validators'] ['notEmpty'] = [];
        }
        $this->validates[$this->index_ID]['validators'] ['date'] = ['format' => 'DD/MM/YYYY'];
        $this->arrInput_ID [$this->index_ID] = ['type' => 'datetime', 'set' => 'text'];
    }

    public function add_select($dataOrDBModel, $col, $offset = FALSE, $require = TRUE, $validates = FALSE, $moreClass = '')
    {
        if (!is_array($dataOrDBModel)) {
            $auto = new \EThesis\Controllers\Autovalue\Autocomplete();
            $data = $auto->get_autoByDBModel($dataOrDBModel);
        }else{
            $data = $dataOrDBModel;
        }
        $option = '';
        foreach ($data as $key => $label) {
            $option .= '<option value="' . $key . '">' . $label . '</option>';
        }

        $this->arrInput[$this->index_ID] = '<div class="' . (empty($offset) ? '' : "col-xs-offset-{$offset} ") . ' col-xs-' . $col . ' padding-mini">'
            . '<select class="form-control" id="' . $this->index_ID . '" name="' . $this->index_ID . '">'
            . ($require <> TRUE ? '<option value="">-- กรุณาเลือกข้อมูล --</option>' : '<option value="">-- กรุณาเลือกข้อมูล --</option>')
            . $option
            . '</select>'
            . '</div>'
            . '<script>'
            . '$(function () {'
            . ''
            . '});</script>';
        if ($validates) {
            $this->validates[$this->index_ID]['validators'] = $validates;
        }
        if ($require) {
            $this->validates[$this->index_ID]['validators'] ['notEmpty'] = [];
        }
        $this->arrInput_ID [$this->index_ID] = ['type' => 'select', 'set' => 'select'];
    }

    public function add_checkbox($dataOrDBModel, $col, $offset = FALSE, $require = TRUE, $validates = FALSE, $moreClass = '')
    {
        if (!is_array($dataOrDBModel)) {
            $auto = new \EThesis\Controllers\Autovalue\Autocomplete();
            $data = $auto->get_autoByDBModel($dataOrDBModel);
        }else{
            $data = $dataOrDBModel;
        }

        $checkbox = '<div class="' . (empty($offset) ? '' : "col-xs-offset-{$offset} ") . ' col-xs-' . $col . ' padding-mini">';
        foreach ($data as $key => $val) {
            $checkbox .= ''
                . '<div class="checkbox checkbox-primary">'
                . '<label>'
                . '<input type="checkbox" name="' . $this->index_ID . '[]" value="' . $key . '">' . $val
                . '</label>'
                . '</div>';
        }
        $checkbox .= '</div>';
        $this->arrInput[$this->index_ID] = $checkbox;

        if ($validates) {
            $this->validates[$this->index_ID . '[]']['validators'] = $validates;
        }
        if ($require) {
            $this->validates[$this->index_ID . '[]']['validators'] ['notEmpty'] = [];
        }
        $this->arrInput_ID [$this->index_ID . '[]'] = ['type' => 'checkbox', 'set' => 'checkbox'];
    }

    public function add_radio($dataOrDBModel, $col, $offset = FALSE, $require = TRUE, $validates = FALSE)
    {
        if (!is_array($dataOrDBModel)) {
            $auto = new \EThesis\Controllers\Autovalue\Autocomplete();
            $data = $auto->get_autoByDBModel($dataOrDBModel);
        }else{
            $data = $dataOrDBModel;
        }
        $option = '';
        foreach ($data as $key => $label) {
            $option .= '<option value="' . $key . '">' . $label . '</option>';
        }
        $n = min(5, count($data));

        echo '<pre>' . $option . '</pre>';

        $this->arrInput[$this->index_ID] = '<div class="' . (empty($offset) ? '' : "col-xs-offset-{$offset} ") . ' col-xs-' . $col . ' padding-mini">'
            . '<select class="form-control" id="' . $this->index_ID . '" name="' . $this->index_ID . '" multiple size="' . $n . '" >'
            //. ($require <> TRUE ? '<option value="">-- กรุณาเลือกข้อมูล --</option>' : '<option value="">-- กรุณาเลือกข้อมูล --</option>')
            . $option
            . '</select>'
            . '</div>'
            . '<script>'
            . '$(function () {'
            . ''
            . '});</script>';
        if ($validates) {
            $this->validates[$this->index_ID]['validators'] = $validates;
        }
        if ($require) {
            $this->validates[$this->index_ID]['validators'] ['notEmpty'] = [];
        }
        $this->arrInput_ID [$this->index_ID] = ['type' => 'select', 'set' => 'select'];
    }

}
