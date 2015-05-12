<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 27/3/2558
 * Time: 10:52
 */

namespace EThesis\Controllers\Bs;

require(__BASE_DIR__ . 'app/library/tcpdf/tcpdf.php');


class Bs1rptController extends \Phalcon\Mvc\Controller
{

    var $data_model;


    public function initialize()
    {
        $this->lang_class = new \EThesis\Library\Lang();
        $this->_lang = $this->session->get('lang');
        if ($this->session->get('auth') !== TRUE) {
            die('false');
        }

        //print_r($this->init_class[sess]->get());
    }

    public function indexAction()
    {
//        echo '<pre>';
//        print_r($_SERVER);
//        echo '<a target="_blank" href="/e-thesis/bs/bs1rpt/preview">Preview</a>';


    }

    private function getdata($pk_id)
    {
//        $pk_id = 2051;
        $bs1_model = new \EThesis\Models\Bs\Bs1_master_model();
        $research_model = new \EThesis\Models\Bs\Bs1_research_model();
        $award_model = new \EThesis\Models\Bs\Bs1_award_model();

        $result = $bs1_model->select_by_filter([], ['IN_ID' => $pk_id]);
        if (is_object($result) && $result->RecordCount() > 0) {
            $data = $result->FetchRow();

            $result = $research_model->select_by_filter([], ['BS1_ID' => $pk_id]);
            if (is_object($result) && $result->RecordCount() > 0) {
                while ($row = $result->FetchRow()) {
                    $data['research'][] = [
                        'BS1_RESEARCH_NAME_TH' => $row['BS1_RESEARCH_NAME_TH']
                    ];
                }
            }

            $result = $award_model->select_by_filter([], ['BS1_ID' => $pk_id]);
            if (is_object($result) && $result->RecordCount() > 0) {
                while ($row = $result->FetchRow()) {
                    $data['award'][] = [
                        'BS1_AWARD_NAME_TH' => $row['BS1_AWARD_NAME_TH'],
                        'BS1_AWARD_YEAR' => $row['BS1_AWARD_YEAR'],
                    ];
                }
            }
            return $data;
        }
        return false;
    }

    public function previewAction($pk_id = '')
    {
        $data = $this->getdata($pk_id);

//        echo '<pre>';
//        print_r($data);return;

        if (empty($data)) {
            echo 'ผิดพลาด! ไม่เจอข้อมูลที่ค้นหา';
            return;
        }
        $this->data_model = new \EThesis\Controllers\Ajax\AutocompleteController();
        $mas_title = $this->data_model->get_dataModel('HRD_TITLE');


        $pdf = new \TCPDF('P');
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('EThesis - UP');
        $pdf->SetTitle('ประวัติอาจารย์บัณฑิตศึกษา');
        $pdf->SetSubject('บันทึก ประวัติอาจารย์บัณฑิตศึกษา/อาจารย์พิเศษบัณฑิตศึกษา');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);


        $pdf->SetMargins(10, 30, 10, 10);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage();

        $pdf->Image(__BASE_DIR__ . 'public/resource/img/logo.png', 0, 10, 0, 20, '', '', '', '', '', 'C');

        if (empty($data['PERSON_IMAGE']) || !file_exists(__BASE_DIR__ . 'public/uploads/bs1/pic_person/' . $data['PERSON_IMAGE'])) {
            $pdf->Image(__BASE_DIR__ . 'public/resource/img/blank.jpg', 0, 10, 30, 0, '', '', '', '', '', 'R', false, false, array('LTRB' => array('width' => 0.1)));
        } else {
            $pdf->Image(__BASE_DIR__ . 'public/uploads/bs1/pic_person/' . $data['PERSON_IMAGE'], 0, 10, 30, 0, '', '', '', '', '', 'R', false, false, array('LTRB' => array('width' => 0.1)));
        }

        $pdf->setY($pdf->getY() + 5);

