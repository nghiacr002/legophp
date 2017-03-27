ADMIN_GROUP = {
    init: function () {
        $('.switch-box').bootstrapSwitch({
            size: "small"
        });
        $('.input_search').on('keyup', function () {
            var _v = $(this).val();
            if (_v.length >= 2) {
                $('.perm-row').hide();
                $('.perm-title').each(function (i, e) {
                    var _t = $(e).text();
                    if (_t.indexOf(_v) > -1) {
                        $(e).parent().parent().show();
                    }
                });
            } else {
                $('.perm-row').show();
            }
        });
    }
};
$(document).ready(function () {
    ADMIN_GROUP.init();
});