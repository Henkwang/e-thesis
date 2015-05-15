<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 9/2/2558
 * Time: 15:43
 */

return [
    'ERROR_AUTH' => 'หมดเวลาการเชื่อมต่อ กรูณาล็อกอินใหม่',
    'ADD' => 'เพิ่ม',
    'EDIT' => 'แก้ไข',
    'DELETE' => 'ลบ',
    'SEARCH' => 'ค้นหา',
    'BUTTON' => 'จัดการ',
    'OK' => 'ตกลง',
    'RESET' => 'เริ่มใหม่',
    'CLEAR' => 'ล้าง',
    'MANAGE' => 'จัดการ',
    'NAME_ML' => 'ชื่อ-สกุล',

    # LOG
    'LOG_USER' => 'ชื่อผู้ใช้งาน',
    'LOG_PAGE' => 'หน้าใช้งาน',
    'LOG_DATE' => 'เวลาบันทึก',
    'LOG_BROWSER_INFO' => 'เบราเซอร์ที่ใช้',
    'LOG_IP' => 'ไอพี',

    # Group User
    'GRP_NAME_ML' => 'กลุ่มผู้ใช้งาน',
    'GRP_USER_TYPE' => 'ประเภทผู้ใช้เริ่มต้น',
    'GRP_TOPMENU' => 'ระดับเมนูหลัก',

    # USER
    'USR_CODE' => 'รหัสสิทธ์',
    'USR_DISPLAY' => 'ชื่อแสดง',
    'USR_USERNAME' => 'รหัสเข้าใช้งาน',
    'USR_EMAIL' => 'อีเมล์',
    'USR_TEL' => 'เบอร์โทร',
    'USR_TYPE' => [
        'label' => 'ประเภทผู้ใช้',
        'A' => 'ผู้ดูแลระบบ',
        'U' => 'เจ้าหน้าที่',
        'T' => 'อาจารย์',
        'S' => 'นิสิต',
    ],

    # โครงสร้างระบบ
    'MOD_PARENT_ID' => 'โหนดแม่',
    'MOD_NAME_TH' => 'ชื่อรายการ(TH)',
    'MOD_NAME_EN' => 'ชื่อรายการ(EN)',
    'MOD_LEVEL' => 'ระดับรายการ',
    'MOD_ORDER' => 'ลำดับ',
    'MOD_ENABLE' => 'เปีดใช้งาน',
    'ENABLE' => [
        'label' => 'ใช้งาน',
        'T' => 'เปิดใช้งาน',
        'F' => 'ปิดใช้งาน'
    ],
    'ASEAN_STATUS' => [
        'label' => 'ประชาคมอาเซียน',
        'T' => 'ใช่',
        'F' => 'ไม่ใช่'
    ],
    'ADVISER_STATUS' => [
        'label' => 'ประเภทอาจารย์บัณฑิต',
        'A' => 'อาจารย์บัณฑิตศึกษา',
        'S' => 'อาจารย์พิเศษบัณฑิตศึกษา'
    ],
    'ADVISER_TYPE_ID' => [
        'label' => 'ประเภทอาจารย์',
        '1' => 'อาจารย์ประจำ',
        '2' => 'ผู้ทรงคุณวุฒิ',
    ],
    'MOD_URL' => 'เส้นทาง',
    'ACAD_YEAR' => 'ปีการศึกษา พ.ศ.',
    'FACULTY_ID' => 'คณะ',
    'PROGRAM_ID' => 'สาขา',
    'CITIZEN_ID' => 'รหัสประจำตัวประชาชน',
    'MAX_DEGREE_ID' => [
        'label' => 'คุณวุฒิสูงสุด',
        '2' => 'ปริญญาตรี',
        '3' => 'ปริญญาโท',
        '4' => 'ปริญญาเอก',
    ],
    'PROVINCE' => 'จังหวัด',
    'COUNTRY' => 'ประเทศ',
    'PERSON_ID' => 'บุคลากร',


    // บศ 1
    'POS_EXECUTIVE_ID' => 'ตำแหน่งทางบริหาร',
    'POS_ACADEMIC_ID' => 'ตำแหน่งทางวิชาการ',
    'DIVISION_ID' => 'คณะ/หน่วยงาน',
    'HRD_FACULTY_ID' => 'คณะ/หน่วยงาน',
    'UNIVERSITY_NAME' => 'มหาวิทยาลัย/สถาบัน',
    'TEL_PERSONNEL' => 'โทรศัพท์มือถือ',
    'TEL_WORK' => 'เบอร์ที่ทำงาน',
    'TEL_WORK_NEXT' => 'ต่อ',
    'MAX_GRADUATE_DATE' => 'วันที่สำเร็จการศึกษา',
    'MAX_COURSE_NAME_ML' => 'หลักสูตร',
    'MAX_PROGRAM_NAME_ML' => 'สาขาวิชา',
    'MAX_UNIVERSITY_NAME_ML' => 'สถาบัน/มหาวิทยาลัย',
    'MAX_COUNTRY_NAME_ML' => 'ประเทศ',
    'PRESENT_ACADEMIC_YEAR' => 'ระบุปีที่เผยแพร่ พ.ศ.',
    'POP_INS_ID' => [
        'label' => 'คุณสมบัติอาจารย์ผู้สอน',
        '-1' => 'ไม่แต่งตั้ง',
        '1' => 'ระดับปริญญาโท วุฒิ ป.โท',
        '2' => 'ระดับปริญญาโท วุฒิ ป.เอก',
        '3' => 'ระดับปริญญาเอก',
    ],
    'POP_HEAD_THESIS_ID' => [
        'label' => 'ประธานที่ปรึกษาวิทยานิพนธ์',
        '-1' => 'ไม่แต่งตั้ง',
        '1' => 'เป็นอาจารย์ประจำ',
        '2' => 'เป็นผู้เชี่ยวชาญเฉพาะ'
    ],
    'POP_COM_THESIS_ID' => [
        'label' => 'กรรมการที่ปรึกษาวิทยานิพนธ์',
        '-1' => 'ไม่แต่งตั้ง',
        '1' => 'เป็นอาจารย์ประจำ',
        '2' => 'เป็นผู้เชี่ยวชาญเฉพาะ'
    ],
    'POP_INS_IS_ID' => [
        'label' => 'อาจารญ์ที่ปรึกษาการศึกษาค้นคว้าด้วยตัวเอง',
        '-1' => 'ไม่แต่งตั้ง',
        '1' => 'มีคุณวุติไม่ตำกว่าปริญญาเอกหรือเทียบเท่า',
    ],
    'POP_THESIS_ID' => [
        'label' => 'คุณสมบัติที่สามารถแต่งตั้ง',
        '1' => 'อาจารย์ผู้สอนระดับปริญญาโท',
        '2' => 'อาจารย์ผู้สอนระดับปริญญาเอก',
        '3' => 'ประธานที่ปรึกษาวิทยานิพนธ์',
        '4' => 'กรรมการที่ปริกษาวิทยานิพนธ์',
        '5' => 'ประธานกรรมการพิจารณาโครงร่างวิทยานิพนธ์',
        '6' => 'กรรมการพิจารณาโครงร่างวิทยานิพนธ์',
        '7' => 'กรรมการสอบวิทยานิพนธ์',
        '8' => 'อาจารย์ที่ปรึกษาการศึกษาค้นคว้าด้วยตัวเอง',
    ],
    'PRESENT_ACADEMIC_TYPE' => [
        'label' => 'เผยแพร่โดย',
        'M' => 'ตีพิมพ์ในวารสาร',
        'A' => 'เสนอต่อที่ประชุมวิชาการ',
    ],

    /* STAP PROCESS */
    'BS1_PROCESS' => 'ขั้นตอนตำเนินการ',
    'BS1_PROCESS_ORDER' => 'ตำเนินการ',
    'BS1_HIS_STATUS' => [
        'label' => 'สถานะดำเนินการ',
        'F' => '<b style="color: red;">ไม่ผ่าน</b>',
        'T' => '<b style="color: green;">ผ่าน</b>',
    ]



];