        $h = 6.5;
        $pdf->SetFont('thniramit', '', 16, true);
        $pdf->Cell(0, $h, 'ประวัติอาจารย์บัณฑิตศึกษา/อาจารย์พิเศษบัณฑิตศึกษา ปีการศิกษา ' . $data['ACAD_YEAR'] . ($data['ASEAN_STATUS'] == 'T' ? ' AEC' : ''), 0, 1, 'C');

        $pdf->SetFont('', '', 16);
        $pdf->Cell(50, $h, '', 0, 0, '');
        $pdf->Cell(15, $h, 'ประเภท', 0, 0, '');
        $pdf->SetFont('', 'B', 16);
        $pdf->Cell(0, $h, $this->lang->lang('ADVISER_STATUS', $data['ADVISER_STATUS']), 0, 1, '');


        $pdf->SetFont('', '', 16);
        $pdf->Cell(50, $h, '', 0, 0, '');
        $pdf->Cell(10, $h, 'คณะ', 0, 0, '');
        $pdf->SetFont('', 'B', 16);
        $pdf->Cell(0, $h, $data['FACULTY_NAME_TH'], 0, 1, '');

        $pdf->SetFont('', '', 16);
        $pdf->Cell(50, $h, '', 0, 0, '');
        $pdf->Cell(10, $h, 'สาขา', 0, 0, '');
        $pdf->SetFont('', 'B', 16);
        $pdf->Cell(0, $h, $data['PROGRAM_NAME_TH'], 0, 1, '');

        $pdf->ln();
        $pdf->SetFont('', '', 16);
        $pdf->Cell(95, $h, '', 0, 0, '');
        $pdf->Cell(48, $h, 'รหัสบัตรประจำตัวประชาชน', 0, 0, '');
        $pdf->SetFont('', 'B', 16);
        $pdf->Cell(0, $h, $this->iden_fill($data['CITIZEN_ID']), 0, 1, '');


        $pdf->ln();
        $pdf->SetFont('', 'B', 16);
        $pdf->Cell(48, $h, 'ส่วนที่  1  ข้อมูลส่วนบุคคล', 0, 1, '');


