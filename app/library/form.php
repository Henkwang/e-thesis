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


    /*
     * Construct
     * TYPE INPUT
     */
    const TYPE_TEXT = 'text';
    const TYPE_NUMBER = 'number';
    const TYPE_SELECT = 'select';
    const TYPE_DATE = 'date';
    const TYPE_TIME = 'time';
    const TYPE_DATETIME = 'datetime';
    const TYPE_AUTOCOMPLETE = 'autocomplete';
    const TYPE_CHECKBOX = 'checkbok';
    const TYPE_RADIO = 'radio';
    const TYPE_HIDDEN = 'hidden';
    const TYPE_EMAIL = 'email';
    const TYPE_PASSWORD = 'password';
    const TYPE_TEL = 'tel';
    const TYPE_FILE = 'file';
//    const TYPE_IMAGE = 'image';

    const TYPE_SUBMIT = 'submit';
    const TYPE_RESET = 'reset';

    const TYPE_TEXTAREA = 'textarea';
    /*
     * param Default
     */
    public $param_default = [
        'type' => Form::TYPE_TEXT,
        'required' => true,
        'col' => 4,
        'offset' => 0,
        'date_format' => 'dd/MM/yyyy',
        'time_format' => 'hh:mm',
        'datetime_format' => 'dd/MM/yyyy hh:mm',
        'class_default' => 'form-control',
        'class' => '',
        'style' => '',
        'value' => false,
        'lock_parmis' => true, // lock data by user session
        'attr' => [],
        'select_multiple' => false,
        'select_size' => false,
        'select_filter' => false,
        'disable' => false,

        //- Select CheckBok Radio AutoComplete
        'data' => [],
        'datamodel' => false,
        'datalang' => false,
        // Label
        'label_col' => 2,
        'label_offset' => 0,
        'label' => false,
        'label_class' => '',
        'label_class_default' => 'control-label padding-mini',
        'label_style' => '',
        'label_hide' => false,
        'label_disable' => false,
        //-------------------
        // Validate
        'required' => true,
        'max' => false,
        'min' => false,
        'maxlength' => false,
        'minlength' => false,
        'trim' => false,
        'phone' => false,
        'citizen' => false,
        'callback' => false,
        'tooltip' => false,
        'between' => [],
        //-------------
    ];


    /*
     * var Public
     */
    public $formname = 'ethesisform';
    public $lang = 'th';
    public $url_set = '';


    /*
     * var Private
     */
    private $_formid = '';
    private $_input_array = [];
    private $_param_array = [];
    private $_input_validate = [];
    /*
     * External Library
     */
    private $_lang_class;
    private $_datamodel_class;
    private $_session_class;

    /*
     * Status Form;
     */
    private $_complete = false;
    private $_cp_label = [];
    private $_cp_input = [];
    private $_cp_validate = '';

    /*
     * model Set
     */
    private $_set_model;
    private $_log_model;
    private $pk_id = null;


    public function __construct()
    {
        $this->_formid = "__" . substr(sha1(rand(0, 1024)), 0, 10);
        $this->formname = $this->formname . $this->_formid;
        $this->_lang_class = new \EThesis\Library\Lang();
        $this->_session_class = DIPhalcon::get('sess');
        $this->lang = $this->_session_class->get('lang');
        $this->_datamodel_class = new \EThesis\Controllers\Ajax\AutocompleteController();

    }


    /**
     * @param $name
     * @param array $param
     */
    public function add_input($name, array $param = [])
    {
//        print_r($param);
        $this->_input_array[] = $name;
        $this->_param_array[$name] = Form::array_marge($this->param_default, $param);

    }


    /**
     * @param $model
     */
    public function set_model($model)
    {
        $this->_set_model = $model;
    }


    /**
     * @return array
     * @data_return formname, formid, action, label, input, valid
     */
    public function get_form()
    {
        if (!$this->_complete) {
            $this->_complete = true;
            $this->_create_input();
            $this->_create_label();
            $this->_create_validate();
        }
        $data = [
            'formname' => $this->formname,
            'formid' => $this->_formid,
            'action' => $this->url_set,
            'label' => $this->_cp_label,
            'input' => $this->_cp_input,
            'valid' => $this->_cp_validate,
        ];
//        print_r($this->_param_array);
        return $data;
    }


    public function set_data($set = null, $pk_id = null)
    {
        $set = strtolower($set);
        $post = $_POST;
        $data = [];
        if ($set <> 'delete') {
            foreach ($this->_param_array as $name => $param) {
                if (!$param['disable']) {
                    if (is_array($post[$name])) {
                        $data[$name] = implode($post[$name]);
                    } else if (isset($post[$name])) {
                        $data[$name] = $post[$name];
                    }
                }
            }
        }
        if ($set == 'add') {
            $result = $this->_set_model->insert($data);
            $msg = ($result === true ? 'เพิ่มข้อมูลสำเร็จ' : 'ผิดพลาด! ไม่สามารถเพิ่มข้อมูลได้');
        } else if ($set == 'edit') {
            if (empty($pk_id)) {
                $msg = 'ผิดพลาด! ไม่สามารถแก้ไขข้อมูลได้';
            } else {
                $result = $this->_set_model->update($data, $pk_id);
                $msg = ($result === true ? 'แก้ไขข้อมูลสำเร็จ' : 'ผิดพลาด! ไม่สามารถแก้ไขข้อมูลได้');
            }
        } else if ($set == 'delete') {
            if (empty($pk_id)) {
                $msg = 'ผิดพลาด! ไม่สามารถแก้ไขข้อมูลได้';
            } else {
                $result = $this->_set_model->delete($pk_id);
                $msg = ($result === true ? 'ลบข้อมูลสำเร็จ' : 'ผิดพลาด! ไม่สามารถลบข้อมูลได้');
            }
        } else {
            $msg = 'การเข้าถึงไม่ถูกต้อง หรือคุณอาจไม่ได้รับนุญาติใจการจัดการข้อมูล';
        }
        return $msg;
    }


    private function _create_label()
    {
        foreach ($this->_param_array as $name => $param) {
            $html = '';
            if ($param['label_disable'] == FALSE) {
                $lang = ($param['label'] ? $param['label'] : $this->_lang_class->label($name));
                $attr['for'] = $name;
                $attr['class'] = ['col-xs-offset-' . $param['label_offset'], 'col-xs-' . $param['label_col'], $param['label_class_default'], $param['label_class']];
                $attr['style'] = ['display:' . ($param['label_hide'] ? 'none;' : 'block;'), $param['label_style']];
                $html = '<label ' . $this->join_attr($attr) . '>' . $lang . '</label>';
            }
            $this->_cp_label[$name] = $html;
        }

    }

    private function _create_input()
    {
        foreach ($this->_param_array as $name => $param) {
            if (!$param['disable']) {
                if (in_array($param['type'], [Form::TYPE_TEXT, Form::TYPE_HIDDEN, Form::TYPE_NUMBER, Form::TYPE_EMAIL, Form::TYPE_TEL])) {
                    $this->_cp_input[$name] = $this->_input_textbox($name, $param);
                } else if (in_array($param['type'], [Form::TYPE_DATE, Form::TYPE_DATETIME, Form::TYPE_TIME])) {
                    $this->_cp_input[$name] = $this->_input_datetime($name, $param);
                } else if ($param['type'] == Form::TYPE_SELECT) {
                    $this->_cp_input[$name] = $this->_input_select($name, $param);
                } else if ($param['type'] == Form::TYPE_CHECKBOX) {
                    $this->_cp_input[$name] = $this->_input_checkbox($name, $param);
                } else if ($param['type'] == Form::TYPE_RADIO) {
                    $this->_cp_input[$name] = $this->_input_radio($name, $param);
                }
            }
        }

    }

    private function _create_validate()
    {
        $this->lang = strtolower($this->lang);
        if ($this->lang == 'en') {
            $lang = '';
        } else {
            $lang = 'locale:\'' . strtolower($this->lang) . '_' . strtoupper($this->lang) . '\',';
        }
        $this->_cp_validate = "
                $('#{$this->formname}')
                    .formValidation({
                        framework: 'bootstrap',
                        excluded: ':hidden :not(:visible) :disabled ',
                        {$lang}
                        icon: {
                            valid: 'md-check',
                            invalid: 'md-close',
                            validating: 'md-refresh',
                            feedback: 'form-control-feedback'
                        },
                        fields: decode_json('" . json_encode($this->_input_validate) . "'),
                    });";
    }


    private function check_validation($input_name, array $vilid)
    {
//        print_r($vilid);
        $attr = [];
        if ($vilid['max'] || $vilid['max'] || $vilid['between']) {
            $attr['between']['max'] = (!empty($vilid['between'] ? max($vilid['between']) : $vilid['max']));
            $attr['between']['min'] = (!empty($vilid['between'] ? min($vilid['between']) : $vilid['min']));
        }
        if ($vilid['maxlength'] !== false) {
            $attr['stringLength']['max'] = $vilid['maxlength'];
        }
        if ($vilid['minlength'] !== false) {
            $attr['stringLength']['min'] = $vilid['minlength'];
        }
        if ($vilid['phone'] !== false || $vilid['type'] == Form::TYPE_TEL) {
            $attr['phone'] = ['country' => 'TH'];
        }
        if ($vilid['callback'] !== false) {
            $attr['callback']['callback'] = $vilid['callback'];
        }
        if ($vilid['required'] !== false) {
            $attr['notEmpty'] = [];
        }
        if ($vilid['type'] == Form::TYPE_NUMBER) {
            $attr['integer'] = [];
        }
        if ($vilid['citizen'] !== FALSE) {
            $attr['id'] = ['country' => 'TH'];
        }

//        print_r($attr);

        $this->_input_validate[$input_name]['validators'] = $attr;

    }

    private function join_attr(array $attr)
    {
        $html = '';
        foreach ($attr as $key => $val) {
            $attr_name = $key;
            $attr_value = '';
            if (is_array($val)) {
                $attr_value = implode(' ', $val);
            } else {
                $attr_value = $val;
            }
            $html .= "{$attr_name}=\"{$attr_value}\" ";
        }
        return $html;
    }


    private function _input_textbox($input_name, array $param = array())
    {
//        print_r($this->check_validation($param));
//        print_r($param);
        $div_attr['class'] = ['col-xs-' . $param['col'], 'col-xs-offset-' . $param['offset'], 'padding-mini'];
        $attr = $param['attr'];
        $attr['type'] = Form::TYPE_TEXT;
        $attr['id'] = $input_name;
        $attr['name'] = $input_name;
        $attr['value'] = $param['value'];
        $attr['class'] = [$param['class_default'], $param['class']];
        $html = '<div ' . $this->join_attr($div_attr) . '>'
            . '<input ' . $this->join_attr($attr) . '/>'
            . '</div>';
        $this->check_validation($input_name, $param);
        return $html;
    }


    private function _input_datetime($input_name, array $param = array())
    {
        $div_attr['class'] = ['col-xs-' . $param['col'], 'col-xs-offset-' . $param['offset'], 'padding-mini'];
        $attr = $param['attr'];

        $attr['type'] = $param['type'];
        $attr['id'] = $input_name;
        $attr['name'] = $input_name;
        $attr['value'] = $param['value'];
        $attr['class'] = [$param['class_default'], $param['class']];
        $attr['readonly'] = 'true';

        $format = '';
        $icon = '';
        if (stripos($param['type'], 'date') !== FALSE) {
            $attr['data-format'][] = 'dd/MM/yyyy';
            $icon['data-date-icon'][] = 'md-event md-lg';
        }
        if (stripos($param['type'], 'time') !== FALSE) {
            $attr['data-format'][] = 'hh:mm';
            $icon['data-date-icon'][] = 'md-access-time md-lg';
        }

        $html = '<div ' . $this->join_attr($div_attr) . '>'
            . '<div class="input-group">'
            . '<input ' . $this->join_attr($attr) . '/>'
            . '<span class="add-on input-group-addon"><i ' . $this->join_attr($icon) . '></i> </span>'
            . '</div></div>'
            . '<script type="text/javascript">'
            . '$(function () {$(\'#' . $this->formname . ' #' . $input_name . '_datetime\').datetimepicker({language: lang})'
            . '.on(\'changeDate\', function (ev) { $(\'#' . $this->formname . '\').formValidation(\'revalidateField\', \'' . $input_name . '\');'
            . '$(\'#' . $this->formname . ' #' . $input_name . '\').removeClass(\'empty\'); });'
            . '});'
            . '</script>';
        $this->check_validation($input_name, $param);
        return $html;
    }

    private function _input_select($input_name, array $param = array())
    {

        $div_attr['class'] = ['col-xs-' . $param['col'], 'col-xs-offset-' . $param['offset'], 'padding-mini'];
        $attr = $param['attr'];

        $attr['id'] = $input_name;
        $attr['name'] = $input_name;
        $attr['class'] = [$param['class_default'], $param['class']];

        $value = explode(',', $param['value']);
        $data = [];
        if ($param['datamodel']) {
            $data = $this->_datamodel_class->get_dataModel($param['datamodel']);
        } else if ($param['datalang']) {
            $data = $this->_lang_class->lang($input_name);
            unset($data['label']);
        } else {
            $data = $param['data'];
        }
        $option = '';
        $script = '';
        if ($param['select_filter'] == false) {
            foreach ($data as $key => $label) {
                $option .= '<option ' . (in_array($key, $value) ? 'selected="selected"' : "") . ' value="' . $key . '">' . $label . '</option>';
            }
        } else {
            if (isset($this->_param_array[$param['select_filter']]['value'])) {
                $parent_value = $this->_param_array[$param['select_filter']]['value'];
                $tmp_data = (isset($data[$parent_value]) ? $data[$parent_value] : []);
            }
            foreach ($tmp_data as $key => $label) {
                $option .= '<option ' . (in_array($key, $value) ? 'selected="selected"' : "") . ' value="' . $key . '">' . $label . '</option>';
            }
        }
        if ($param['select_filter'] !== false) {
            $script = '<script>'
                . '$(function(){'
                . '$(\'#' . $this->formname . ' #' . $param['select_filter'] . ' \').change(function(){ '
                . 'var data = decode_json(\'' . json_encode($data) . '\');'
                . 'var chil = $(\'#' . $this->formname . ' #' . $input_name . '\');'
                . 'chil.html(\'<option value="">-- กรุณาเลือกข้อมูล --</option>\');'
                . '$.each(data[$(this).val()], function(k,v){chil.append(\'<option value="\' + k + \'">\' + v + \'</option>\')});'
                . '$(\'#' . $this->formname . '\').formValidation(\'revalidateField\', \'' . $input_name . '\');'
                . '});'
                . '});'
                . '</script>';
        }

        $html = '<div ' . $this->join_attr($div_attr) . '>'
            . '<select ' . $this->join_attr($attr) . '>'
            . '<option value="">-- กรุณาเลือกข้อมูล --</option>'
            . $option
            . '</select>'
            . $script
            . '</div>';

        $this->check_validation($input_name, $param);
        return $html;
    }

    private function _input_checkbox($input_name, array $param = array())
    {
        $div_attr['class'] = ['col-xs-' . $param['col'], 'col-xs-offset-' . $param['offset'], 'padding-mini'];
        $attr = $param['attr'];
        $attr['name'] = $input_name . '[]';
        $attr['type'] = Form::TYPE_CHECKBOX;
        if ($param['required']) {
            $attr['required'] = 'required';
        }

        $value = explode(',', $param['value']);
        $data = [];
        if ($param['datamodel']) {
            $data = $this->_datamodel_class->get_dataModel($param['datamodel']);
        } else if ($param['datalang']) {
            $data = $this->_lang_class->lang($input_name);
            unset($data['label']);
        } else {
            $data = $param['data'];
        }

        $checkbox = '<div ' . $this->join_attr($div_attr) . '>';
        foreach ($data as $key => $val) {
            $attr['value'] = $key;
            if (in_array($key, $value)) {
                $attr['checked'] = 'checked';
            }
            $checkbox .= ''
                . '<div class="checkbox checkbox-primary">'
                . '<label>'
                . '<input  ' . $this->join_attr($attr) . ' >' . $val
                . '</label>'
                . '</div>';
        }
        $checkbox .= '</div>';
        $this->check_validation($input_name, $param);
        return $checkbox;
    }


    private function _input_radio($input_name, array $param = array())
    {
        $div_attr['class'] = ['col-xs-' . $param['col'], 'col-xs-offset-' . $param['offset'], 'padding-mini'];
        $attr = $param['attr'];
        $attr['name'] = $input_name;
        $attr['type'] = Form::TYPE_RADIO;
        if ($param['required']) {
            $attr['required'] = 'required';
        }

        $value = explode(',', $param['value']);
        $data = [];
        if ($param['datamodel']) {
            $data = $this->_datamodel_class->get_dataModel($param['datamodel']);
        } else if ($param['datalang']) {
            $data = $this->_lang_class->lang($input_name);
            unset($data['label']);
        } else {
            $data = $param['data'];
        }

        $radio = '<div ' . $this->join_attr($div_attr) . '>';
        $radio .= '<div class="radio radio-primary">';
        $tmp_attr = $attr;
        foreach ($data as $key => $val) {
            $attr = $tmp_attr;
            $attr['value'] = $key;
            if (in_array($key, $value)) {
                $attr['checked'] = 'checked';
            }
            $radio .= ''
                . '<label>'
                . '<input  ' . $this->join_attr($attr) . ' >' . $val
                . '</label>';
        }
        $radio .= '</div></div>';
        $this->check_validation($input_name, $param);
        return $radio;
    }

    /**
     * @param string $url_set
     */
    public function set_urlset($url_set)
    {
        $this->url_set = $url_set;
    }

    public static function array_marge(array $base_array, array $update_array = [])
    {
        foreach ($update_array as $key => $val) {
            if (isset($base_array[$key])) {
                $base_array[$key] = $val;
            }
        }
        return $base_array;
    }


}