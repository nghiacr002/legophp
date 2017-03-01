MAIN = {
    init: function () {
        $('a.js_confirm').on('click', function (e) {
            var href = $(this).attr('href');
            bootbox.confirm({
                size: "small",
                message: _TL('core.are_you_sure'),
                callback: function (result) {
                    if (result) {
                        window.location.href = href;
                    }
                }
            }
            );
            e.preventDefault();
            return false;
        });
        var hash = document.location.hash;
        if (hash) {
            $('.nav-tabs a[href="' + hash + '"]').tab('show');
            var no_submit = $('.nav-tabs a[href="' + hash + '"]').attr('no-submit');
            if (no_submit == "true") {
                $($('.nav-tabs a[href="' + hash + '"]').parent().parent().attr('btn-submit')).hide();
            } else {
                $($('.nav-tabs a[href="' + hash + '"]').parent().parent().attr('btn-submit')).show();
            }
        }
        $('.nav-tabs a').click(function () {
            var no_submit = $(this).attr('no-submit');
            if (no_submit == "true") {
                $($(this).parent().parent().attr('btn-submit')).hide();
            } else {
                $($(this).parent().parent().attr('btn-submit')).show();
            }
        });
    }
}
$(document).ready(function () {
    MAIN.init();
});