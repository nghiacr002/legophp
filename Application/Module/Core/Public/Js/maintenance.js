MAINTENANCE = {
    init: function () {
        $('.folder-row input.selectall').each(function () {
            var _path = $(this).val();
            MAINTENANCE.calculate(_path, this);
        });
        $('.calculate-folder').click(function () {
            $(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
            var _input = $(this).parent().parent().find('input.selectall');
            var _path = _input.val();
            MAINTENANCE.calculate(_path, _input);
        });
        $('.empty-folder').click(function () {
            var _input = $(this).parent().parent().find('input.selectall');
            var _path = _input.val();
            MAINTENANCE.clean(_path, _input);
        });
    },
    clean: function (path, e) {
        $.ajax({
            url: CORE.params['sBaseAdminUrl'] + 'core/maintenance/clean',
            dataType: 'JSON',
            data: {
                path: path,
            },
            method: 'POST'
        }).done(function (data) {
            var _tr = $(e).parent().parent();
            _tr.find('.empty-folder').html('<i class="fa fa-trash"></i>');

            if (data.message == "OK") {
                MAINTENANCE.calculate(path, e);
            }
        }).error(function (data) {
            alert(data);
        });
    },
    calculate: function (path, e) {
        $.ajax({
            url: CORE.params['sBaseAdminUrl'] + 'core/maintenance/calculate',
            dataType: 'JSON',
            data: {
                path: path,
            }
        }).done(function (data) {
            var _tr = $(e).parent().parent();
            _tr.find('.total-file').html(data.total_file);
            _tr.find('.total-size').html(data.total_size);
            _tr.find('.calculate-folder').html('<i class="fa fa-calculator"></i>');
        }).error(function (data) {
            alert(data);
        });
    }
}
$(document).ready(function () {
    MAINTENANCE.init();
})