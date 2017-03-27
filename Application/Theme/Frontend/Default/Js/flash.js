FLASH = {
    init: function () {
        $('.content-flash .messages .login-box-msg').each(function (i, e) {
            var id = '#' + $(e).attr('rel');
            var html = $(e).html();
            if ($(e).hasClass('flash-error') && $(id).length > 0) {
                $(id).parent().addClass('has-error');
                $(id).parent().append('<label>' + html + '</label>');
                $(e).remove();
            }
        });
        if ($('.content-flash .messages .login-box-msg').length > 0) {
            $('.content-flash').show();
        } else {
            $('.content-flash').remove();
        }
    }
};
$(document).ready(function () {
    FLASH.init();
});