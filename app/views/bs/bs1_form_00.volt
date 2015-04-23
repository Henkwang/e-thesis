<form id="{{ formname }}" action="{{ action }}" role="form" class="form-horizontal" method="post">
{{ input['pk_id'] }}
<div class="row">
    <div class="col-xs-12 col-lg-10 col-lg-offset-1">
        <p class="text-right">บศ.1 : ปรับปรุง 2557</p>

        <div class="text-center">
            <h3>ประวัติอาจารย์บัณฑิตศึกษา/อาจารย์พิเศษบัณฑิตศึกษา</h3>
            <br>
        </div>

        <div class="row" id="main_bs1">
            <div class="row-picture" style="position: absolute" id="picprofile">
                <img style="height: 200px;width: auto;border: 1px solid #a4b4bc"/>

                <div class="form-group">
                    <div class="col-xs-12">
                        {{ input['PERSON_IMAGE'] }}
                    </div>
                </div>

            </div>
            <script>
                var url_noimg = base_url + 'public/resource/img/no_image.png';
                $('#{{ formname }} #picprofile img').attr('src', url_noimg);
                $('#{{ formname }} #PERSON_IMAGE').change(function () {
                    var oFReader = new FileReader();
                    oFReader.readAsDataURL(document.getElementById("PERSON_IMAGE").files[0]);
                    oFReader.onload = function (oFREvent) {
                        $('#{{ formname }} #picprofile img').attr('src', oFREvent.target.result);
                    };

                });
            </script>

            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-6">
                    <div class="input-group">
                        {{ labelgroup['ACAD_YEAR'] }}
                        {{ input['ACAD_YEAR'] }}
                        {{ input['pk_id'] }}
                    </div>
                </div>
                <div class="col-xs-offset-3 col-xs-4">
                    <div class="input-group">
                        {{ labelgroup['ASEAN_STATUS'] }}
                        {{ input['ASEAN_STATUS'] }}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-6">
                    <div class="input-group">
                        {{ labelgroup['ADVISER_STATUS'] }}
                        {{ input['ADVISER_STATUS'] }}
                    </div>
                </div>
            </div>
            <script>
                $('#{{ formname }} input[name=ADVISER_STATUS]').change(function () {
                    if ($(this).val() == 'A') {
                        $('#{{ formname }} a[href="#bs1_0071"]').parent().show();
                    } else {
                        $('#{{ formname }} a[href="#bs1_0071"]').parent().hide();
                        if ($('#{{ formname }} a[href="#bs1_0071"]').parent().attr('class') == 'active') {
                            $('#{{ formname }} a[href="#bs1_001"]').click();
                        }
                    }
                });
            </script>


            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-6">
                    <div class="input-group">
                        {{ labelgroup['PERSON_ID'] }}
                        {{ input['PERSON_ID'] }}
                        <span class="input-group-addon">(ถ้ามี)</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-6">
                    <div class="input-group">
                        {{ labelgroup['FACULTY_ID'] }}
                        {{ input['FACULTY_ID'] }}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-6">
                    <div class="input-group">
                        {{ labelgroup['PROGRAM_ID'] }}
                        {{ input['PROGRAM_ID'] }}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-6">
                    <div class="input-group">
                        {{ labelgroup['CITIZEN_ID'] }}
                        {{ input['CITIZEN_ID'] }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<p class="text-center">
    <a onclick="$('#main_bs1').slideUp();$('#main_bs1_up').hide();$('#main_bs1_down').show();" id="main_bs1_up"
       class="btn btn-fab btn-fab-mini"><i class="md-keyboard-arrow-up"></i></a>
    <a style="display: none"
       onclick="$('#main_bs1').slideDown();$('#main_bs1_down').hide();$('#main_bs1_up').show();"
       id="main_bs1_down" class="btn btn-fab btn-fab-mini"><i class="md-keyboard-arrow-down"></i></a>
</p>

<hr/>

<div class="row" style="min-height: 100%">
<div class="col-lg-12" id="tab_bs1">
<ul class="nav nav-tabs">
    <li class="active"><a href="#bs1_001" data-toggle="tab">ข้อมูลส่วนบุคคล</a></li>
    <li class=""><a href="#bs1_002" data-toggle="tab">คุณวุฒิสูงสุด</a></li>
    <li class=""><a href="#bs1_003" data-toggle="tab">ประสบการณ์หรือความเชี่ยวชาญ</a></li>
    <li class=""><a href="#bs1_004" data-toggle="tab">ผลงานทางวิชาการ</a></li>
    <li class=""><a href="#bs1_005" data-toggle="tab">งานวิจัยที่สนใจหรือตำเนินอยู่</a></li>
    <li class=""><a href="#bs1_006" data-toggle="tab">รางวัลหรือเกียรติคุณ</a></li>
    <li class="" style="display: none"><a href="#bs1_0071" data-toggle="tab">สัญญาจ้าง</a></li>
    <li class=""><a href="#bs1_0072" data-toggle="tab">คุณสมบัติ</a></li>
    <li class=""><a href="#bs1_END" data-toggle="tab">บันทึกข้อมูล</a></li>
</ul>


<div id="tab_bs1_content" class="tab-content">


{#ข้อ 1#}
<div class="tab-pane fade active in" id="bs1_001">

    <br>
    <h4 class="text-left">1. ข้อมูลส่วนบุคคล</h4>
    <br><br>

    <div class="col-xs-12 col-lg-10 col-lg-offset-1">

        <div class="row">
            <div class="col-xs-2">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['TITLE_ID'] }}
                        {{ input['TITLE_ID'] }}
                    </div>
                </div>
            </div>
            <div class="col-xs-5">
                <div class="form-group">
                    {{ input['FNAME_TH'] }}
                </div>
            </div>
            <div class="col-xs-5">
                <div class="form-group">
                    {{ input['LNAME_TH'] }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['POS_EXECUTIVE_ID'] }}
                        {{ input['POS_EXECUTIVE_ID'] }}
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['POS_ACADEMIC_ID'] }}
                        {{ input['POS_ACADEMIC_ID'] }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['HRD_FACULTY_ID'] }}
                        {{ input['HRD_FACULTY_ID'] }}
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['UNIVERSITY_NAME'] }}
                        {{ input['UNIVERSITY_NAME'] }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-5">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['TEL_PERSONNEL'] }}
                        {{ input['TEL_PERSONNEL'] }}
                    </div>
                </div>
            </div>
            <div class="col-xs-5">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['TEL_WORK'] }}
                        {{ input['TEL_WORK'] }}
                    </div>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['TEL_WORK_NEXT'] }}
                        {{ input['TEL_WORK_NEXT'] }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{#ข้อ 2#}
<div class="tab-pane fade" id="bs1_002">
    <br>
    <h4 class="text-left">2. ข้อมูลคุณวุฒิสูงสุดจที่สำเร็จการศึกษา</h4>
    <br><br>

    <div class="col-xs-12 col-lg-10 col-lg-offset-1">
        <div class="row">
            <div class="col-xs-7">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['MAX_DEGREE_ID'] }}
                        {{ input['MAX_DEGREE_ID'] }}
                    </div>
                </div>
            </div>
            <div class="col-xs-5">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['MAX_GRADUATE_DATE'] }}
                        {{ input['MAX_GRADUATE_DATE'] }}
                    </div>
                </div>
            </div>
        </div>

        <h5 class="text-left" style="font-weight: bold;text-decoration: underline;">คุณวุฒิสูงสุด ภาษาไทย</h5>

        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['MAX_COURSE_NAME_TH'] }}
                        {{ input['MAX_COURSE_NAME_TH'] }}
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['MAX_PROGRAM_NAME_TH'] }}
                        {{ input['MAX_PROGRAM_NAME_TH'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['MAX_UNIVERSITY_NAME_TH'] }}
                        {{ input['MAX_UNIVERSITY_NAME_TH'] }}
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['MAX_COUNTRY_NAME_TH'] }}
                        {{ input['MAX_COUNTRY_NAME_TH'] }}
                    </div>
                </div>
            </div>
        </div>

        <h5 class="text-left" style="font-weight: bold;text-decoration: underline;">คุณวุฒิสูงสุด ภาษาอังกฤษ</h5>

        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['MAX_COURSE_NAME_EN'] }}
                        {{ input['MAX_COURSE_NAME_EN'] }}
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['MAX_PROGRAM_NAME_EN'] }}
                        {{ input['MAX_PROGRAM_NAME_EN'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['MAX_UNIVERSITY_NAME_EN'] }}
                        {{ input['MAX_UNIVERSITY_NAME_EN'] }}
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['MAX_COUNTRY_NAME_EN'] }}
                        {{ input['MAX_COUNTRY_NAME_EN'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{#ข้อ 3#}
<div class="tab-pane fade" id="bs1_003">
    <br>

    <div class="col-xs-12">
        <h4 class="text-left">3. ประสบการณ์การสอนหรือความเชี่ยวชาญทางวิชาการ</h4>
    </div>
    <br>

    <div class="col-xs-10 col-xs-offset-1">
        <div class="form-group">
            {{ input['BS1_EXPERIENCE'] }}
        </div>
    </div>
</div>


{#ข้อ 4#}
<div class="tab-pane fade" id="bs1_004">
    <br>

    <div class="col-xs-12">
        <h4 class="text-left">4. ผลงานทางวิชาการ</h4>
    </div>
    <br>

    <div class="row">
        <div class="col-xs-1">
            <p class="text-right"> 4.1</p>
        </div>
        <div class="col-xs-10">
            <span class="text-left">ผลงานวิจัยที่พิมพ์เผยแพร่หลังสำเร็จการศึกษาตามข้อ 2 (เป็นผลงานที่ทำร่วมกับนิสิตได้)</span>
        </div>
    </div>

    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12 col-lg-10 col-lg-offset-1">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <div class="input-group">
                                {{ labelgroup['PRESENT_ACADEMIC_FOR_GRADUATE'] }}
                                {{ input['PRESENT_ACADEMIC_FOR_GRADUATE'] }}
                            </div>
                        </div>
                    </div>
                </div>

                <div id="PRE_ACAD" style="display: none">

                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <div class="input-group">
                                    {{ labelgroup['PRESENT_ACADEMIC_TYPE'] }}
                                    {{ input['PRESENT_ACADEMIC_TYPE'] }}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <div class="input-group">
                                    {{ labelgroup['PRESENT_ACADEMIC_YEAR'] }}
                                    {{ input['PRESENT_ACADEMIC_YEAR'] }}
                                    <span class="input-group-addon">ล่าสุด</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="input-group">
                                    {{ labelgroup['PRESENT_ACADEMIC_TYPE_NAME'] }}
                                    {{ input['PRESENT_ACADEMIC_TYPE_NAME'] }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-5">
                            <div class="form-group">
                                <div class="input-group">
                                    {{ labelgroup['PRESENT_ACADEMIC_PLACE_NAME'] }}
                                    {{ input['PRESENT_ACADEMIC_PLACE_NAME'] }}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <div class="input-group">
                                    {{ labelgroup['PRESENT_ACADEMIC_PROVINCE_NAME'] }}
                                    {{ input['PRESENT_ACADEMIC_PROVINCE_NAME'] }}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <div class="input-group">
                                    {{ labelgroup['PRESENT_ACADEMIC_COUNTRY_NAME'] }}
                                    {{ input['PRESENT_ACADEMIC_COUNTRY_NAME'] }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-3">
                            <div class="form-group">
                                <div class="input-group">
                                    {{ labelgroup['PRESENT_ACADEMIC_TITLE_ID'] }}
                                    {{ input['PRESENT_ACADEMIC_TITLE_ID'] }}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-5">
                            <div class="form-group">
                                {{ input['PRESENT_ACADEMIC_FNAME_TH'] }}
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                {{ input['PRESENT_ACADEMIC_LNAME_TH'] }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="input-group">
                                    {{ labelgroup['PRESENT_ACADEMIC_NAME'] }}
                                    {{ input['PRESENT_ACADEMIC_NAME'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <br>

            <div class="col-xs-1">
                <p class="text-right" style="margin-bottom: 0px;margin-top:7px"> 4.2</p>
            </div>
            <div class="col-xs-5">
                <div class="form-group">
                    <div class="input-group">
                        {{ labelgroup['PRESENT_ACADEMIC_IS_EXPERIENCE'] }}
                        {{ input['PRESENT_ACADEMIC_IS_EXPERIENCE'] }}
                    </div>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group" id="PRE_EXPERIENCE" style="display: none">
                    <div class="input-group">
                        {{ labelgroup['PRESENT_ACADEMIC_IS_EXPERIENCE_YEAR'] }}
                        {{ input['PRESENT_ACADEMIC_IS_EXPERIENCE_YEAR'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{#ข้อ 5#}
<div class="tab-pane fade" id="bs1_005">
    <br>

    <div class="col-xs-12">
        <h4 class="text-left">5. งานวิจัยที่สนใจหรือกำลังดำเนินการ</h4>
    </div>
    <br>

    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12 col-lg-10 col-lg-offset-1">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <div class="input-group">
                                {{ labelgroup['MORE_RESEARCH_STATUS'] }}
                                {{ input['MORE_RESEARCH_STATUS'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="row_MORE_RESEARCH_NAME_0" style="display: none">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <div class="input-group">
                                {{ labelgroup['MORE_RESEARCH_NAME[]'] }}
                                {{ input['MORE_RESEARCH_NAME[]'] }}
                                <span class="input-group-addon">
                                    <a class="btn btn-success btn-flat"
                                       href="javascript:man_input(0 ,'MORE_RESEARCH_NAME')">
                                        <i class="md-add-circle"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>



{#ข้อ 6#}
<div class="tab-pane fade" id="bs1_006">
    <br>

    <div class="col-xs-12">
        <h4 class="text-left">6. รางวัลหรือเกียรติคุณทางการสอน การวิจัยหรือทางวิชาการ</h4>
    </div>
    <br>

    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12 col-lg-10 col-lg-offset-1">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <div class="input-group">
                                {{ labelgroup['AWARD_NAME_STATUS'] }}
                                {{ input['AWARD_NAME_STATUS'] }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="row_AWARD_0" style="display: none">
                    <div class="col-xs-8">
                        <div class="form-group">
                            <div class="input-group">
                                {{ labelgroup['AWARD_NAME[]'] }}
                                {{ input['AWARD_NAME[]'] }}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <div class="input-group">
                                {{ labelgroup['AWARD_YEAR[]'] }}
                                {{ input['AWARD_YEAR[]'] }}
                                <span class="input-group-addon">
                                    <a class="btn btn-success btn-flat" href="javascript:man_input(0 ,'AWARD')">
                                        <i class="md-add-circle"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



{#ข้อ 7.1#}
<div class="tab-pane fade" id="bs1_0071">
    <br>

    <div class="col-xs-12">
        <h4 class="text-left">บันทึกการตรวจสอบ วันเริ่มทำงาน (เฉพาะอาจารย์บัณฑิตศึกษา)</h4>
    </div>
    <br>

    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12 col-lg-10 col-lg-offset-1">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <div class="input-group">
                                {{ labelgroup['ADVISER_TYPE_ID'] }}
                                {{ input['ADVISER_TYPE_ID'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $('#{{ formname }} input[name=ADVISER_TYPE_ID]').change(function () {
                        if ($(this).val() == 1) {
                            $('#{{ formname }} #CK_AVD').hide();
                        } else {
                            $('#{{ formname }} #CK_AVD').show();
                        }
                    });
                </script>
                <div id="CK_AVD" style="display: none">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="input-group">
                                    {{ labelgroup['CK_POSITION_ID'] }}
                                    {{ input['CK_POSITION_ID'] }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="input-group">
                                    {{ labelgroup['CK_START_DATE'] }}
                                    {{ input['CK_START_DATE'] }}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="input-group">
                                    {{ labelgroup['CK_END_DATE'] }}
                                    {{ input['CK_END_DATE'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="input-group">
                                    {{ labelgroup['PERSON_CONTRACT_FILE'] }}
                                    {{ input['PERSON_CONTRACT_FILE'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




{#ข้อ 7.2#}
<div class="tab-pane fade" id="bs1_0072">
    <br>

    <div class="col-xs-12">
        <h4 class="text-left">บันทึกการตรวจสอบ คุณสมบัติการแต่งตั้งอาจารย์บัณฑิตศึกษา/อาจารย์พิเศษบัณฑิตศึกษา</h4>
    </div>
    <br>

    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12 col-lg-10 col-lg-offset-1">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <div class="input-group">
                                {{ labelgroup['POP_INS_ID'] }}
                                {{ input['POP_INS_ID'] }}
                            </div>
                        </div>
                        <p style="display: none" class="text-primary text-left" id="detail_POP_INS_ID"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <div class="input-group">
                                {{ labelgroup['POP_HEAD_THESIS_ID'] }}
                                {{ input['POP_HEAD_THESIS_ID'] }}
                            </div>
                        </div>
                        <p style="display: none" class="text-primary text-left" id="detail_POP_HEAD_THESIS_ID"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <div class="input-group">
                                {{ labelgroup['POP_COM_THESIS_ID'] }}
                                {{ input['POP_COM_THESIS_ID'] }}
                            </div>
                        </div>
                        <p style="display: none" class="text-primary text-left" id="detail_POP_COM_THESIS_ID"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <div class="input-group">
                                {{ labelgroup['POP_INS_IS_ID'] }}
                                {{ input['POP_INS_IS_ID'] }}
                            </div>
                        </div>
                        <p style="display: none" class="text-primary text-left" id="detail_POP_INS_IS_ID"></p>
                    </div>
                </div>
                {#<div class="row">#}
                {#<div class="col-xs-12">#}
                {#<p style="display: none" class="text-muted text-left" id="pop_POP_THESIS_ID"></p>#}

                {#<div class="form-group">#}
                {#<div class="input-group">#}
                {#{{ labelgroup['POP_THESIS_ID'] }}#}
                {#{{ input['POP_THESIS_ID'] }}#}
                {#</div>#}
                {#</div>#}
                {#</div>#}
                {#</div>#}
                <script>
                    //                    $('[data-toggle="tooltip"]').tooltip();
                    var pop_thesis_detail = [
                        'มีคุณวุติไม่ตำกว่าปริญญาโทหรือเทียบเท่า หรือเป็นผู้ตำรงตำแหน่งทางวิชาการไม่ต่ำกว่าผู้ช่วยศาสตร์จารย์ และมีประสบการณ์ด้านการสอนและการทำวิจัยที่มิใช่ส่วนหนึ่งของการศึกษาเพื่อรับปริญญา',
                        'มีคุณวุติไม่ตำกว่าปริญญาเอกหรือเทียบเท่า และยังไม่มีผลงานวิจัยหลังสำเร็จการศึกษา',
                        'มีคุณวุติไม่ตำกว่าปริญญาเอกหรือเทียบเท่า หรือเป็นผู้ตำรงตำแหน่งทางวิชาการไม่ต่ำกว่าผู้ช่วยศาสตร์จารย์ และมีประสบการณ์ด้านการสอนและการทำวิจัยที่มิใช่ส่วนหนึ่งของการศึกษาเพื่อรับปริญญา',
                        'เป็นอาจารย์ประจำมหาวิทยาลัยพะเยา มีคุณวุติไม่ตำกว่าปริญญาเอกหรือเทียบเท่า หรือเป็นผู้ตำรงตำแหน่งทางวิชาการไม่ต่ำกว่าผู้ช่วยศาสตร์จารย์ และมีประสบการณ์ด้านการสอนและการทำวิจัยที่มิใช่ส่วนหนึ่งของการศึกษาเพื่อรับปริญญา',
                        'เป็นผู้เชี่ยวชาญเฉพาะ ที่เป็นอาจารย์ประจำมหาวิทยาลัยพะเยา มีความรู้ ความเชี่ยวชาญปละประสบการณ์สูงในสาขาวิชานั้นๆ เป็นที่ยอมรับในหน่วยงานหรือระดับกระทรวงหรือวงการด้านวิชาชืพนั้นๆ เทียบได้ไม่ต่ำกว่าระดับ 9 ตามหลักเกณฑ์และวิธีการที่สำนักงานคณะกรรมการข้าราชการพลเรือนและหน่วยงานที่เกี่ยวข้องกำหนด'
                    ];
                    $.each($('#{{ formname }} input[name="POP_THESIS_ID[]"]'), function (k, v) {
                        var ar = '';
                        switch ($(v).val()) {
                            case '1' :
                                ar = '[0,1]';
                                break;
                            case '2' :
                                ar = '[2]';
                                break;
                            case '3' :
                                ar = '[3,4]';
                                break;
                            case '4' :
                                ar = '[3,4]';
                                break;
                            case '5' :
                                ar = '[3,4]';
                                break;
                            case '6' :
                                ar = '[3,4]';
                                break;
                            case '7' :
                                ar = '[3,4]';
                                break;
                            case '8' :
                                ar = '[2]';
                                break;

                        }

                        var ht = ' <a class="text-info" href="javascript:call_detail_pop(' + ar + ')"><i class="md-chat"></i></a>';
                        $(v).parent().after(ht + '<br/>');
                    });
                    function call_detail_pop(arr) {
                        var e = $('#{{ formname }} #pop_POP_THESIS_ID');
                        e.html('');
                        var html = [];
                        for (var i = 0; i < arr.length; i++) {
                            html.push(' - ' + pop_thesis_detail[arr[i]]);
                        }
                        e.html(html.join('<br>'));
                        e.show();
                    }

                    $(document).ready(function () {
                        $('#{{ formname }} input[name="POP_INS_ID"]').change(function () {
                            var e = $('#{{ formname }} #detail_POP_INS_ID');
                            e.show();
                            switch ($(this).val()) {
                                case '1' :
                                    e.html('คุณสมบัติ : มีคุณวุติไม่ตำกว่าปริญญาโทหรือเทียบเท่า หรือเป็นผู้ตำรงตำแหน่งทางวิชาการไม่ต่ำกว่าผู้ช่วยศาสตร์จารย์ และมีประสบการณ์ด้านการสอนและการทำวิจัยที่มิใช่ส่วนหนึ่งของการศึกษาเพื่อรับปริญญา');
                                    break;
                                case '2' :
                                    e.html('คุณสมบัติ : มีคุณวุติไม่ตำกว่าปริญญาเอกหรือเทียบเท่า และยังไม่มีผลงานวิจัยหลังสำเร็จการศึกษา');
                                    break;
                                case '3' :
                                    e.html('คุณสมบัติ : มีคุณวุติไม่ตำกว่าปริญญาเอกหรือเทียบเท่า หรือเป็นผู้ตำรงตำแหน่งทางวิชาการไม่ต่ำกว่าผู้ช่วยศาสตร์จารย์ และมีประสบการณ์ด้านการสอนและการทำวิจัยที่มิใช่ส่วนหนึ่งของการศึกษาเพื่อรับปริญญา');
                                    break;
                                default:
                                    e.hide();
                                    e.html('');
                                    break;
                            }
                        });
                        $('#{{ formname }} input[name="POP_HEAD_THESIS_ID"]').change(function () {
                            var e = $('#{{ formname }} #detail_POP_HEAD_THESIS_ID');
                            e.show();
                            switch ($(this).val()) {
                                case '1' :
                                    e.html('คุณสมบัติ : เป็นอาจารย์ประจำมหาวิทยาลัยพะเยา มีคุณวุติไม่ตำกว่าปริญญาเอกหรือเทียบเท่า หรือเป็นผู้ตำรงตำแหน่งทางวิชาการไม่ต่ำกว่าผู้ช่วยศาสตร์จารย์ ' +
                                    'และมีประสบการณ์ด้านการสอนและการทำวิจัยที่มิใช่ส่วนหนึ่งของการศึกษาเพื่อรับปริญญา');
                                    break;
                                case '2' :
                                    e.html('คุณสมบัติ : เป็นผู้เชี่ยวชาญเฉพาะ ที่เป็นอาจารย์ประจำมหาวิทยาลัยพะเยา มีความรู้ ความเชี่ยวชาญปละประสบการณ์สูงในสาขาวิชานั้นๆ ' +
                                    'เป็นที่ยอมรับในหน่วยงานหรือระดับกระทรวงหรือวงการด้านวิชาชืพนั้นๆ เทียบได้ไม่ต่ำกว่าระดับ 9 ตามหลักเกณฑ์และวิธีการที่สำนักงานคณะกรรมการข้าราชการพลเรือนและหน่วยงานที่เกี่ยวข้องกำหนด');
                                    break;
                                default:
                                    e.hide();
                                    e.html('');
                                    break;
                            }
                        });
                        $('#{{ formname }} input[name="POP_COM_THESIS_ID"]').change(function () {
                            var e = $('#{{ formname }} #detail_POP_COM_THESIS_ID');
                            e.show();
                            switch ($(this).val()) {
                                case '1' :
                                    e.html('คุณสมบัติ : เป็นอาจารย์ประจำมหาวิทยาลัยพะเยา มีคุณวุติไม่ตำกว่าปริญญาเอกหรือเทียบเท่า หรือเป็นผู้ตำรงตำแหน่งทางวิชาการไม่ต่ำกว่าผู้ช่วยศาสตร์จารย์ ' +
                                    'และมีประสบการณ์ด้านการสอนและการทำวิจัยที่มิใช่ส่วนหนึ่งของการศึกษาเพื่อรับปริญญา');
                                    break;
                                case '2' :
                                    e.html('คุณสมบัติ : เป็นผู้เชี่ยวชาญเฉพาะ ที่เป็นอาจารย์ประจำมหาวิทยาลัยพะเยา มีความรู้ ความเชี่ยวชาญปละประสบการณ์สูงในสาขาวิชานั้นๆ ' +
                                    'เป็นที่ยอมรับในหน่วยงานหรือระดับกระทรวงหรือวงการด้านวิชาชืพนั้นๆ เทียบได้ไม่ต่ำกว่าระดับ 9 ตามหลักเกณฑ์และวิธีการที่สำนักงานคณะกรรมการข้าราชการพลเรือนและหน่วยงานที่เกี่ยวข้องกำหนด');
                                    break;
                                default:
                                    e.hide();
                                    e.html('');
                                    break;
                            }
                        });
                        $('#{{ formname }} input[name="POP_INS_IS_ID"]').change(function () {
                            var e = $('#{{ formname }} #detail_POP_INS_IS_ID');
                            e.show();
                            switch ($(this).val()) {
                                case '1' :
                                    e.html('คุณสมบัติ : เป็นอาจารย์ประจำมหาวิทยาลัยพะเยา มีคุณวุติไม่ตำกว่าปริญญาเอกหรือเทียบเท่า หรือเป็นผู้ตำรงตำแหน่งทางวิชาการไม่ต่ำกว่าผู้ช่วยศาสตร์จารย์ ' +
                                    'และมีประสบการณ์ด้านการสอนและการทำวิจัยที่มิใช่ส่วนหนึ่งของการศึกษาเพื่อรับปริญญา');
                                    break;
                                default:
                                    e.hide();
                                    e.html('');
                                    break;
                            }
                        });
                    });
                </script>


            </div>
        </div>
    </div>
</div>

{#ข้อ END#}
<div class="tab-pane fade" id="bs1_END">
    <br>

    <div class="col-xs-12">
        <h4 class="text-left">บันทึกข้อมูล</h4>
    </div>
    <br>

    <div class="form-group">
        <div class="col-xs-offset-5 col-xs-4">
            {{ input['OK'] }}{{ input['RESET'] }}
        </div>
    </div>
</div>


</div>
</div>
</div>{#div .row#}


<br><br>
<hr>

</form>


<script>
    var num_moreinput = {};
    $(':input').change(function () {
        $('#{{ formname }} #OK').removeClass('disable');
    });


    $(document).ready(function () {
        $.material.init();
        {{ valid }}


        $("#{{ formname }}")
                .on('err.form.fv', function (e) {
                    e.preventDefault();
                    alert('กรอกข้อมูลไม่ครบถ้วน กรุณาตรวจสอบ');
                })
                .on('success.form.fv', function (e) {
                    // Prevent form submission
                    e.preventDefault();

                    var $form = $(e.target);
                    var fv = $form.data('formValidation');

                    // Use Ajax to submit form data
                    $.ajax({
                        url: $form.attr('action'),
                        type: 'POST',
                        data: $form.serialize(),
                        dataType: 'json',
                        success: function (result) {
                            // ... Process the result ...
                            if (result.success) {
                                $('#{{ formname }} #pk_id').val(result.pk_id);
                                alert(result.msg);
                            } else {
                                alert(result.msg);
                            }
                        },
                        error: function ($msg) {
                            alert('ผิดพลาด! การกระทำนี้ไม่ถูกต้อง');
                            window.location = base_url + 'main';
                        }
                    });
                });


        $('#PERSON_ID').on('select2:select', function (e) {
            {#console.log('{{ preurl }}');#}
            $.ajax({
                url: '{{ preurl }}bs/bs1form/dataperson',
                type: "POST",
                dataType: 'json',
                data: "id=" + e.params.data.id,
                success: function (result) {
//                    clog(result);
                    $.each(result, function (k, v) {
                        $('#' + k).val(v).change();
                        $('#{{ formname }}').formValidation('revalidateField', k);
                    });
                    $('#{{ formname }} #MAX_DEGREE_ID_v' + result.MAX_DEGREE_ID).click();//                    clog(result);
                }

            });
        });


        $("#AWARD_NAME_STATUS_vF").change(function () {
            var inputnum = (typeof num_moreinput['AWARD'] == 'undefined' ? 1 : num_moreinput['AWARD']);
            for (var i = 0; i < inputnum; i++) {
                $('#{{ formname }} ' + '#row_AWARD_' + i).hide();
            }
        });
        $("#AWARD_NAME_STATUS_vT").change(function () {
            var inputnum = (typeof num_moreinput['AWARD'] == 'undefined' ? 1 : num_moreinput['AWARD']);
            for (var i = 0; i < inputnum; i++) {
                $('#{{ formname }} ' + '#row_AWARD_' + i).show();
            }
        });
        $("#MORE_RESEARCH_STATUS_vF").change(function () {
            var inputnum = (typeof num_moreinput['MORE_RESEARCH_NAME'] == 'undefined' ? 1 : num_moreinput['MORE_RESEARCH_NAME']);
            for (var i = 0; i < inputnum; i++) {
                $('#{{ formname }} ' + '#row_MORE_RESEARCH_NAME_' + i).hide();
            }
        });
        $("#MORE_RESEARCH_STATUS_vT").change(function () {
            var inputnum = (typeof num_moreinput['MORE_RESEARCH_NAME'] == 'undefined' ? 1 : num_moreinput['MORE_RESEARCH_NAME']);
            for (var i = 0; i < inputnum; i++) {
                $('#{{ formname }} ' + '#row_MORE_RESEARCH_NAME_' + i).show();
            }
        });

        // ผลงานวิจัยที่พิมพ์เผยแพร่หลังสำเร็จ
        $('#{{ formname }} input[name="PRESENT_ACADEMIC_FOR_GRADUATE"]').change(function () {
//            console.log(jQuery('#PRESENT_ACADEMIC_YEAR',this));
            if ($(this).val() == 'T') {
                jQuery('#{{ formname }} #PRE_ACAD').show();
            } else {
                jQuery('#{{ formname }} #PRE_ACAD').hide();
            }
        });
        // ผลงานวิจัยที่พิมพ์เผยแพร่หลังสำเร็จ
        $('#{{ formname }} input[name="PRESENT_ACADEMIC_IS_EXPERIENCE"]').change(function () {
            if ($(this).val() == 'T') {
                jQuery('#{{ formname }} #PRE_EXPERIENCE').show();
            } else {
                jQuery('#{{ formname }} #PRE_EXPERIENCE').hide();
            }
        });

    });


    function man_input(num, id) {
        var inputnum = (typeof num_moreinput[id] == 'undefined' ? 1 : num_moreinput[id]);
        if (num == 0 && inputnum < 9) {
            var elm = $('#{{ formname }} ' + '#row_' + id + '_0').clone();
            elm.attr('id', 'row_' + id + '_' + inputnum.toString());
            elm.find('a').first().attr('href', "javascript:man_input(" + inputnum.toString() + ",'" + id + "');").removeClass('btn-success').addClass('btn-danger');
            elm.find('a').first().find('i').first().attr('class', 'md-remove-circle');
            elm.find('input').val('');
            var elmsp = elm.find('div span[class="input-group-addon"]').first();
            elmsp.html((parseFloat(elmsp.text()) + (parseFloat(inputnum) / 10.0)).toFixed(1).toString());
            inputnum++;
            $('#{{ formname }} ' + '#row_' + id + '_0').parent().append(elm);
        } else if (num > 0 && inputnum < 10) {
            inputnum--;
            var elm = $('#{{ formname }} ' + '#row_' + id + '_' + inputnum.toString());
            elm.remove();
        }
        num_moreinput[id] = inputnum;
    }


</script>