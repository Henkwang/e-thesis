<div class="well col-xs-10 col-xs-offset-1">

    <form id="{{ formname }}" role="form" class="form-horizontal" method="post">
        <div class="form-group">
            {{ label['ACAD_YEAR'] }}
            {{ input['ACAD_YEAR'] }}
        </div>

        <div class="form-group">
            {{ label['SEMESTEM_ID'] }}
            {{ input['SEMESTEM_ID'] }}
        </div>

        <div class="form-group">
            {{ label['DATE'] }}
            {{ input['DATE'] }}
        </div>

        <div class="form-group">
            {{ label['FACULTY_ID'] }}
            {{ input['FACULTY_ID'] }}
        </div>

        <div class="form-group">
            {{ label['PROGRAM_ID'] }}
            {{ input['PROGRAM_ID'] }}
        </div>

        <div class="form-group">
            {{ label['CHECKBOX_TEST'] }}
            {{ input['CHECKBOX_TEST'] }}
        </div>
        <div class="form-group">
            {{ label['RADIO_TEST'] }}
            {{ input['RADIO_TEST'] }}
        </div>
        <button type="submit" name="submit" id="submit" class="btn btn-primary btn-raised">ตกลง</button>
    </form>
    <script>
        $(document).ready(function () {
            {{ formvalid }}
        });
    </script>
</div>