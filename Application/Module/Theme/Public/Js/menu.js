
MENU = {
    init: function () {
        $('#menu-holder-editable').nestedSortable({
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
        $('#form-menu-adding').submit(function () {
            var _f = $(this);
            CORE.formProcessing(_f.parent());
            if ($('#menu_id').val() && $('#menu_id').val() > 0) {
                var _url = CORE.params['sBaseAdminUrl'] + 'theme/menu/edit';
            } else {
                var _url = CORE.params['sBaseAdminUrl'] + 'theme/menu/add'
            }
            $.ajax({
                url: _url,
                method: 'POST',
                dataType: 'json',
                data: _f.serialize(),
            }).done(function (data) {
                if (data.menu) {
                    MENU.appendMenuItem(data.menu);
                    if (data.menu.parent_id == 0) {
                        $('#parent_id').append('<option value="' + data.menu.menu_id + '">' + data.menu.menu_name + '</option>');
                    }
                }
                CORE.formProcessing(_f.parent(), false);
                MENU.resetForm(_f);
                if (data.menu.is_updated == 1) {
                    //console.log('#menu-item-' + data.menu.menu_id + ' .menu_content',data.menu.menu_id);
                    $('#menu-item-' + data.menu.menu_id + ' .menu-content')
                            .removeClass('menu-active-mode-0').removeClass('menu-active-mode-1')
                            .addClass('menu-active-mode-' + data.menu.is_active);
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
    insertExistedItem: function(e){
    	var name = $(e).attr('name'); 
    	var urlPage = $(e).attr('url'); 
    	if(name && urlPage){
    		var _url = CORE.params['sBaseAdminUrl'] + 'theme/menu/add';
	       	 $.ajax({
	                url: _url,
	                method: 'POST',
	                dataType: 'json',
	                data: {
	                 menu_name:name, 
	                 parent_id:0,
	                 url:urlPage,
	                 is_active:0,
	                },
	            }).done(function (data) {
	                if (data.menu) {
	                    MENU.appendMenuItem(data.menu);
	                    if (data.menu.parent_id == 0) {
	                        $('#parent_id').append('<option value="' + data.menu.menu_id + '">' + data.menu.menu_name + '</option>');
	                    }
	                }
	            }).error(function (data) {
	               
	            });
    	}
    	return false;
    },
    appendMenuItem: function (item) {
        if ($('menu-item-' + item.menu_id + '').length > 0) {
            return false;
        }
        var _html = [];
        var is_active = (item.is_active == 1) ? 1: 0;
        _html.push('<li class="menu-item menu-active-mode-'+is_active+'" id="menu-item-' + item.menu_id + '">');
        _html.push('<div class="menu-content">');
        _html.push('<span>' + item.menu_name + '</span>');
        _html.push('<span class="pull-right edit-row-menu">');
        _html.push('<a href="javascript:void(0)" class="btn btn-info" title="' + _TL('core.edit') + '" onclick="MENU.edit(' + item.menu_id + ');"><i class="fa fa-pencil"></i></a>');
        _html.push('<a href="javascript:void(0)" class="btn btn-danger" title="' + _TL('core.delete') + '" onclick="MENU.delete(' + item.menu_id + ');"><i class="fa fa-trash"></i></a>');
        _html.push('</span>');
        _html.push('</div>');
        _html.push('</li>');
        if (item.parent_id == 0) {
            $('#menu-holder-editable').append(_html.join(' '));
        } else {
            var _sub = $('#menu-item-' + item.parent_id).find('ol.sub-menu-items');
            if (_sub.length <= 0) {
                $('#menu-item-' + item.parent_id).append('<ol class="sub-menu-items"> ' + _html.join(' ') + ' </ol>');
            } else {
                _sub.append(_html.join(' '));
            }
        }
        $("#menu-item-" + item.menu_id).effect("highlight", {}, 3000);
    },
    saveChanges: function (ele) {
        if (typeof (ele) != "undefined") {
            $(ele).html('<i class= "fa fa-spin fa-refresh"></i>');
        }
        var _ol = $('#menu-holder-editable');
        serialized = _ol.nestedSortable('serialize');
        if (serialized && serialized != "") {
            $.ajax({
                url: CORE.params['sBaseAdminUrl'] + 'theme/menu/updateItems',
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
            url: CORE.params['sBaseAdminUrl'] + 'theme/menu/edit',
            dataType: 'JSON',
            data: {
                id: id,
            }
        }).done(function (data) {
            if (data.menu) {
                for (element in data.menu) {
                    var _v = data.menu[element];

                    if (element == "parent_id") {
                        $('#form-menu-adding #' + element).prop("disabled", true);
                        $('#form-menu-adding #' + element).val(_v);
                    } else if (element == "is_active") {
                        //$('#form-menu-adding #' + element).attr("checked", ( _v == 1) ? "checked": "");

                        $('#is_active').bootstrapSwitch("state", _v);
                    } else {
                        $('#form-menu-adding #' + element).val(_v);
                    }
                }
                $('#box-form-menu h3.box-title').html(_TL('core.edit_menu'));
                $('#switch_form').show();
                $('#tab_1_a').trigger('click');
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
                        url: CORE.params['sBaseAdminUrl'] + 'theme/menu/delete',
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            id: id,
                        }
                    }).done(function (data) {
                        $('#menu-item-' + id).fadeOut(function () {
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
            f = $('#form-menu-adding');
        }
        f.find('input[type="text"]').val("");
        f.find('select').val(0);
        f.find('select').prop("disabled", false);
        f.find('#menu_id').val("");
        $('#box-form-menu h3.box-title').html(_TL('core.add_new_menu'));
        $('#switch_form').hide();
    }
}

$(document).ready(function () {
    MENU.init();
});