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
class Form
{

    var $genID;
    private $index_ID;

    public $formname;
    private  $action_form;

    private $arrInput = [];

    private $arrInput_ID = [];

    private $arrLabel_ID = [];

    private $arrLabel = [];


    public function __construct($formname, $action = '')
    {
        $this->genID = "__" . substr(sha1(rand(0, 1024)), 0, 10);
        $this->formname = $formname . $this->genID;
        $url = \Phalcon\DI\FactoryDefault::getDefault()['url'];
        $this->action_form = $url->get($action);
    }


    public function add_field($id, $label = FALSE, $col = 2, $offset = 0)
    {
        $this->index_ID = $id;
        $this->add_label($col, $offset, $label);
    }

    /*
     * Get DATA
     */
    function get_data_form()
    {
        $data = [
            'formname'=> $this->formname,
            'input' => $this->get_input(),
            'label' => $this->get_label(),
            'formvalid' => $this->get_validate_script(),
            'set' => $this->get_inputSet(),
        ];
        return $data;
    }

    public function get_input()
    {
        return $this->arrInput;
    }

    public function get_label()
    {
        return $this->arrLabel;
    }

    public function get_inputSet(){
        return $this->arrInput_ID;
    }

    public function get_validate_script()
    {
        return "$('#{$this->formname}')
                    .formValidation({
                        framework: 'bootstrap',
                        locale: 'th_TH',
                        icon: {
                            valid: 'md-check',
                            invalid: 'md-close',
                            validating: 'md-refresh',
                            feedback: 'form-control-feedback'
                        },
                        excluded: ':hidden :not(:visible) :disabled ',
                    });";
    }


    /*
     * End  Get DATA
     */


    private function add_label($col, $offset = 0, $label_set = FALSE)
    {
        if ($label_set == FALSE) {
            $label_set = $this->index_ID;
            $this->arrLabel_ID [] = $this->index_ID;
        }
        $this->arrLabel[$this->index_ID] = '<label for="'
            . $this->index_ID . '" class="' . (empty($offset) ? '' : "col-xs-offset-{$offset} ") . 'col-xs-' . $col
            . ' control-label padding-mini">' . $label_set . '</label>';
    }

    public function add_text(array $param = array())
    {
        $type = (isset($param['type']) ? $param['type'] : 'text');
        $col = (isset($param['col']) ? $param['col'] : '4');
        $offset = (isset($param['offset']) ? $param['offset'] : '0');
        $moreClass = (isset($param['class']) ? $param['class'] : '');
        $require = (isset($param['required']) && $param['required'] ? ' required ' : '');
        $attr = $this->check_validation((isset($param) ? $param : []));

        $this->arrInput[$this->index_ID] = '<div class="' . (empty($offset) ? '' : "col-xs-offset-{$offset} ") . ' col-xs-' . $col . ' padding-mini">'
            . '<input type="' . $type . '" class="form-control ' . $moreClass . '" id="' . $this->index_ID . '" name="' . $this->index_ID . '" ' . $require . $attr . '/>'
            . '</div>';

        $this->arrInput_ID [$this->index_ID] = ['type' => $type, 'set' => (isset($param['set']) ? $param['set'] : '')];
    }

    public function add_datetime(array $param = array())
    {
        $datetime = (isset($param['datetime']) ? $param['datetime'] : 'date');
        $col = (isset($param['col']) ? $param['col'] : '4');
        $offset = (isset($param['offset']) ? $param['offset'] : '0');
        $moreClass = (isset($param['class']) ? $param['class'] : '');
        $require = (isset($param['required']) && $param['required'] ? ' required ' : '');
        $onChangeJS = (isset($param['onchange ']) ? $param['onchange'] : '');
        $attr = $this->check_validation((isset($param) ? $param : []));

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
            . '<input data-format="' . $format . '" type="text" class="form-control"  name="' . $this->index_ID . '" id="' . $this->index_ID . '" readonly ' . $require . $attr . '/>'
            . '<span class="add-on input-group-addon"><i ' . $icon . '></i> </span>'
            . '</div></div>'
            . '<script type="text/javascript">'
            . '$(function () {$(\'#' . $this->formname . ' #' . $this->index_ID . '_datetime\').datetimepicker({language: lang})'
            . '.on(\'changeDate\', function (ev) { $(\'#' . $this->formname . '\').formValidation(\'revalidateField\', \'' . $this->index_ID . '\');'
            . '$(\'#' . $this->formname . ' #' . $this->index_ID . '\').removeClass(\'empty\'); ' . $onChangeJS . ' });'
            . '});</script>';

        $this->arrInput_ID [$this->index_ID] = ['type' => 'datetime', 'set' => (isset($param['set']) ? $param['set'] : '')];
    }

