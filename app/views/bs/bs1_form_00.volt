<form id="{{ formname }}" action="{{ action }}" role="form" class="form-horizontal" method="post">
{{ input['pk_id'] }}
<div class="row">
    <div class="col-xs-12 col-lg-10 col-lg-offset-1">
        <p class="text-right">บศ.1 : ปรับปรุง 2557</p>

        <div class="text-center">
            <h3>ประวัติอาจารย์บัณฑิตศึกษา/อาจารย์พิเศษบัณฑิตศึกษา</h3>
            <br>
        </div>
        <div class="form-group">
            <div class="col-xs-offset-3 col-xs-4">
                <div class="input-group">
                    {{ labelgroup['ACAD_YEAR'] }}
                    {{ input['ACAD_YEAR'] }}
                </div>
            </div>
            <div class="col-xs-4">
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
                        {{ labelgroup['DIVISION_ID'] }}
                        {{ input['DIVISION_ID'] }}
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
        <br>

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

        <br>
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
                <div class="form-group">
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
                <div class="row" id="row_MORE_RESEARCH_NAME_0">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <div class="input-group">
                                {{ labelgroup['MORE_RESEARCH_NAME[]'] }}
                                {{ input['MORE_RESEARCH_NAME[]'] }}
                                <span class="input-group-addon">
                                    <a class="btn  btn-success btn-link"
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

                <div class="row" id="row_AWARD_0">
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
                                    <a class="btn btn-success btn-link" href="javascript:man_input(0 ,'AWARD')">
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


</div>
</div>
</div>{#div .row#}


<br><br>
<hr>
<div class="form-group">
    <div class="col-xs-offset-5 col-xs-4">
        {{ input['OK'] }}{{ input['RESET'] }}
    </div>
</div>

</form>


<script>
    var num_moreinput = {};
    $(document).ready(function () {
        {{ valid }}
        $.material.init();


        $('#PERSON_ID').on('select2:select', function (e) {
            {#console.log('{{ preurl }}');#}
            $.ajax({
                url: '{{ preurl }}bs/bs1form/dataperson',
                type: "POST",
                dataType: 'json',
                data: "id=" + e.params.data.id,
                success: function (result) {
                    clog(result);
                    $.each(result, function (k, v) {
                        $('#' + k).val(v).change();
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

    });


    function man_input(num, id) {
        var inputnum = (typeof num_moreinput[id] == 'undefined' ? 1 : num_moreinput[id]);
        if (num == 0 && inputnum < 9) {
            var elm = $('#{{ formname }} ' + '#row_' + id + '_0').clone();
            elm.attr('id', 'row_' + id + '_' + inputnum.toString());
            elm.find('a').first().attr('href', "javascript:man_input(" + inputnum.toString() + ",'" + id + "');").removeClass('btn-success').addClass('btn-danger');
            elm.find('a').first().find('i').first().attr('class', 'md-remove-circle');
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