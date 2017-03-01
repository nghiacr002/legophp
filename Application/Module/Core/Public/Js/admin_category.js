ADMIN_CATEGORY = {
    init: function () {
        $('#category-holder-editable').nestedSortable({
            forcePlaceholderSize: true,
            handle: 'div',
            helper: 'clone',
            items: 'li',
            opacity: .6,
            placeholder: 'placeholder',
            revert: 250,
            tabSize: 25,
            tolerance: 'pointer',
            toleranceElement: '> div',
            maxLevels: 2,
            isTree: true,
            expandOnHover: 700,
            startCollapsed: false,
            change: function () {

            }
        });
        $('#form-category-adding').submit(function () {
            var _f = $(this);
            CORE.formProcessing(_f.parent());
            if ($('#category_id').val() && $('#category_id').val() > 0) {
                var _url = CORE.params['sBaseAdminUrl'] + 'core/category/edit';
            } else {
                var _url = CORE.params['sBaseAdminUrl'] + 'core/category/add'
            }
            $.ajax({
                url: _url,
                method: 'POST',
                dataType: 'json',
                data: _f.serialize(),
            }).done(function (data) {
                if (data.category) {
                    ADMIN_CATEGORY.appendMenuItem(data.category);
                    if (data.category.parent_id == 0) {
                        $('#parent_id').append('<option value="' + data.category.category_id + '">' + data.category.category_name + '</option>');
                    }
                }
                CORE.formProcessing(_f.parent(), false);
                ADMIN_CATEGORY.resetForm(_f);
                if (data.category.is_updated == 1) {
                    //console.log('#menu-item-' + data.menu.menu_id + ' .menu_content',data.menu.menu_id);
                    $('#category-item-' + data.category.category_id + ' .category-content')
                            .removeClass('category-active-mode-0').removeClass('category-active-mode-1')
                            .addClass('category-active-mode-' + data.category.is_active);
                    $('#category-item-' + data.category.category_id + ' .category-content > span:first-child').html(data.category.category_name);
                }
            }).error(function (data) {
                if (data.responseJSON) {
                    data = data.responseJSON;
                }
                CORE.formProcessing(_f.parent(), false);
                CORE.formMessage(_f, data.message, 'danger');
            });
            return false;
        });
        $('#is_active').bootstrapSwitch({
            size: "small"
        });
    },
    appendMenuItem: function (item) {
        if ($('category-item-' + item.category_id + '').length > 0) {
            return false;
        }
        var _html = [];
        _html.push('<li class="category-item" id="category-item-' + item.category_id + '">');
        _html.push('<div class="category-content">');
        _html.push('<span>' + item.category_name + '</span>');
        _html.push('<span class="pull-right edit-row-category">');
        _html.push('<a href="javascript:void(0)" class="btn btn-info" title="' + _TL('core.edit') + '" onclick="ADMIN_CATEGORY.edit(' + item.category_id + ');"><i class="fa fa-pencil"></i></a>');
        _html.push('<a href="javascript:void(0)" class="btn btn-danger" title="' + _TL('core.delete') + '" onclick="ADMIN_CATEGORY.delete(' + item.category_id + ');"><i class="fa fa-trash"></i></a>');
        _html.push('</span>');
        _html.push('</div>');
        _html.push('</li>');
        if (item.parent_id == 0) {
            $('#category-holder-editable').append(_html.join(' '));
        } else {
            var _sub = $('#category-item-' + item.parent_id).find('ol.sub-category-items');
            if (_sub.length <= 0) {
                $('#category-item-' + item.parent_id).append('<ol class="sub-category-items"> ' + _html.join(' ') + ' </ol>');
            } else {
                _sub.append(_html.join(' '));
            }
        }
        $("#category-item-" + item.menu_id).effect("highlight", {}, 3000);
    },
    saveChanges: function (ele) {
        if (typeof (ele) != "undefined") {
            $(ele).html('<i class= "fa fa-spin fa-refresh"></i>');
        }
        var _ol = $('#category-holder-editable');
        serialized = _ol.nestedSortable('serialize');
        if (serialized && serialized != "") {
            $.ajax({
                url: CORE.params['sBaseAdminUrl'] + 'core/category/updateItems',
                method: 'POST',
                dataType: 'JSON',
                data: serialized,
            }).done(function (data) {
                if (data.message) {
                    alert(data.message);
                }
                $(ele).html('<i class= "fa fa-save"></i>');
            }).error(function (data) {
                $(ele).html('<i class= "fa fa-save"></i>');
            });
            return false;
        } else {

        }
    },
    edit: function (id) {
        $.ajax({
            url: CORE.params['sBaseAdminUrl'] + 'core/category/edit',
            dataType: 'JSON',
            data: {
                id: id,
            }
        }).done(function (data) {
            if (data.category) {
                for (element in data.category) {
                    var _v = data.category[element];
                    if (element == "parent_id") {
                        $('#form-category-adding #' + element).prop("disabled", true);
                        $('#form-category-adding #' + element).val(_v);
                    } else if (element == "is_active") {
                        $('#is_active').bootstrapSwitch("state", _v);
                    } else {
                        $('#form-category-adding #' + element).val(_v);
                    }
                }
                $('#box-form-category h3.box-title').html(_TL('core.edit_menu'));
                $('#switch_form').show();
            }
        }).error(function (data) {

        });
    },
    delete: function (id) {
        bootbox.confirm({
            size: "small",
            message: _TL('core.are_you_sure'),
            callback: function (result) {
                if (result) {
                    $.ajax({
                        url: CORE.params['sBaseAdminUrl'] + 'core/category/delete',
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            id: id,
                        }
                    }).done(function (data) {
                        $('#category-item-' + id).fadeOut(function () {
                            $(this).remove();
                        });

                    }).error(function (data) {
                        bootbox.alert(data.message);
                    });
                }
            }
        }
        );
    },
    resetForm: function (f) {
        if (typeof f == "undefined") {
            f = $('#form-category-adding');
        }
        f.find('input[type="text"]').val("");
        f.find('select').val(0);
        f.find('select').prop("disabled", false);
        f.find('#category_id').val("");
        $('#box-form-menu h3.box-title').html(_TL('core.add_new_menu'));
        $('#switch_form').hide();
    }
}

$(document).ready(function () {
    ADMIN_CATEGORY.init();
});