ADMIN_THEME = {
    init: function () {
        $('.switch-box').bootstrapSwitch({
            size: "small"
        });
        $('.switch-box-default').on('switchChange.bootstrapSwitch', function (event, state) {

            if (state == true) {
                $('.switch-box-active').bootstrapSwitch('disabled', false);
                var r = $(this).parent().parent().parent().parent();
                r.find('.switch-box-active').bootstrapSwitch('disabled', true);
            } else {

            }
            ADMIN_THEME.updateThemes('#theme-form-holder');
        });
        $('.switch-box-active').on('switchChange.bootstrapSwitch', function (event, state) {
            var r = $(this).parent().parent().parent().parent();
            r.find('.switch-box-default').bootstrapSwitch('disabled', !state);
            ADMIN_THEME.updateThemes('#theme-form-holder');
        });
    },
    updateThemes: function (f) {
        var active_list = [];
        $('.switch-box-active').each(function (i, e) {
            var state = $(e).bootstrapSwitch('state');
            state = (state) ? 1 : 0;
            active_list.push($(e).attr('rel') + '-' + state);
        });
        var is_default = 0;
        $('.switch-box-default').each(function (i, e) {
            var state = $(e).bootstrapSwitch('state');
            if (state == true) {
                is_default = $(e).attr('rel');
            }
        });
        $.ajax({
            url: CORE.params['sBaseAdminUrl'] + 'theme/updateAll',
            data: {
                is_default: is_default,
                is_active: active_list
            },
            method: 'POST',
            dataType: 'JSON'
        }).done(function () {
            DEFAULT_UPDATE = false;
        }).error(function () {
            DEFAULT_UPDATE = false;
        });
    }
}
$(document).ready(function () {
    ADMIN_THEME.init();
});