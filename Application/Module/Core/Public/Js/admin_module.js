ADMIN_MODULE = {
    init: function () {
        $('.switch-box').bootstrapSwitch({
            size: "small"
        });
    }
};
$(document).ready(function () {
    ADMIN_MODULE.init();
});