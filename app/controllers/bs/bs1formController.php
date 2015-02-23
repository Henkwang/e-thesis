<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 16/2/2558
 * Time: 13:33
 */

namespace EThesis\Controllers\Bs;

use EThesis\Library\Form;

class bs1formController extends \Phalcon\Mvc\Controller
{

    var $init_class;

    private $_lang = 'th';
    private $lang_class;

    public function initialize()
    {
        $this->init_class = \EThesis\Library\DIPhalcon::get();
        if ($this->init_class[sess]->get('auth') <> true) {
            die(AUTH_FALSE_J);
        }
        $this->_lang = $this->init_class[sess]->get('lang');
        $this->lang_class = new \EThesis\Library\Lang();

        //print_r($this->init_class[sess]->get());
    }

    public function indexAction()
    {
//        echo '<pre>';
//        print_r($this->lang);
//        echo '</pre>';
//        return;
        $this->view->enable();
        $form = $this->_get_config();
        $this->view->setVars($form->get_form());
        $this->view->pick('/bs/bs1_form_00');

    }

    private function _get_config()
    {
        $form = new Form();
        $form->param_default['col'] = 12;

        $form->set_urlset($this->init_class['url']->get('bs/bs1/setdata/'));
        $form->set_model(new \EThesis\Models\Bs\Bs1_model());


        $form->add_input('ADD', [
            'type' => Form::TYPE_SUBMIT,
        ]);
        $form->add_input('RESET', [
            'type' => Form::TYPE_RESET,
        ]);

        /*
         * ประวัติส่วนหัว
         */

        $form->add_input('ACAD_YEAR', [
            'type' => Form::TYPE_NUMBER,
            'maxlength' => 4,
            'minlength' => 4,
            'value' => 2558,
        ]);
        $form->add_input('ASEAN_STATUS', [
            'type' => Form::TYPE_RADIO,
            'datalang' => 'ASEAN_STATUS',
            'value' => 'T',
        ]);
        $form->add_input('ADVISER_STATUS', [
            'type' => Form::TYPE_RADIO,
            'datalang' => 'ADVISER_STATUS',

        ]);
        $form->add_input('FACULTY_ID', [
            'type' => Form::TYPE_SELECT,
            'datamodel' => 'MAS_FACULTY',

        ]);
        $form->add_input('PROGRAM_ID_TEST', [
            'type' => Form::TYPE_AUTOCOMPLETE,
            'datamodel' => 'MAS_PROGRAM',
            'value' => '62',
            'maxitem' => 1,
        ]);

        $form->add_input('PROGRAM_ID', [
            'type' => Form::TYPE_SELECT,
            'datamodel' => 'MAS_PROGRAM',
            'select_filter' => 'FACULTY_ID',
        ]);
        $form->add_input('CITIZEN_ID', [
            'type' => Form::TYPE_TEXT,
            'citizen' => true,
        ]);

        /*
         * 1.  ประวัติส่วนตัว
         */
        $form->add_input('TITLE_ID', [
            'type' => Form::TYPE_SELECT,
            'datamodel' => 'MAS_TITLE',
            'label' => 'ชื่อ - สกุล',
        ]);
        $form->add_input('BS1_FNAME_TH', [
            'type' => Form::TYPE_TEXT,
            'holder' => $this->lang_class->label_manual('ชื่อ', 'First Name')
        ]);
        $form->add_input('BS1_LNAME_TH', [
            'type' => Form::TYPE_TEXT,
            'holder' => $this->lang_class->label_manual('สกุล', 'Last Name')

        ]);
        $form->add_input('POS_EXECUTIVE', [
            'type' => Form::TYPE_TEXT,
        ]);
        $form->add_input('POS_ACADEMIC', [
            'type' => Form::TYPE_TEXT,
        ]);
        $form->add_input('DIVISION_ID', [
            'type' => Form::TYPE_SELECT,
            'datamodel' => 'MAS_DIVISION',
        ]);
        $form->add_input('UNIVERSITY_NAME', [
            'type' => Form::TYPE_TEXT,
        ]);
        $form->add_input('TEL_PERSONNEL', [
            'type' => Form::TYPE_TEL,

        ]);
        $form->add_input('TEL_WORK', [
            'type' => Form::TYPE_TEL,
        ]);
        $form->add_input('TEL_WORK_NEXT', [
            'type' => Form::TYPE_TEXT,
        ]);
        /*
         * 2.  ข้อมูลคถณวุฒิสูงสุด
         */
        $form->add_input('MAX_DEGREE_ID', [
            'type' => Form::TYPE_RADIO,
            'datalang' => 'MAX_DEGREE_ID',
        ]);

        $form->add_input('MAX_GRADUATE_DATE', [
            'type' => Form::TYPE_DATE,
        ]);
        $form->add_input('MAX_COURSE_NAME_TH', [
            'type' => Form::TYPE_TEXT,
        ]);
        $form->add_input('MAX_PROGRAM_NAME_TH', [
            'type' => Form::TYPE_TEXT,
        ]);
        $form->add_input('MAX_UNIVERSITY_NAME_TH', [
            'type' => Form::TYPE_TEXT,
        ]);
        $form->add_input('MAX_COUNTRY_NAME_TH', [
            'type' => Form::TYPE_TEXT,
        ]);
        $form->add_input('MAX_COURSE_NAME_EN', [
            'type' => Form::TYPE_TEXT,
        ]);
        $form->add_input('MAX_PROGRAM_NAME_EN', [
            'type' => Form::TYPE_TEXT,
        ]);
        $form->add_input('MAX_UNIVERSITY_NAME_EN', [
            'type' => Form::TYPE_TEXT,
        ]);
        $form->add_input('MAX_COUNTRY_NAME_EN', [
            'type' => Form::TYPE_TEXT,
        ]);

        /*
         * 3.  ประสบการณ์การสอน หรือความเชี่ยวชาญ
         */
        $form->add_input('BS1_EXPERIENCE', [
            'type' => Form::TYPE_TEXTAREA,
            'textarea_rows' => 10,
            'col' => 12,
        ]);

        /*
        * 4.  ผลงานทางวิชาการ
        */
        $form->add_input('PRESENT_ACADEMIC_FOR_GRADUATE', [
            'type' => Form::TYPE_RADIO,
            'data' => ['F' => 'ไม่มี', 'T' => 'มี'],
            'label' => 'และที่มิใช่ส่วนหนึ่งของการศึกษาเพื่อรับปริญญา',
        ]);
        $form->add_input('PRESENT_ACADEMIC_TYPE', [
            'type' => Form::TYPE_CHECKBOX,
            'data' => ['M' => 'ตีพิมพ์ในวารสาร', 'A' => 'เสนอต่อที่ประชุมวิชาการ'],
        ]);
        $form->add_input('PRESENT_ACADEMIC_YEAR', [
            'type' => Form::TYPE_NUMBER,
            'maxlength' => 4,
            'minlength' => 4,
        ]);
        $form->add_input('PRESENT_ACADEMIC_TYPE_NAME', [
            'type' => Form::TYPE_TEXT,
            'label' => 'ชื่อวารสาร/การประชุมวิชาการ'
        ]);
        $form->add_input('PRESENT_ACADEMIC_PLACE_NAME', [
            'type' => Form::TYPE_TEXT,
            'label' => 'สถานที่'
        ]);
        $form->add_input('PRESENT_ACADEMIC_PROVINCE_NAME', [
            'type' => Form::TYPE_TEXT,
            'label' => $this->lang_class->label('PROVINCE')
        ]);
        $form->add_input('PRESENT_ACADEMIC_COUNTRY_NAME', [
            'type' => Form::TYPE_TEXT,
            'label' => $this->lang_class->label('COUNTRY')
        ]);
        $form->add_input('PRESENT_ACADEMIC_TITLE_ID', [
            'type' => Form::TYPE_SELECT,
            'datamodel' => 'MAS_TITLE',
            'label' => 'ชื่อ - สกุล เจ้าของผลงาน',
        ]);
        $form->add_input('PRESENT_ACADEMIC_FNAME_TH', [
            'type' => Form::TYPE_TEXT,
            'holder' => $this->lang_class->label_manual('ชื่อ', 'First Name')
        ]);
        $form->add_input('PRESENT_ACADEMIC_LNAME_TH', [
            'type' => Form::TYPE_TEXT,
            'holder' => $this->lang_class->label_manual('สกุล', 'Last Name')

        ]);
        $form->add_input('PRESENT_ACADEMIC_NAME', [
            'type' => Form::TYPE_TEXT,
            'label' => $this->lang_class->label_manual('ชื่อเรื่อง')

        ]);
        $form->add_input('PRESENT_ACADEMIC_IS_EXPERIENCE', [
            'type' => Form::TYPE_RADIO,
            'label' => 'ประสบการณ์การเป็นอาจารย์ที่ปรึกษาการศึกษาค้นคว้าด้วยตนเอง',
            'data' => ['F' => 'ไม่มี', 'T' => 'มี'],
        ]);
        $form->add_input('PRESENT_ACADEMIC_IS_EXPERIENCE_YEAR', [
            'type' => Form::TYPE_NUMBER,
            'maxlength' => 4,
            'minlength' => 4,
            'label' => 'ระบุครั้งล่าสุด พ.ศ.'
        ]);

        /*
         * 5
         */
        $form->add_input('MORE_RESEARCH_STATUS', [
            'type' => Form::TYPE_RADIO,
            'label' => 'งานวิจัยที่สนใจหรือกำลังดำเนินการ',
            'data' => ['F' => 'ไม่มี', 'T' => 'มี'],
        ]);
        $form->add_input('MORE_RESEARCH_NAME[0]', [
            'type' => Form::TYPE_TEXT,
            'label' =>'5.1',
        ]);
        $form->add_input('MORE_RESEARCH_NAME[1]', [
            'type' => Form::TYPE_TEXT,
            'label' =>'5.2',
            'required' => false,
        ]);

        /*
        * 6
        */
        $form->add_input('AWARD_NAME_STATUS', [
            'type' => Form::TYPE_RADIO,
            'label' => 'รางวัลหรือเกียรติคุณทางการสอน การวิจัยหรือทางวิชาการ ที่เคยได้รับ',
            'data' => ['F' => 'ไม่มี', 'T' => 'มี'],
        ]);
        $form->add_input('AWARD_NAME[0]', [
            'type' => Form::TYPE_TEXT,
            'label' =>'6.1',
        ]);
        $form->add_input('AWARD_YEAR[0]', [
            'type' => Form::TYPE_NUMBER,
            'label' =>'ปีที่ได้รับ',
        ]);
        $form->add_input('AWARD_NAME[1]', [
            'type' => Form::TYPE_TEXT,
            'required' => false,
            'label' =>'6.2',
        ]);
        $form->add_input('AWARD_YEAR[1]', [
            'type' => Form::TYPE_NUMBER,
            'required' => false,
            'label' =>'ปีที่ได้รับ',
        ]);



        return $form;


    }

} 