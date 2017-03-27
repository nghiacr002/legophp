ADMIN_USER = {
    init: function () {
        $('.switch-box').bootstrapSwitch({
            size: "small"
        });
        $('.input_search').on('keyup', function () {
            var _v = $(this).val();
            if (_v.length >= 2) {
                $('.user-row').hide();
                $('.user-title').each(function (i, e) {
                    var _t = $(e).text();
                    if (_t.indexOf(_v) > -1) {
                        $(e).parent().parent().show();
                    }
                });
            } else {
                $('.user-row').show();
            }
        });
        $('#birthday').datepicker();
    }
};
$(document).ready(function () {
    ADMIN_USER.init();
});