$.fn.editable.defaults.mode = 'inline';
$(document).ready(function () {
    $('.editable-field').each(function () {
        var pk = $(this).attr('data-pk');
        var name = $(this).attr('id');
        var title = $(this).attr('data-title');
        var type2 = $(this).attr('data-type');
        var type = "";
        switch (type2)
        {
            case 'textbox':
                type = "text";
                break;
            case 'boolean':
                type = "select";
                break;
            default:
                type = type2;
        }
        $(this).attr('data-type', type);
        var _opts = {
            url: CORE.params['sBaseAdminUrl'] + 'core/setting/update',
            type: type,
            pk: pk,
            name: name,
            title: title,
            ajaxOptions: {
                dataType: 'JSON' //assuming json response
            },
            error: function (data) {
                if (typeof data.responseJSON != "undefined" && data.responseJSON.message) {
                    return data.responseJSON.message;
                }
                return 'Service unavailable. Please try later.';
            }
        };
        if (type2 == "boolean") {
            _opts.source = [{value: 0, text: _TL('core.no')}, {value: 1, text: _TL('core.yes')}];
            _opts.showbuttons = false;
            _opts.value = parseInt($(this).html());
            _opts.display = function (value, sourceData) {
                var colors = {"": "gray", 0: "red", 1: "green"},
                        elem = $.grep(sourceData, function (o) {
                            return o.value == value;
                        });
                if (elem.length) {
                    $(this).text(elem[0].text).css("color", colors[value]);
                } else {
                    $(this).empty();
                }
            };
        }
        $(this).editable(_opts);
    });

    $('.input_search').on('keyup', function () {
        var _v = $(this).val();
        if (_v.length >= 2) {
            $('.setting-row').hide();
            $('.setting-title').each(function (i, e) {
                var _t = $(e).text();
                if (_t.indexOf(_v) > -1) {
                    $(e).parent().parent().show();
                }
            });
        } else {
            $('.setting-row').show();
        }
    });
});