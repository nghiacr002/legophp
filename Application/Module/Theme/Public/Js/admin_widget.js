ADMIN_WIDGET = {
    init: function () {
        //init sortable
    	if($('#widget-list-holders .widget-sortable').length > 0){
    		$("#widget-list-holders .widget-sortable").sortable({
                connectWith: ".page-location .widget-sortable",
                helper: function (e, li) {
                	if(li.attr('removeondraf') == 1){
                		$(this).data('copied', false);
                		return li.clone();
                	}
                    this.copyHelper = li.clone().insertAfter(li);
                    $(this).data('copied', false);
                    return li.clone();
                },
                stop: function () {
                    var copied = $(this).data('copied');
                    if (!copied) {
                        this.copyHelper.remove();
                    }
                    this.copyHelper = null;
                }
            }).disableSelection();
            $('.page-location .widget-sortable').sortable({
                connectWith: ".page-location .widget-sortable",
                receive: function (e, ui) {
                    ui.sender.data('copied', true);
                    var hp = $(ui.item).attr('hp');
                    var _controls = [];
                    if(hp == 1){
                    	_controls.push('<a href="javascript:void(0)" title="' + _TL('core.edit') + '" onclick="ADMIN_WIDGET.editWidget(this);" class="a-edit"><i class="fa fa-pencil"></i></a>');
                    }
                    _controls.push('<a href="javascript:void(0)" title="' + _TL('core.delete') + '" onclick="ADMIN_WIDGET.removeWidget(this);" class="a-remove"><i class="fa fa-trash"></i></a>');
                    $(ui.item).append('<span class="widget-controls-holder">' + _controls.join('') + '</span>');
                    if(!$(ui.sender).parent().hasClass('page-location')){
                        if(hp == 1){
                        	 $(ui.item).find('.widget-controls-holder > a.a-edit').trigger('click');
                        }else{
                        	var hash = CORE.random(); 
                            $(ui.item).attr('hash',hash);
                        }
                       
                    }
                },

            }).disableSelection();
    	}
    	if($(".hide_on_this_page").length > 0){
    		$('.hide_on_this_page').click(function(){
    			var df = $(this).attr('df');
    			df = parseInt(df);
    			df = df ? 0 : 1; 
    			$(this).attr('df',df);
    			if(df){
    				$(this).html(_TL('page.show_on_this_page'));
    			}else{
    				$(this).html(_TL('page.hide_on_this_page'));
    			}
    		});
    	}
    	if($('.switch-box').length > 0){
    		$('.switch-box').bootstrapSwitch({
                size: "small"
            });
        	 $('.switch-box-default').on('switchChange.bootstrapSwitch', function (event, state) {
                 ADMIN_WIDGET.updateLayouts('#theme-form-holder');
             });
    	}
    	if($('.layout-widget-item-holder').length > 0){
    		$('.layout-widget-item-holder .widget-item').each(function(i,e){
    			var pw = $(e).attr('pw');
    			var added_item = $('#layout-design-editor .widget-item[pw="'+pw+'"]'); 
    			if(added_item.length > 0){
    				$(e).hide();
    			}else{
    				$(e).show();
    			}
    		});
    	}
    	
    },
    updateLayouts: function(f){
    	var is_default = 0;
        $('.switch-box-default').each(function (i, e) {
            var state = $(e).bootstrapSwitch('state');
            if (state == true) {
                is_default = $(e).attr('rel');
            }
        });
        $.ajax({
            url: CORE.params['sBaseAdminUrl'] + 'theme/layout/updateAll',
            data: {
                is_default: is_default,
            },
            method: 'POST',
            dataType: 'JSON'
        }).done(function () {
            
        }).error(function () {
            
        });
    },
    removeWidget: function (e) {
        bootbox.confirm({
            size: "small",
            message: _TL('core.are_you_sure'),
            callback: function (result) {
                if (result) {
                    $(e).parent().parent().hide().attr('remove',1);
                }
            }
        }
        );
    },
    editWidget: function (e) {
        var li = $(e).parent().parent();
        var pw_id = $(li).attr('pw');
        var wid = $(li).attr('wid');
        var hash = $(li).attr('hash');
        var df = $(li).attr('default');
        if((typeof hash == "undefined" )|| hash == ""){
            hash = CORE.random(); 
            $(li).attr('hash',hash);
        }
        var caller = null;
        if (CORE.isInIframe()) {
            caller = window.top.CORE;
        } else {
            caller = CORE;
        }
        
        var url = CORE.params['sBaseAdminUrl'] + 'theme/widget/edit';
        if(typeof DESGIN_LAYOUT_MODE != "undefined" || df == 1){
        	url += "?type=layout";
        }
        var params = {
            pwid: pw_id,
            wid: wid,
            ehash: hash, 
            wd: $('body')
        };
        var config = {
            'title': _TL('theme.edit_widget'),
        };
        config.size = "small";
        caller.box(url, params, config);
    },
    addWidget: function (pid) {
        var url = CORE.params['sBaseAdminUrl'] + 'theme/widget/add';
        var params = {
            'widget_type': 'html'
        };
        var config = {
            'title': _TL('theme.add_new_html_widget')
        };
        CORE.box(url, params, config);
    }, 
    saveDesign: function(f){
    	var _editor = $(f).find("#layout-design-editor");
        var layout = {
            id: $('#layout_id').val(),
            locations: [],
            footer: 1,
            header: 1
        };
        layout.header = _editor.find('.hide_on_this_page.h-header').attr('df');
        layout.footer = _editor.find('.hide_on_this_page.h-footer').attr('df');
        _editor.find('.page-location').each(function (i, e) {
            var location_id = $(this).attr('rel');
            var location = {
                id: location_id,
                widgets: [],
            }
            $(e).find('.widget-sortable li').each(function (i2, e2) {
                var widget_router = $(e2).attr('rel');
                var widget_id = $(e2).attr('wid');
                var hash = $(e2).attr('hash');
                var remove = $(e2).attr('remove');
                if(typeof remove == "undefined"){
                	remove = 0;
                }
                if (typeof widget_id == "undefined") {
                    widget_id = 0;
                }
                var pw_id = $(e2).attr('pw');
                if (typeof pw_id == "undefined") {
                    pw_id = 0;
                }
                location.widgets.push({
                    widget_id: widget_id,
                    pw_id: pw_id,
                    router: widget_router,
                    ehash: hash,
                    remove: remove,
                });
            });
            layout.locations.push(location);
        });
        CORE.formProcessing($(f).parent());
        $.ajax({
            url: $(f).attr('action'),
            method: 'POST',
            data: layout,
            dataType: 'JSON',
        }).done(function (msg) {
            CORE.formProcessing($(f).parent(), false);
            bootbox.alert({
            	size: 'small',
            	message: msg.message,
            	callback: function(){
            		CORE.reloadWindow();
            	}
            });
        }).error(function () {
            CORE.formProcessing($(f).parent(), false);
            alert(msg.message);
        });
        return false;
    }
}
$(document).ready(function () {
    ADMIN_WIDGET.init();
});