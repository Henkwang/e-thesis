<form id="{{ formname }}" action="{{ action }}" role="form" method="post" class="form-horizontal"
      enctype="multipart/form-data"
      method="post">
    {{ input['pk_id'] }}
    <div class="row">
        <div class="col-xs-offset-4 col-xs-4">
            <div class="row">
                <div class="row-picture" id="picprofile">
                    <img style="height: 200px;width: auto;border: 1px solid #a4b4bc"/>

                    <div class="form-group">
                        <div class="col-xs-12">
                            {{ input['UPLOAD'] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <button type="submit" name="add">Add</button>
            </div>
        </div>
    </div>

    <script>
        var url_noimg = base_url + 'public/resource/img/no_image.png';
        $('#{{ formname }} #picprofile img').attr('src', url_noimg);
        $('#{{ formname }} #UPLOAD').change(function () {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("UPLOAD").files[0]);
            oFReader.onload = function (oFREvent) {
                $('#{{ formname }} #picprofile img').attr('src', oFREvent.target.result);
            };
        });
    </script>
</form>



<div class="msg_show_lock">
    <div class="msg_data">
        <div style="background-color: #EFF8FB;border-color: #5bc0de;padding: 20px;width: 95%; margin: auto auto;margin-top:20%;border-left: 3px solid #44c5ed;">
            <div class="progress" style="margin: 0;">
                <div id="loadProgress" class="progress-bar" style="width: 20%"></div>
            </div>
        </div>
    </div>
</div>
<style>
    .msg_show_lock {
        background: #000;
        width: 100%;
        height: 100%;
        z-index: 80000;
        top: 0px;
        left: 0px;
        position: fixed;
        display: none;
        opacity: .8;

    }
</style>

<script>
    $.material.init();
    {{ valid }}

    /* Check Form By Form Validator */
    $("#{{ formname }}")
            .on('err.form.fv', function (e) {
                e.preventDefault();
                alert('กรอกข้อมูลไม่ครบถ้วน กรุณาตรวจสอบ');
            });

    /* Process File upload By jQuery Form Plugin*/
    $('#{{ formname }}').ajaxForm({
        beforeSend: function () {
            $('.msg_show_lock').show();
            $('#loadProgress').css('width', '0%');
        },
        uploadProgress: function (event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            $('#loadProgress').css('width', percentVal);
        },
        success: function () {
            var percentVal = '100%';
            $('#loadProgress').css('width', percentVal);
        },
        complete: function (response) {
            alert(response.msg);
            $('.msg_show_lock').hide();
        }
    });
    /* END*/


</script>
