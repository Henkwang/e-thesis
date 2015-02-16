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
        'callback' => false,
        'tooltip' => false,
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
        $this->_input_array[] = $name;
        $this->_param_array[$name] = array_merge($this->param_default, $param);
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
                $attr['class'] = ['col-xs-offset-' . $param['offset'], 'col-xs-' . $param['col'], $param['label_class_default'], $param['label_class']];
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
        $this->_cp_validate = "$('#{$this->formname}')
                    .formValidation({
                        framework: 'bootstrap',
                        {$lang}
                        icon: {
                            valid: 'md-check',
                            invalid: 'md-close',
                            validating: 'md-refresh',
                            feedback: 'form-control-feedback'
                        },
                        excluded: ':hidden :not(:visible) :disabled ',
                    });";
    }


    private function check_validation(array $vilid)
    {
        $attr = [];
        if ($vilid['max'] !== false) {
            $attr['data-fv-between-max'] = $vilid['max'];
        }
        if ($vilid['min'] !== false) {
            $attr['data-fv-between-min'] = $vilid['min'];
        }
        if ($vilid['maxlength'] !== false) {
            $attr['data-fv-stringlength-max'] = $vilid['maxlength'];
        }
        if ($vilid['minlength'] !== false) {
            $attr['data-fv-stringlength-min'] = $vilid['minlength'];
        }
        if ($vilid['trim'] !== false) {
            $attr['data-fv-stringlength-trim'] = 'true';
        }
        if ($vilid['phone'] !== false) {
            $attr['data-fv-phone'] = 'true';
            $attr['data-fv-phone-country'] = 'TH';
        }
        if ($vilid['callback'] !== false) {
            $attr['data-fv-callback'] = 'true';
            $attr['data-fv-callback-callback'] = $vilid['callback'];
        }
        if ($vilid['required'] !== false) {
            $attr['required'] = 'required';
        }

        return $attr;
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
        $div_attr['class'] = ['col-xs-' . $param['col'], 'col-xs-offset-' . $param['offset'], 'padding-mini'];
        $attr = array_merge($param['attr'], $this->check_validation($param));
        $attr['type'] = $param['type'];
        $attr['id'] = $input_name;
        $attr['name'] = $input_name;
        $attr['value'] = $param['value'];
        $attr['class'] = [$param['class_default'], $param['class']];
        $html = '<div ' . $this->join_attr($div_attr) . '>'
            . '<input ' . $this->join_attr($attr) . '/>'
            . '</div>';
        return $html;
    }


    private function _input_datetime($input_name, array $param = array())
    {
        $div_attr['class'] = ['col-xs-' . $param['col'], 'col-xs-offset-' . $param['offset'], 'padding-mini'];
        $attr = array_merge($param['attr'], $this->check_validation($param));

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
        return $html;
    }

    private function _input_select($input_name, array $param = array())
    {
        $div_attr['class'] = ['col-xs-' . $param['col'], 'col-xs-offset-' . $param['offset'], 'padding-mini'];
        $attr = array_merge($param['attr'], $this->check_validation($param));

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
        foreach ($data as $key => $label) {
            $option .= '<option ' . (in_array($key, $value) ? 'selected="selected"' : "") . ' value="' . $key . '">' . $label . '</option>';
        }
        $html = '<div ' . $this->join_attr($div_attr) . '>'
            . '<select ' . $this->join_attr($attr) . '>'
            . '<option value="">-- กรุณาเลือกข้อมูล --</option>'
            . $option
            . '</select>'
            . '</div>';
        return $html;
    }

    private function _input_checkbox($input_name, array $param = array())
    {
        $div_attr['class'] = ['col-xs-' . $param['col'], 'col-xs-offset-' . $param['offset'], 'padding-mini'];
        $attr = array_merge($param['attr'], $this->check_validation($param));
        $attr['name'] = $input_name . '[]';
        $attr['type'] = Form::TYPE_CHECKBOX;

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
        return $checkbox;
    }


    private function _input_radio($input_name, array $param = array())
    {
        $div_attr['class'] = ['col-xs-' . $param['col'], 'col-xs-offset-' . $param['offset'], 'padding-mini'];
        $attr = array_merge($param['attr'], $this->check_validation($param));
        $attr['name'] = $input_name . '[]';
        $attr['type'] = Form::TYPE_CHECKBOX;

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
        foreach ($data as $key => $val) {
            $attr['value'] = $key;
            if (in_array($key, $value)) {
                $attr['checked'] = 'checked';
            }
            $radio .= ''
                . '<div class="radio radio-primary">'
                . '<label>'
                . '<input  ' . $this->join_attr($attr) . ' >' . $val
                . '</label>'
                . '</div>';
        }
        $radio .= '</div>';
        return $radio;
    }

    /**
     * @param string $url_set
     */
    public function set_urlset($url_set)
    {
        $this->url_set = $url_set;
    }


}