function decode_json(text) {
    var json = [];
    try {
        json = jQuery.parseJSON(text);
        return json;
    } catch (e) {
        return []
    }
}

function encode_json(arr) {
    return JSON.stringify(arr);
}

function filter_selected(data, formname, parentname, childname) {
    // jQuery(parent_elm).attr('onchange', 'alert(\'11111\')');


    jQuery("#" + formname + " #" + parentname).change(function () {
        // alert('aaa');
        var val = jQuery(this).val();
        var data_sel = data[val];
        jQuery("#" + formname + " #" + childname).empty();
        $("#" + formname).formValidation('revalidateField', childname);
        jQuery("#" + formname + " #" + childname).append('<option value="">-- กรุณาเลือกข้อมูล --</option>');
        jQuery.each(data_sel, function (k, v) {
            jQuery("#" + formname + " #" + childname).append('<option value="' + k + '">' + v + '</option>');

        });

    });
}