        $pdf->SetFont('', '', 14);
        $pdf->Cell(6, $h, '1.', 0, 0, '');
        $pdf->Cell(20, $h, 'ชื่อ - สกุล', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(0, $h, $mas_title[$data['TITLE_ID']] . " {$data['FNAME_TH']}  {$data['LNAME_TH']}", 0, 1, '');


        $pdf->SetFont('', '', 14);
        $pdf->Cell(6, $h, '', 0, 0, '');
        $pdf->Cell(28, $h, 'ตำแหน่งทางบริหาร', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        if (strlen($data['POS_EXEC_NAME_TH']) > 120) {
            $pdf->SetFont('', 'B', 12);
        } else if (strlen($data['POS_EXEC_NAME_TH']) > 180) {
            $pdf->SetFont('', 'B', 10);
        }
        $pdf->Cell(90, $h, (!empty('POS_EXECUTIVE_ID') ? $data['POS_EXEC_NAME_TH'] : ''), 0, 0, '');
        $pdf->SetFont('', '', 14);
        $pdf->Cell(30, $h, 'ตำแหน่งทางวิชาการ', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(0, $h, (!empty('POS_ACADEMIC_ID') ? $data['POS_ACAD_NAME_TH'] : ''), 0, 1, '');


        $pdf->SetFont('', '', 14);
        $pdf->Cell(6, $h, '', 0, 0, '');
        $pdf->Cell(31, $h, 'สังกัดคณะ/หน่วยงาน', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(70, $h, (!empty('HRD_FACULTY_NAME_TH') ? $data['HRD_FACULTY_NAME_TH'] : ''), 0, 0, '');
        $pdf->SetFont('', '', 14);
        $pdf->Cell(38, $h, 'มหาวิทยาลัย/สถาบัน/อื่นๆ', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(0, $h, (!empty('UNIVERSITY_NAME') ? $data['UNIVERSITY_NAME'] : ''), 0, 1, '');


        $pdf->SetFont('', '', 14);
        $pdf->Cell(6, $h, '', 0, 0, '');
        $pdf->Cell(23, $h, 'โทรศัพท์มือถือ', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(45, $h, (!empty('TEL_PERSONNEL') ? $data['TEL_PERSONNEL'] : ''), 0, 0, '');
        $pdf->SetFont('', '', 14);
        $pdf->Cell(15, $h, 'ที่ทำงาน', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(45, $h, (!empty('TEL_WORK') ? $data['TEL_WORK'] : ''), 0, 0, '');
        $pdf->SetFont('', '', 14);
        $pdf->Cell(8, $h, 'ต่อ', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(0, $h, (!empty('TEL_WORK_NEXT') ? $data['TEL_WORK_NEXT'] : ''), 0, 1, '');


        $pdf->setY($pdf->getY() + ($h / 4));
        $pdf->SetFont('', '', 14);
        $pdf->Cell(6, $h, '2.', 0, 0, '');
        $pdf->Cell(29, $h, 'คุณวุฒิสูงสุดระดับ', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(50, $h, $this->lang->lang('MAX_DEGREE_ID', $data['MAX_DEGREE_ID']), 0, 0, '');
        $pdf->SetFont('', '', 14);
        $pdf->Cell(45, $h, 'วัน เดือน ปี ที่สำเร็จการศึกษา', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(0, $h, view_date($data['MAX_GRADUATE_DATE'], 'mm'), 0, 1, '');


        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(25, $h, 'ระบุข้อมูล', 'LTBR', 0, 'C');
        $pdf->Cell(40, $h, 'หลักสูตร', 'TBR', 0, 'C');
        $pdf->Cell(50, $h, 'สาขาวิชา', 'TBR', 0, 'C');
        $pdf->Cell(45, $h, 'สภาบัน/มหาวิทยาลัย', 'TBR', 0, 'C');
        $pdf->Cell(30, $h, 'ประเทศ', 'TBR', 1, 'C');


        $pdf->SetFont('', 'L', 13);
        $pdf->MultiCell(25, $h * 3, 'ภาษาไทย', 'LBR', 'L', 0, 0);
        $pdf->MultiCell(40, $h * 3, $data['MAX_COURSE_NAME_TH'], 'LBR', 'L', 0, 0);
        $pdf->MultiCell(50, $h * 3, $data['MAX_PROGRAM_NAME_TH'], 'LBR', 'L', 0, 0);
        $pdf->MultiCell(45, $h * 3, $data['MAX_UNIVERSITY_NAME_TH'], 'LBR', 'L', 0, 0);
        $pdf->MultiCell(30, $h * 3, $data['MAX_COUNTRY_NAME_TH'], 'LBR', 'L', 0, 1);


        $pdf->MultiCell(25, $h * 3, 'ภาษาอังกฤษ', 'LBR', 'L', 0, 0);
        $pdf->MultiCell(40, $h * 3, $data['MAX_COURSE_NAME_EN'], 'LBR', 'L', 0, 0);
        $pdf->MultiCell(50, $h * 3, $data['MAX_PROGRAM_NAME_EN'], 'LBR', 'L', 0, 0);
        $pdf->MultiCell(45, $h * 3, $data['MAX_UNIVERSITY_NAME_EN'], 'LBR', 'L', 0, 0);
        $pdf->MultiCell(30, $h * 3, $data['MAX_COUNTRY_NAME_EN'], 'LBR', 'L', 0, 1);


        $pdf->setY($pdf->getY() + ($h / 4));
        $pdf->SetFont('', '', 14);
        $pdf->Cell(6, $h, '2.', 0, 0, '');
        $pdf->Cell(29, $h, 'ประสบการณ์การสอน หรือความเชี่ยวชาญทางวิชาการ', 0, 1, '');

        $pdf->SetFont('', '', 14);
        $sc = substr_count($data['BS1_EXPERIENCE'], PHP_EOL);
        $pdf->MultiCell(6, $h * ($sc > 2 ? $sc : 3), '', '', 'L', 0, 0);
        $pdf->MultiCell(30, $h * ($sc > 2 ? $sc : 3), $data['BS1_EXPERIENCE'], '', 'L', 0, 1);


        $pdf->setY($pdf->getY() + ($h / 4));
        $pdf->SetFont('', '', 14);
        $pdf->Cell(6, $h, '2.', 0, 0, '');
        $pdf->Cell(29, $h, 'ผลงานทางวิชาการ', 0, 1, '');

        $pdf->SetFont('', '', 14);
        $pdf->Cell(10, $h, '4.1', 0, 0, 'R');
        $pdf->Cell(0, $h, ' ผลงานวิจัยที่พิมพ์เผยแพร่หลังสำเร็จการคึกษาตามข้อ 2 (เป็นผลงานที่ทำร่วมกับนิสิตได้)', 0, 1, '');
        $pdf->Cell(10, $h, '', 0, 0, '');
        $pdf->Cell(70, $h, 'และที่มิใช่ส่วนหนึ่งของการศึกษาเพื่อรับปริญญา ', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(0, $h, ($data['PRESENT_ACADEMIC_FOR_GRADUATE'] == 'T' ? '' : 'ไม่') . 'มีผลงานทางวิชาการ', 0, 1, '');
        if ($data['PRESENT_ACADEMIC_FOR_GRADUATE'] == 'T') {
            $pdf->SetFont('', '', 14);
            $pdf->Cell(10, $h, '', 0, 0, '');
            $pdf->Cell(20, $h, 'เผยแพร่โดย  ', 0, 0, '');
            $pdf->SetFont('', 'B', 14);
            $pdf->Cell(30, $h, $this->lang->lang('PRESENT_ACADEMIC_TYPE', $data['PRESENT_ACADEMIC_TYPE']), 0, 0, '');
            $pdf->SetFont('', '', 14);
            $pdf->Cell(23, $h, 'เผยแพร่ปี พ.ศ.', 0, 0, '');
            $pdf->SetFont('', 'B', 14);
            $pdf->Cell(0, $h, $data['PRESENT_ACADEMIC_YEAR'], 0, 1, '');

            $pdf->SetFont('', '', 14);
            $pdf->Cell(10, $h, '', 0, 0, '');
            $pdf->Cell(45, $h, 'ชื่อวารสาร/การประชุมวิชาการ  ', 0, 0, '');
            $pdf->SetFont('', 'B', 14);
            $pdf->Cell(0, $h, $data['PRESENT_ACADEMIC_TYPE_NAME'], 0, 1, '');

            $pdf->SetFont('', '', 14);
            $pdf->Cell(10, $h, '', 0, 0, '');
            $pdf->Cell(13, $h, 'สถานที่  ', 0, 0, '');
            $pdf->SetFont('', 'B', 14);
            $pdf->Cell(60, $h, $data['PRESENT_ACADEMIC_PLACE_NAME'], 0, 0, '');
            $pdf->SetFont('', '', 14);
            $pdf->Cell(13, $h, 'จังหวัด', 0, 0, '');
            $pdf->SetFont('', 'B', 14);
            $pdf->Cell(45, $h, $data['PRESENT_ACADEMIC_PROVINCE_NAME'], 0, 0, '');
            $pdf->SetFont('', '', 14);
            $pdf->Cell(13, $h, 'ประเทศ', 0, 0, '');
            $pdf->SetFont('', 'B', 14);
            $pdf->Cell(35, $h, $data['PRESENT_ACADEMIC_COUNTRY_NAME'], 0, 1, '');

            $pdf->SetFont('', '', 14);
            $pdf->Cell(10, $h, '', 0, 0, '');
            $pdf->Cell(38, $h, 'ชื่อ - สกุล เจ้าของผลงาน', 0, 0, '');
            $pdf->SetFont('', 'B', 14);
            $pdf->Cell(0, $h, $mas_title[$data['PRESENT_ACADEMIC_TITLE_ID']] . " {$data['PRESENT_ACADEMIC_FNAME_TH']}  {$data['PRESENT_ACADEMIC_LNAME_TH']}", 0, 1, '');

            $pdf->SetFont('', '', 14);
            $pdf->Cell(10, $h, '', 0, 0, '');
            $pdf->Cell(13, $h, 'ชื่อเรื่อง', 0, 0, '');
            $pdf->SetFont('', 'B', 14);
            $pdf->Cell(0, $h, $data['PRESENT_ACADEMIC_NAME'], 0, 1, '');
        }

        $pdf->SetFont('', '', 14);
        $pdf->Cell(10, $h, '4.2', 0, 0, 'R');
        $pdf->Cell(95, $h, ' ประสบการณ์การเป็นอาจารย์ที่ปรึกษาการศึกษาค้นคว้าด้วยตนเอง', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(30, $h, ($data['PRESENT_ACADEMIC_IS_EXPERIENCE'] == 'T' ? '' : 'ไม่') . 'มีประสบการณ์', 0, 0, '');
        if ($data['PRESENT_ACADEMIC_IS_EXPERIENCE'] == 'T') {
            $pdf->SetFont('', '', 14);
            $pdf->Cell(23, $h, 'ครั้งล่าสุด พ.ศ.', 0, 0, '');
            $pdf->SetFont('', 'B', 14);
            $pdf->Cell(20, $h, $data['PRESENT_ACADEMIC_IS_EXPERIENCE_YEAR'], 0, 1, '');

            $pdf->Cell(10, $h, '', 0, 0, '');
            $pdf->SetTextColor(0, 0, 255);
            $pdf->SetFont('', 'UB', 14);
            $file = text_encode('bs1/is_file/' . $data['IS_CONTRACT_FILE']);
            $pdf->Cell(0, $h, 'แนบคำส่งแต่งตั้งอาจารย์ที่ปริกษาการศึกษาค้าคว้าด้วยตนเอง', 0, 1, '', 0, $this->url->get('ajax/getfile/get_pdf/' . $file));
            $pdf->SetTextColor(0, 0, 0);
        }


        $pdf->addPage();


        $pdf->SetFont('', '', 14);
        $pdf->Cell(6, $h, '5.', 0, 0, '');
        $pdf->Cell(55, $h, 'งานวิจัยที่สนใจหรือกำลังดำเนินการอยู่', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(50, $h, ($data['MORE_RESEARCH_STATUS'] == 'F' ? 'ไม่มี' : 'มี') . 'งานวิจัยที่สนใจ', 0, 1, '');

        if ($data['MORE_RESEARCH_STATUS'] == 'T') {
            foreach ($data['research'] as $key => $val) {
                $pdf->SetFont('', 'B', 14);
                $pdf->Cell(6, $h, '', 0, 0, '');
                $pdf->Cell(7, $h, '5.' . ($key + 1), 0, 0, '');
                $pdf->Cell(0, $h, $val['BS1_RESEARCH_NAME_TH'], 0, 1, '');
            }
        }

        $pdf->setY($pdf->getY() + ($h / 4));
        $pdf->SetFont('', '', 14);
        $pdf->Cell(6, $h, '6.', 0, 0, '');
        $pdf->Cell(100, $h, 'รางวัลหรือเกียรติคุณทางการสอน การวิจัยหรือทางวิชาการ ที่เคยได้รับ', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(50, $h, ($data['AWARD_NAME_STATUS'] == 'F' ? 'ไม่เคย' : 'เคย') . 'ได้รับรางวัล', 0, 1, '');
        if ($data['AWARD_NAME_STATUS'] == 'T') {
            foreach ($data['award'] as $key => $val) {
                $pdf->SetFont('', 'B', 14);
                $pdf->Cell(6, $h, '', 0, 0, '');
                $pdf->Cell(7, $h, '6.' . ($key + 1), 0, 0, '');
                $pdf->Cell(0, $h, $val['BS1_AWARD_NAME_TH'] .  '   พ.ศ.' . $val['BS1_AWARD_YEAR'], 0, 1, '');
            }
        }

        $pdf->ln();
        $pdf->ln();
        $pdf->SetFont('', 'B', 16);
        $pdf->Cell(0, $h, 'ส่วนที่  2  คณะจัดการเรียนการสอน', 0, 1, '');


        $pdf->setY($pdf->getY() + ($h / 4));
        $pdf->SetFont('', '', 14);
        $pdf->Cell(6, $h, '1.', 0, 0, '');
        $pdf->Cell(90, $h, 'บันทึกการตรวจสอบ วันที่เริ่มทำงาน (เฉพาะอาจารย์บัณฑิตศึกษา)', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(6, $h, '', 0, 0, '');
        $pdf->Cell(0, $h, ($data['ADVISER_STATUS'] == 'A' ? '' : 'ไม่') . 'เป็นอาจารย์บัณฑิตศึกษา', 0, 1, '');
        if ($data['ADVISER_STATUS'] == 'A') {
            $pdf->SetFont('', '', 14);
            $pdf->Cell(6, $h, '', 0, 0, '');
            $pdf->Cell(30, $h, 'ประเภทสัญญาจ้าง', 0, 0, '');
            $pdf->SetFont('', 'B', 14);
            $pdf->Cell(0, $h, $this->lang->lang('ADVISER_TYPE_ID', $data['ADVISER_TYPE_ID']), 0, 1, '');


            $pdf->SetFont('', '', 14);
            $pdf->Cell(6, $h, '', 0, 0, '');
            $pdf->Cell(90, $h, 'ตรวจสอบแล้วเป็นพนักงานมหาวิทยาลัยตามสัญญาจ้างตำแหน่ง', 0, 0, '');
            $pdf->SetFont('', 'B', 14);
            $pdf->Cell(0, $h, $data['CK_POSITION_TH'], 0, 1, '');
            if ($data['ADVISER_TYPE_ID'] == 2) {
                $pdf->SetFont('', '', 14);
                $pdf->Cell(6, $h, '', 0, 0, '');
                $pdf->Cell(0, $h, 'แห่งข้อบังคับมหาวิทยาลัยพะเยา ว่าด้วย การบริหารงานบุคคล สัญญาจ้างตั้งแต่', 0, 1, '');
                $pdf->SetFont('', '', 14);
                $pdf->Cell(6, $h, '', 0, 0, '');
                $pdf->SetFont('', 'B', 14);
                $pdf->Cell(80, $h, 'วันที่ ' . view_date($data['CK_START_DATE'], 'mm') . ' ถึง วันที่ ' . view_date($data['CK_END_DATE'], 'mm'), 0, 0, '');

                $pdf->SetTextColor(0, 0, 255);
                $pdf->SetFont('', 'UB', 14);
                $file = text_encode('bs1/contract_person/' . $data['PERSON_CONTRACT_FILE']);
                $pdf->Cell(0, $h, 'แนบสัญญาจ้าง', 0, 1, '', 0, $this->url->get('ajax/getfile/get_pdf/' . $file));
                $pdf->SetTextColor(0, 0, 0);
            }

        }


        $pdf->setY($pdf->getY() + ($h / 4));
        $pdf->SetFont('', '', 14);
        $pdf->Cell(6, $h, '2.', 0, 0, '');
        $pdf->Cell(0, $h, 'บันทึกการตรวจสอบ คุณสมบัติการแต่งตั้งอาจารย์บัณฑิตสึกษา/อาจารย์พิเศษบัณฑิตศึกษา ได้ตรวจสอบแล้วสามารถแต่งตั้งเป็น', 0, 1, '');


        $pop_data = [
            /*0*/
            'มีคุณวุติไม่ตำกว่าปริญญาโทหรือเทียบเท่า หรือเป็นผู้ตำรงตำแหน่งทางวิชาการไม่ต่ำกว่าผู้ช่วยศาสตร์จารย์ และมีประสบการณ์ด้านการสอนและการทำวิจัยที่มิใช่ส่วนหนึ่งของการศึกษาเพื่อรับปริญญา',
            /*1*/
            'มีคุณวุติไม่ตำกว่าปริญญาเอกหรือเทียบเท่า และยังไม่มีผลงานวิจัยหลังสำเร็จการศึกษา',
            /*2*/
            'มีคุณวุติไม่ตำกว่าปริญญาเอกหรือเทียบเท่า หรือเป็นผู้ตำรงตำแหน่งทางวิชาการไม่ต่ำกว่าผู้ช่วยศาสตร์จารย์ และมี ประสบการณ์ด้านการสอนและการทำวิจัยที่มิใช่ส่วนหนึ่งของการศึกษาเพื่อรับปริญญา',
            /*3*/
            'เป็นอาจารย์ประจำมหาวิทยาลัยพะเยา มีคุณวุติไม่ตำกว่าปริญญาเอกหรือเทียบเท่า หรือเป็นผู้ตำรงตำแหน่งทาง วิชาการไม่ต่ำกว่าผู้ช่วยศาสตร์จารย์ และมีประสบการณ์ด้านการสอนและการทำวิจัยที่มิใช่ส่วนหนึ่งของการศึกษา เพื่อรับปริญญา',
            /*4*/
            'เป็นผู้เชี่ยวชาญเฉพาะ ที่เป็นอาจารย์ประจำมหาวิทยาลัยพะเยา มีความรู้ ความเชี่ยวชาญและประสบการณ์สูงใน สาขาวิชานั้นๆ เป็นที่ยอมรับในหน่วยงานหรือระดับกระทรวงหรือวงการด้านวิชาชืพนั้นๆ เทียบได้ไม่ต่ำกว่าระดับ 9 ตามหลักเกณฑ์และวิธีการที่สำนักงานคณะกรรมการข้าราชการพลเรือนและหน่วยงานที่เกี่ยวข้องกำหนด'
        ];


        $pdf->SetFont('', '', 14);
        $pdf->Cell(15, $h, '2.1  ', 0, 0, 'R');
//        $pdf->Cell(23, $h, 'อาจารย์ผู้สอน', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(40, $h, ($data['POP_INS_ID'] == '-1' ? 'ไม่' : '') . 'แต่งตั้งเป็นอาจารย์ผู้สอน', 0, 0, '');
        if (!($data['POP_INS_ID'] == '-1')) {
            if ($data['POP_INS_ID'] == 1 || $data['POP_INS_ID'] == 2) {
                $pdf->SetFont('', 'B', 14);
                $pdf->Cell(40, $h, 'ระดับปริญญาโท', 0, 1, '');
            } else if ($data['POP_INS_ID'] == 3) {
                $pdf->SetFont('', 'B', 14);
                $pdf->Cell(40, $h, 'ระดับปริญญาเอก ', 0, 1, '');
            }
            $pdf->Cell(15, $h, '', 0, 0, '');
            $pdf->SetFont('', 'B', 14);
            $pdf->Cell(15, $h, 'เนื่องจาก ', 0, 0, '');

            $pdf->SetFont('', '', 14);
            if ($data['POP_INS_ID'] == 1) {
                $pdf->MultiCell(00, $h * 2, $pop_data[0], '', 'J', 0, 1);
            } else if ($data['POP_INS_ID'] == 2) {
                $pdf->MultiCell(00, $h * 2, $pop_data[1], '', 'J', 0, 1);
            } else if ($data['POP_INS_ID'] == 3) {
                $pdf->MultiCell(00, $h, $pop_data[2], '', 'J', 0, 1);
            }
        }


        $pdf->SetFont('', '', 14);
        $pdf->Cell(15, $h, '2.2  ', 0, 0, 'R');
//        $pdf->Cell(, $h, '', 0, 0, '');
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(90, $h, ($data['POP_HEAD_THESIS_ID'] == '-1' ? 'ไม่' : '') . 'แต่งตั้งเป็นประธานที่ปรึกษาวิทยานิพนธ์ ระดับบัณฑิตศึกษา', 0, 1, '');
        if (!($data['POP_HEAD_THESIS_ID'] == '-1')) {
            $pdf->Cell(15, $h, '', 0, 0, '');
            $pdf->SetFont('', 'B', 14);
            $pdf->Cell(15, $h, 'เนื่องจาก ', 0, 0, '');
            $pdf->SetFont('', '', 14);
            if ($data['POP_HEAD_THESIS_ID'] == 1) {
                $pdf->MultiCell(0, $h, $pop_data[3], '', 'J', 0, 1);
            } else if ($data['POP_HEAD_THESIS_ID'] == 2) {
                $pdf->MultiCell(0, $h, $pop_data[4], '', 'J', 0, 1);
            }
        }


        $pdf->SetFont('', '', 14);
        $pdf->Cell(15, $h, '2.3  ', 0, 0, 'R');
        $pdf->SetFont('', 'B', 14);
        $pdf->MultiCell(0, $h * 2, ($data['POP_COM_THESIS_ID'] == '-1' ? 'ไม่' : '') . 'แต่งตั้งเป็นกรรมการที่ปรึกษาวิทยานิพนธ์ ประธานกรรมการพิจารณาโครงร่างวิทยานิพนธ์ กรรมการพิจารณา โครงร่างวิทยานิพนธ์ และกรรมการสอบวิทยานิพนธ์ ระดับบัณฑิตศึกษา', '', 'L', 0, 1);
        if (!($data['POP_COM_THESIS_ID'] == '-1')) {
            $pdf->Cell(15, $h, '', 0, 0, '');
            $pdf->SetFont('', 'B', 14);
            $pdf->Cell(15, $h, 'เนื่องจาก ', 0, 0, '');
            $pdf->SetFont('', '', 14);
            if ($data['POP_COM_THESIS_ID'] == 1) {
                $pdf->MultiCell(0, $h, $pop_data[2], '', 'J', 0, 1);
            } else if ($data['POP_COM_THESIS_ID'] == 2) {
                $pdf->MultiCell(0, $h, $pop_data[4], '', 'J', 0, 1);
            }
        }


        $pdf->SetFont('', '', 14);
        $pdf->Cell(15, $h, '2.4  ', 0, 0, 'R');
        $pdf->SetFont('', 'B', 14);
        $pdf->MultiCell(0, $h, ($data['POP_COM_THESIS_ID'] == '-1' ? 'ไม่' : '') . 'แต่งตั้งเป็นอาจารย์ที่ปรึกษาการศึกษาค้นคว้าด้วยตนเอง', '', 'L', 0, 1);
        if (!($data['POP_COM_THESIS_ID'] == '-1')) {
            $pdf->Cell(15, $h, '', 0, 0, '');
            $pdf->SetFont('', 'B', 14);
            $pdf->Cell(15, $h, 'เนื่องจาก ', 0, 0, '');
            $pdf->SetFont('', '', 14);
            if ($data['POP_COM_THESIS_ID'] == 1) {
                $pdf->MultiCell(0, $h, $pop_data[2], '', 'J', 0, 1);
            }
        }


        $pdf->Output('bs1.pdf', 'I');

    }

    function iden_fill($iden)
    {
        if (strlen($iden) == 13) {
            $fill = $iden[0] . '-' . substr($iden, 1, 4) . '-' . substr($iden, 5, 5) . '-' . substr($iden, 9, 2) . '-' . substr($iden, 12, 1);
        } else {
            $fill = $iden;
        }

        return $fill;
    }


}