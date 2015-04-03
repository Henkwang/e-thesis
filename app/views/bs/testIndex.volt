<form id="{{ formname }}" action="{{ action }}" role="form" class="form-horizontal" method="post">
    {{ input['pk_id'] }}
    <div class="row">
        <div class="form-group">
            <div class="col-xs-12 col-lg-10 col-lg-offset-1">
                {{ input['UPLOAD'] }}
            </div>
        </div>
    </div>
</form>

<script>
    $.material.init();
    {{ valid }}

    $('#{{ formname }} #UPLOAD').bind('change', function () {
//        alert('hhh');
        {#$('#{{ formname }}').formValidation('revalidateField', 'UPLOAD');#}
    });
</script>
