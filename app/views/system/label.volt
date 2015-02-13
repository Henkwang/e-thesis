

    <form id="{{ formname }}" role="form" class="form-horizontal" method="post">
        <h3>ระบบภาษา</h3>
        <hr/>
        <div class="form-group">
            {{ label['LBL_NAME'] }}
            {{ input['LBL_NAME'] }}
        </div>
        <h4 class="col-xs-offset-2">ภาษาไทย</h4>

        <div class="form-group">
            {{ label['LBL_GRID_TH'] }}
            {{ input['LBL_GRID_TH'] }}
        </div>
        <div class="form-group">
            {{ label['LBL_BTN_TH'] }}
            {{ input['LBL_BTN_TH'] }}
        </div>
        <div class="form-group">
            {{ label['LBL_FORM_TH'] }}
            {{ input['LBL_FORM_TH'] }}
        </div>
        <h4 class="col-xs-offset-2">ภาษาอังกฤษ</h4>

        <div class="form-group">
            {{ label['LBL_GRID_EN'] }}
            {{ input['LBL_GRID_EN'] }}
        </div>
        <div class="form-group">
            {{ label['LBL_BTN_EN'] }}
            {{ input['LBL_BTN_EN'] }}
        </div>
        <div class="form-group">
            {{ label['LBL_FORM_EN'] }}
            {{ input['LBL_FORM_EN'] }}
        </div>

        <div class="col-xs-offset-2">
            <button type="submit" name="submit" id="submit" class="btn btn-primary btn-raised">ตกลง</button>
        </div>

    </form>
    <script>
        $(document).ready(function () {
            {{ formvalid }}
        });
    </script>