    public function add_select(array $param = array())
    {
        $dataOrDBModel = (isset($param['data_db']) ? $param['data_db'] : []);
        $col = (isset($param['col']) ? $param['col'] : '4');
        $offset = (isset($param['offset']) ? $param['offset'] : '0');
        $require = (isset($param['required']) && $param['required'] ? ' required ' : '');
        $attr = $this->check_validation((isset($param) ? $param : []));

        if (!is_array($dataOrDBModel)) {
            $auto = new \EThesis\Controllers\Autovalue\Autocomplete();
            $data = $auto->get_dataModel($dataOrDBModel);
        } else {
            $data = $dataOrDBModel;
        }
        $option = '';
        if (empty($param['filter'])) {
            foreach ($data as $key => $label) {
                $option .= '<option value="' . $key . '">' . $label . '</option>';
            }
        }


        if (!empty($param['filter'])) {
            $script = "\n"
                . 'var option_data=decode_json(\'' . json_encode($data) . '\');'
                . "\n"
                . 'filter_selected(option_data, "' . $this->formname . '", "' . $param['filter'] . '", "' . $this->index_ID . '");'
                . '';
        }

        $this->arrInput[$this->index_ID] = '<div class="' . (empty($offset) ? '' : "col-xs-offset-{$offset} ") . ' col-xs-' . $col . ' padding-mini">'
            . '<select class="form-control" id="' . $this->index_ID . '" name="' . $this->index_ID . '" ' . $require . $attr . '>'
            . '<option value="">-- กรุณาเลือกข้อมูล --</option>'
            //. ($require <> TRUE ? '<option value="">-- กรุณาเลือกข้อมูล --</option>' : '<option value="">-- กรุณาเลือกข้อมูล --</option>')
            . $option
            . '</select>'
            . '</div>'
            . '<script>'
            . '$(function () {'
            . '' . $script
            . '});</script>';

        $this->arrInput_ID [$this->index_ID] = ['type' => 'select', 'set' => (isset($param['set']) ? $param['set'] : '')];
    }

    public function add_checkbox(array $param = array())
    {
        $dataOrDBModel = (isset($param['data_db']) ? $param['data_db'] : []);
        $col = (isset($param['col']) ? $param['col'] : '4');
        $offset = (isset($param['offset']) ? $param['offset'] : '0');
        $require = (isset($param['required']) && $param['required'] ? ' required ' : '');
        $attr = $this->check_validation((isset($param) ? $param : []));

        if (!is_array($dataOrDBModel)) {
            $auto = new \EThesis\Controllers\Autovalue\Autocomplete();
            $data = $auto->get_dataModel($dataOrDBModel);
        } else {
            $data = $dataOrDBModel;
        }

        $checkbox = '<div class="' . (empty($offset) ? '' : "col-xs-offset-{$offset} ") . ' col-xs-' . $col . ' padding-mini">';
        foreach ($data as $key => $val) {
            $checkbox .= ''
                . '<div class="checkbox checkbox-primary">'
                . '<label>'
                . '<input type="checkbox" name="' . $this->index_ID . '[]" value="' . $key . '" ' . $require . $attr . '>' . $val
                . '</label>'
                . '</div>';
        }
        $checkbox .= '</div>';
        $this->arrInput[$this->index_ID] = $checkbox;

        $this->arrInput_ID [$this->index_ID . '[]'] = ['type' => 'checkbox', 'set' => (isset($param['set']) ? $param['set'] : '')];
    }

    public function add_radio(array $param = array())
    {
        $dataOrDBModel = (isset($param['data_db']) ? $param['data_db'] : []);
        $col = (isset($param['col']) ? $param['col'] : '4');
        $offset = (isset($param['offset']) ? $param['offset'] : '0');
        $require = (isset($param['required']) && $param['required'] ? ' required ' : '');
        $attr = $this->check_validation((isset($param) ? $param : []));

        if (!is_array($dataOrDBModel)) {
            $auto = new \EThesis\Controllers\Autovalue\Autocomplete();
            $data = $auto->get_dataModel($dataOrDBModel);
        } else {
            $data = $dataOrDBModel;
        }

        $checkbox = '<div class="' . (empty($offset) ? '' : "col-xs-offset-{$offset} ") . ' col-xs-' . $col . ' padding-mini">';
        foreach ($data as $key => $val) {
            $checkbox .= ''
                . '<div class="radio radio-primary">'
                . '<label>'
                . '<input type="radio" name="' . $this->index_ID . '" value="' . $key . '" ' . $require . $attr . '>' . $val
                . '</label>'
                . '</div>';
        }
        $checkbox .= '</div>';
        $this->arrInput[$this->index_ID] = $checkbox;

        $this->arrInput_ID [$this->index_ID . ''] = ['type' => 'radio', 'set' => (isset($param['set']) ? $param['set'] : '')];
    }

    private function check_validation(array $vilid)
    {
        $attr = '';
        $attr .= (isset($vilid['max']) ? ' data-fv-between-max="' . $vilid['max'] . '" ' : '');
        $attr .= (isset($vilid['min']) ? ' data-fv-between-min="' . $vilid['max'] . '" ' : '');
        $attr .= (isset($vilid['maxlength']) ? ' data-fv-stringlength-max="' . $vilid['maxlength'] . '" ' : '');
        $attr .= (isset($vilid['minlength']) ? ' data-fv-stringlength-min="' . $vilid['minlength'] . '" ' : '');
        $attr .= (isset($vilid['trim']) ? ' data-fv-stringlength-trim="true" ' : '');
        $attr .= (isset($vilid['phone']) ? ' data-fv-phone="true" data-fv-phone-country="TH" ' : '');
        $attr .= (isset($vilid['callback']) ? ' data-fv-callback="true" data-fv-callback-callback="' . $vilid['callback'] . '" ' : '');

        $attr .= (isset($vilid['tooltip']) ? ' data-toggle="tooltip" title="' . $vilid['tooltip'] . '" ' : '');
        return $attr;

    }

}
