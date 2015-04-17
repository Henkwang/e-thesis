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
//    const TYPE_DATETIME = 'datetime';
    const TYPE_AUTOCOMPLETE = 'autocomplete';
    const TYPE_CHECKBOX = 'checkbox';
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
        'col' => 12,
        'offset' => 0,
        'date_format' => 'dd/MM/yyyy',
        'time_format' => 'hh:mm',
        'datetime_format' => 'dd/MM/yyyy hh:mm',
        'class_default' => 'form-control',
        'button_class_default' => 'btn btn-raised',
        'class' => '',
        'style' => '',
        'holder' => '',
        'value' => false,
        'lock_parmis' => true, // lock data by user session
        'attr' => [],
        'select_multiple' => false,
        'select_size' => false,
        'select_filter' => false,
        'disable' => false,
        'textarea_rows' => 3,

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
        'novalidate' => false,
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
        'choice' => false,
        'maxitem' => false,
        'filetype' => false,
        'filesize' => false,


        //-------------
        'formsearch' => true,
        'formedit' => true,

    ];

    var $file_ext = [
        'msoffice' => [
            'doc' => 'application/msword',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint'
        ],
        'pdf' => [
            'pdf' => 'application/pdf'
        ],
        'image' => [
            'gif' => 'image/gif',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'tif' => 'image/tiff',
            'tiff' => 'image/tiff',
            'bmp' => 'image/x-ms-bmp'
        ]
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
    private $_cp_label_pure = [];
    private $_cp_label_group = [];
    private $_cp_input = [];
    private $_cp_validate = '';
    private $_url_prefix = '';

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
        $this->_session_class = new \EThesis\Library\Session();
        $this->lang = $this->_session_class->get('lang');
        $this->_datamodel_class = new \EThesis\Controllers\Ajax\AutocompleteController();
        $this->add_input('pk_id', ['type' => Form::TYPE_HIDDEN, 'novalidate' => true]);
        $this->_log_model = new \EThesis\Models\System\Sys_log_model();

    }


    /**
     * @param $name
     * @param array $param
     */
    public function add_input($name, array $param = [])
    {
//        print_r($param);
        $this->_input_array[] = $name;
        $this->_param_array[$name] = $this->array_marge($this->param_default, $param);
//        print_r($this->_param_array[$name]);

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
            'formclass' => $this,
            'formname' => $this->formname,
            'formid' => $this->_formid,
            'action' => $this->url_set,
            'label' => $this->_cp_label,
            'labelpure' => $this->_cp_label_pure,
            'labelgroup' => $this->_cp_label_group,
            'input' => $this->_cp_input,
            'valid' => $this->_cp_validate,
            'preurl' => $this->_url_prefix,
        ];
