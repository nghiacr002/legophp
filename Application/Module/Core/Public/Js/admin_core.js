$(document).ready(function () {
    $('#update_note').click(function () {
        //.
        $.ajax({
            url: CORE.params['sBaseAdminUrl'] + 'core/note',
            type: 'POST',
            data: $('#update_note_form').serialize(),
        }).done(function (data) {
            bootbox.alert(data);
        });
        return false;
    })
});