//        print_r($this->_param_array);
        return $data;
    }

    public function get_inputgroup_complate($table_name, $set = true, $num_group = 2)
    {
        $data = $this->get_form();
        $numinput = count($data['input']);
        $html = '<form id="' . $data['formname'] . '" action="' . $data['action'] . '" role="form" class="form-horizontal" method="post" ' . ($set ? '' : ' onsubmit="return false"') . ' >';
        $html .= '<div class="row"><div class="col-xs-12 col-lg-10 col-lg-offset-1"">';
        $html .= $data['input']['pk_id'];
        unset($data['input']['pk_id']);
        $arr_name = array_keys($data['input']);
        $numinput--;

        for ($i = 0; $i < $numinput; $i += $num_group) {
            $xs = floor(12 / $num_group);
            $html .= '<div class="row">';
            for ($j = 0; $j < $num_group && ($i + $j) < $numinput; $j++) {
                $html .= '<div class="col-xs-' . $xs . '">';
                $html .= '<div class="form-group">';
                $html .= '<div class="input-group">';
                $html .= $data['labelgroup'][$arr_name[$i + $j]];
                $html .= $data['input'][$arr_name[$i + $j]];
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }
        if ($set == true) {
            $html .= '<div class="row">';
            $html .= '<div class="col-xs-offset-5">';
            $html .= '<button type="submit" class="btn btn-success" name="OK"><i class="md-done"></i> ' . $this->_lang_class->label('OK') . '</button>';
            $html .= '</div></div>';

            $html .= '<script>';
            $html .= $data['valid'];
            $html .= '.on(\'success.form.fv\', function(e) {
                e.preventDefault();
                var $form = $(e.target);
                var bv = $form.data(\'formValidation\');
                $.post($form.attr(\'action\'), $form.serialize(),  function(result){
                     alert(result.msg);
                    $(\'#' . $table_name . '_wrapper #top #EDIT\').add(\'#' . $table_name . '_wrapper #top #DELETE\').addClass(\'disabled\');
                    O' . $table_name . '.ajax.reload();
                    close_active(\'main_tab\');
                }, \'json\');
            });';
            $html .= ' $.material.init()';
            $html .= '</script>';
        } else {
            $html .= '<div class="row"><div class="col-xs-offset-5">';
            $html .= ' <button type="submit" class="btn btn-primary btn-raised" ';
            $html .= 'onclick="O' . $table_name . '.search(JSON.stringify($(\'#' . $this->formname . '\').serializeArray())).draw();"><i class="md-search"></i>' . $this->_lang_class->label('SEARCH') . '</button>';
            $html .= '<button type="reset" class="btn btn-default btn-raised">    <i class="md-refresh"></i> ' . $this->_lang_class->label('CLEAR') . '</button>';
            $html .= '</div></div>';
        }
        $html .= '</div></div>';
        $html .= '</form>';
        return $html;

    }

    public function get_formedit($pk_id)
    {
        $field = $this->_input_array;
        $field[array_search('pk_id', $field)] = $this->_set_model->primary . ' [pk_id]';
        $filter = ['IN_ID' => $pk_id];
        $result = $this->_set_model->select_by_filter($field, $filter);
//        print_r($field);
        if (is_object($result) && $result->RecordCount() > 0) {
            $row = $result->FetchRow();
            foreach ($this->_input_array as $val) {
                $this->_param_array[$val]['value'] = $row[$val];
            }
            $this->_complete = false;
        }
        return $this->get_form();
    }


    public function set_data($set = null)
    {
        $set = strtolower($set);
        $post = $_POST;
        $data = [];
        $pk_id = $post['pk_id'];
        if ($set <> 'delete') {
            foreach ($this->_param_array as $name => $param) {
                if (!$param['disable']) {
                    if (is_array($post[$name])) {
                        $data[$name] = implode(',', $post[$name]);
                    } else if (isset($post[$name])) {
                        $data[$name] = $post[$name];
                    }
                }
            }
        }
        if ($set == 'add') {
            $result = $this->_set_model->insert($data);
            if ($result) {
                $msg = 'เพิ่มข้อมูลสำเร็จ';
                $this->_log_model->set(LOG_ADD_COMPLETE);
            } else {
                $msg = 'ผิดพลาด! ไม่สามารถเพิ่มข้อมูลได้';
                $this->_log_model->set(LOG_ADD_ERROR);
            }

        } else if ($set == 'edit') {
            if (empty($pk_id)) {
                $this->_log_model->set(LOG_UPDATE_ERROR);
                $msg = 'ผิดพลาด! ไม่สามารถแก้ไขข้อมูลได้';
            } else {
                $result = $this->_set_model->update($data, $pk_id);
                if ($result) {
                    $msg = 'แก้ไขข้อมูลสำเร็จ';
                    $this->_log_model->set(LOG_UPDATE_COMPLETE);
                } else {
                    $msg = 'ผิดพลาด! ไม่สามารถแก้ไขข้อมูลได้';
                    $this->_log_model->set(LOG_UPDATE_ERROR);
                }
            }
        } else if ($set == 'delete') {
            if (empty($pk_id)) {
                $this->_log_model->set(LOG_DELETE_USED);
                $msg = 'ผิดพลาด! ไม่สามารถแก้ไขข้อมูลได้';
            } else {
                $result = $this->_set_model->delete($pk_id);
                if ($result) {
                    $msg = 'แก้ไขข้อมูลสำเร็จ';
                    $this->_log_model->set(LOG_DELETE_COMPLETE);
                } else {
                    $msg = 'ผิดพลาด! ไม่สามารถลบข้อมูลได้';
                    $this->_log_model->set(LOG_DELETE_USED);
                }
            }
        } else {
            $msg = 'การเข้าถึงไม่ถูกต้อง หรือคุณอาจไม่ได้รับอนุญาติใจการจัดการข้อมูล';
        }
        return json_encode(['success' => !empty($result), 'msg' => $msg]);
    }

    public function set_responce($set, $ok)
    {
        if ($set == 'add') {
            if ($ok) {
                $msg = 'เพิ่มข้อมูลสำเร็จ';
                $this->_log_model->set(LOG_ADD_COMPLETE);
            } else {
                $msg = 'ผิดพลาด! ไม่สามารถเพิ่มข้อมูลได้';
                $this->_log_model->set(LOG_ADD_ERROR);
            }
        } else if ($set == 'edit') {
            if ($ok) {
                $msg = 'แก้ไขข้อมูลสำเร็จ';
                $this->_log_model->set(LOG_UPDATE_COMPLETE);
            } else {
                $msg = 'ผิดพลาด! ไม่สามารถแก้ไขข้อมูลได้';
                $this->_log_model->set(LOG_UPDATE_ERROR);
            }
        } else if ($set == 'delete') {
            if ($ok) {
                $msg = 'แก้ไขข้อมูลสำเร็จ';
                $this->_log_model->set(LOG_DELETE_COMPLETE);
            } else {
                $msg = 'ผิดพลาด! ไม่สามารถลบข้อมูลได้';
                $this->_log_model->set(LOG_DELETE_USED);
            }
        } else {
            $ok = false;
            $msg = 'ไม่มีการกระทำเกิดขึ้น';
        }
        return json_encode(['success' => $ok, 'msg' => $msg]);
    }


    private function _create_label()
    {
        foreach ($this->_param_array as $name => $param) {
            $html = '';
            $hgroup = '';
            if ($param['label_disable'] == FALSE) {
                $lang = ($param['label'] ? $param['label'] : $this->_lang_class->label($name));
                $attr['for'] = $name;
                $attr['class'] = ['col-xs-offset-' . $param['label_offset'], 'col-xs-' . $param['label_col'], $param['label_class_default'], $param['label_class']];
                $attr['style'] = ['display:' . ($param['label_hide'] ? 'none;' : 'block;'), $param['label_style']];
                $html = '<label ' . $this->join_attr($attr) . '>' . $lang . '</label>';
                $hgroup = '<span class="input-group-addon">' . $lang . '</span>';

            }
            $this->_cp_label[$name] = $html;
            $this->_cp_label_pure[$name] = $lang;
            $this->_cp_label_group[$name] = $hgroup;
        }

    }

    private function _create_input()
    {
        foreach ($this->_param_array as $name => $param) {
            if (!$param['disable']) {
                if (in_array($param['type'], [Form::TYPE_TEXT, Form::TYPE_HIDDEN, Form::TYPE_NUMBER, Form::TYPE_EMAIL, Form::TYPE_TEL])) {
                    $this->_cp_input[$name] = $this->_input_textbox($name, $param);
                } else if (in_array($param['type'], [Form::TYPE_DATE, Form::TYPE_TIME])) {
                    $this->_cp_input[$name] = $this->_input_datetime($name, $param);
                } else if ($param['type'] == Form::TYPE_SELECT) {
                    $this->_cp_input[$name] = $this->_input_select($name, $param);
                } else if ($param['type'] == Form::TYPE_CHECKBOX) {
                    $this->_cp_input[$name] = $this->_input_checkbox($name, $param);
                } else if ($param['type'] == Form::TYPE_RADIO) {
                    $this->_cp_input[$name] = $this->_input_radio($name, $param);
                } else if ($param['type'] == Form::TYPE_TEXTAREA) {
                    $this->_cp_input[$name] = $this->_input_textarea($name, $param);
                } else if (in_array($param['type'], [Form::TYPE_SUBMIT, Form::TYPE_RESET])) {
                    $this->_cp_input[$name] = $this->_input_button($name, $param);
                } else if (in_array($param['type'], [Form::TYPE_AUTOCOMPLETE])) {
                    $this->_cp_input[$name] = $this->_input_autocomplete($name, $param);
                } else if (in_array($param['type'], [Form::TYPE_FILE])) {
                    $this->_cp_input[$name] = $this->_input_file($name, $param);
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
                    })";
    }


    private function check_validation($input_name, array $vilid)
    {
//        print_r($vilid);
        $attr = [];

        if ($vilid['novalidate'] == true) {
            return;
        }

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
//            $attr['phone'] = ['country' => 'TH'];
            $attr['integer'] = [];
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
        if ($vilid['choice'] !== FALSE && is_array($vilid['choice']) && count($vilid['choice']) >= 2) {
            $attr['choice']['max'] = max($vilid['choice']);
            $attr['choice']['min'] = min($vilid['choice']);
        }
        if ($vilid['type'] == Form::TYPE_FILE) {
//            print_r($vilid);
//            die();
            if (!empty($vilid['filetype']) && !empty($this->file_ext[$vilid['filetype']])) {
//                die('true');
                $ext = array_keys($this->file_ext[$vilid['filetype']]);
                $type = array_values($this->file_ext[$vilid['filetype']]);
                $attr['file'] = ['extension' => implode(',', $ext), 'type' => implode(',', $type)];
            }
            if (!empty($vilid['filesize'])) {
                $attr['file']['maxSize'] = $vilid['filesize'] * (1024 * 1024);
            }
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
        if ($param['type'] == Form::TYPE_HIDDEN) {
            $attr['type'] = Form::TYPE_HIDDEN;
        }
        $attr['id'] = $input_name;
        $attr['name'] = $input_name;
        $attr['placeholder'] = $param['holder'];
        $attr['value'] = $param['value'];
        $attr['class'] = [$param['class_default'], $param['class']];
        $html = '<div ' . $this->join_attr($div_attr) . '>'
            . '<input ' . $this->join_attr($attr) . '/>'
            . '</div>';
        $this->check_validation($input_name, $param);
        return $html;
    }

    private function _input_file($input_name, array $param = array())
    {
//        print_r($this->check_validation($param));
//        print_r($param);
        $div_attr['class'] = ['col-xs-' . $param['col'], 'col-xs-offset-' . $param['offset'], 'padding-mini'];
        $attr = $param['attr'];
        $attr['type'] = Form::TYPE_TEXT;
        $attr['id'] = 'textfile_' . $input_name;
        $attr['name'] = 'textfile_' . $input_name;
        $attr['readonly'] = true;
        $attr['placeholder'] = (empty($attr['placeholder']) ? 'ไฟล์' : $attr['placeholder']);
        $attr['value'] = $param['value'];
        $attr['class'] = [$param['class_default'], $param['class'], 'floating-label'];
        $type_upload = array_values($this->file_ext[$param['filetype']]);
        $accept = '';
        if (!empty($type_upload)) {
            $accept = 'accept="' . implode(',', $type_upload) . '"';
        }
        $html = '<div ' . $this->join_attr($div_attr) . '>'
            . '<input ' . $this->join_attr($attr) . '/>'
            . '<input type="file" ' . $accept . ' id="' . $input_name . '" name="' . $input_name . '" ' . ($attr['multiple'] ? 'multiple=""' : '') . ' style="cursor:pointer">'
            . '</div>'
//            . '<script>'
//            . '$(\'#' . $input_name . '\').bind(\'change\', function(){'

//            . '});</script>'
        ;

        $this->check_validation($input_name, $param);
        return $html;
    }


    private function _input_datetime($input_name, array $param = array())
    {
        $div_attr['class'] = ['col-xs-' . $param['col'], 'col-xs-offset-' . $param['offset'], 'padding-mini'];
        $attr = $param['attr'];

        $attr['type'] = Form::TYPE_TEXT;
        $attr['id'] = $input_name;
        $attr['name'] = $input_name;
        $attr['value'] = $param['value'];
        $attr['class'] = [$param['class_default'], $param['class']];
        $attr['readonly'] = 'true';
        if (is_array($param['style'])) {
            $attr['style'] = $this->array_marge($param['style'], ['cursor:pointer']);
        } else {
            $attr['style'] = ['cursor:pointer', $param['style']];
        }
        if ($param['type'] == Form::TYPE_DATE) {
            $html = "\n" . '<div ' . $this->join_attr($div_attr) . '>'
                . '<div class="input-group date" id="' . $input_name . '_datetime">'
                . '<input ' . $this->join_attr($attr) . '/>'
                . '<span class="input-group-addon"><i class="md-event md-lg"></i> </span>'
                . '</div></div>'
                . '<script type="text/javascript">'
                . '$(function () {$(\'#' . $this->formname . ' #' . $input_name . '_datetime\').datepicker({language: lang+\'-th\',format:\'dd/mm/yyyy\'})'
                . '.on(\'changeDate\', function (ev) { $(\'#' . $this->formname . '\').formValidation(\'revalidateField\', \'' . $input_name . '\');'
                . '$(\'#' . $this->formname . ' #' . $input_name . '\').removeClass(\'empty\'); });'
                . '$("#' . $input_name . '").click(function(){$(\'#' . $this->formname . ' #' . $input_name . '_datetime .input-group-addon i\').click();});'
                . '});'
                . '</script>';
        } else if ($param['type'] == Form::TYPE_TIME) {
            $attr['id'] = $input_name;
            $attr['name'] = $input_name;
            $attr['type'] = Form::TYPE_TIME;
            $attr['placeholder'] = $param['holder'];
            $attr['value'] = $param['value'];
            $attr['class'] = [$param['class_default'], $param['class']];
            $html = '<div ' . $this->join_attr($div_attr) . '>'
                . '<input ' . $this->join_attr($attr) . '/>'
                . '</div>';
        }

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
            $data = $this->_lang_class->lang($param['datalang']);
//            print_r($data);
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
            $data = $this->_lang_class->lang($param['datalang']);
            unset($data['label']);
        } else {
            $data = $param['data'];
        }

        $checkbox = '<div ' . $this->join_attr($div_attr) . '>'
            . '<div class="checkbox checkbox-primary">';
        foreach ($data as $key => $val) {
            $attr['value'] = $key;
            if (in_array($key, $value)) {
                $attr['checked'] = 'checked';
            }
            $attr['id'] = $input_name . '_v' . $key;
            $checkbox .= ''
                . '<label>'
                . '<input  ' . $this->join_attr($attr) . ' />' . $val
                . '</label>';
        }
        $checkbox .= '</div></div>';
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
            $data = $this->_lang_class->lang($param['datalang']);
            if (isset($data['label']))
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
            $attr['id'] = $input_name . '_v' . $key;
            $radio .= ''
                . '<label>'
                . '<input  ' . $this->join_attr($attr) . ' >' . $val
                . '</label>';
        }
        $radio .= '</div></div>';
        $this->check_validation($input_name, $param);
        return $radio;
    }

    private function _input_textarea($input_name, array $param = array())
    {
        $div_attr['class'] = ['col-xs-' . $param['col'], 'col-xs-offset-' . $param['offset'], 'padding-mini'];
        $attr = $param['attr'];
        $attr['id'] = $input_name;
        $attr['name'] = $input_name;
        $attr['placeholder'] = $param['holder'];
        $attr['rows'] = $param['textarea_rows'];
//        $attr['value'] = $param['value'];
        $attr['class'] = [$param['class_default'], $param['class']];
        $html = '<div ' . $this->join_attr($div_attr) . '>'
            . '<textarea  ' . $this->join_attr($attr) . ' style="border-top:1px solid #D8D8D8;border-left:1px solid #D8D8D8;border-right:1px solid #D8D8D8;">' . $param['value'] . '</textarea>'
            . '</div>';
        $this->check_validation($input_name, $param);
        return $html;
    }

    private function _input_autocomplete($input_name, array $param = [])
    {
        $div_attr['class'] = ['col-xs-' . $param['col'], 'col-xs-offset-' . $param['offset'], 'padding-mini'];
        $attr['id'] = $input_name;
        $attr['name'] = $input_name;
        $attr['class'] = [$param['class_default'], $param['class']];
        $attr['multiple'] = 'true';
        $maxitem = ($param['maxitem'] !== false ? ',maximumSelectionLength:' . $param['maxitem'] : '');

        $data = [];
        if ($param['datamodel'] && !empty($param['value'])) {
            $search = (!empty($param['value']) ? ['IN_ID' => $param['value']] : []);
            $data = $this->_datamodel_class->get_search($param['datamodel'], $search);
            $option = '';
            foreach ($data as $k => $v) {
                $option .= '<option selected="selected" value="' . $k . '">' . $v . '</option>';
            }
        }


        $url = 'ajax/autocomplete/autoselect2/' . $param['datamodel'];


        $html = '<div ' . $this->join_attr($div_attr) . '>'
            . '<select ' . $this->join_attr($attr) . '>'
            . $option
            . '</select>'
            . '<script>'
            . '$("#' . $input_name . '").select2({'
            . 'language: lang,allowClear: true,multiple: true,minimumInputLength: 1' . $maxitem . ','
            . 'ajax: {'
            . 'url: base_url + "' . $url . '",'
            . 'dataType: \'json\',method: \'POST\', delay: 250,data: function (params) {return {q: params.term};},'
            . 'processResults: function (data, page) {return {results: data.items};}, cache: false'
            . '}'
            . ' });'
            . '</script>'
            . '</div>';
        $this->check_validation($input_name, $param);
        return $html;
    }


    private function _input_button($input_name, array $param = [])
    {
        $div_attr['class'] = ['col-xs-' . $param['col'], 'col-xs-offset-' . $param['offset'], 'padding-mini'];
        $attr = $param['attr'];
        $attr['id'] = $input_name;
        $attr['name'] = $input_name;
        $attr['type'] = $param['type'];
        if ($param['type'] == Form::TYPE_SUBMIT) {
            $attr['class'] = [$param['button_class_default'], 'btn-primary'];
            $icon = '<i class="md-done-all"></i> ';
        } else if ($param['type'] == Form::TYPE_RESET) {
            $icon = '<i class="md-autorenew"></i> ';
            $attr['class'] = [$param['button_class_default'], 'btn-default'];
        }
        $html = '<button ' . $this->join_attr($attr) . '>' . $icon . $this->_lang_class->label($input_name) . '</button>';
        return $html;
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
//        print_r($base_array);
        return $base_array;
    }


}