ADMIN_PAGE = {
    init: function () {
        $('#page_title').slug({});
        CORE.editor('#page_content', true);
        if ($('#page_layout').length > 0 && $('#page-layout-design').length > 0) {
            ADMIN_PAGE.loadLayout();
        }
        $('#page_layout').on('change', function () {
        	bootbox.confirm(_TL('theme.you_will_lost_your_changes_are_you_sure'),function(){
        		ADMIN_PAGE.loadLayout();
        	});        		
        });
        if($('#custom_css').length > 0){
    		editor = CORE.editor4Code('#custom_css');
    		var CSSMode = ace.require("ace/mode/css").Mode;
    		editor.session.setMode(new CSSMode());
    	}
    	if($('#custom_js').length > 0){
    		editor = CORE.editor4Code('#custom_js');
    		var JavaScriptMode = ace.require("ace/mode/javascript").Mode;
    		editor.session.setMode(new JavaScriptMode());
    	}
    	$('#form-page-infor').on('submit',function(){
    		$('textarea').each(function(i,e){
				var editor = $(e).data('ace-editor');
				if(typeof(editor) !="undefined"){
					$(e).val(editor.getSession().getValue());
				}
			});
    	});
    	if($('.switch-box').length > 0){
    		$('.switch-box').bootstrapSwitch({
                size: "small"
            });
        	 $('.switch-box-default').on('switchChange.bootstrapSwitch', function (event, state) {
        		 
        		 ADMIN_PAGE.updatePages('#page-form-holder');
             });
    	}
    },
    loadLayout: function () {
        var lid = $('#page_layout').val();
        var pid = $('#item_id').val();
        var type = "page";
        if($('#item_type').length > 0){
        	type = $('#item_type').val();
        }
        
        var hh = $('#hide_header_layout').val();
        var hf = $('#hide_footer_layout').val();
        var src = CORE.URL.build(CORE.params['sBaseAdminUrl'] + 'theme/layout/design/', {
        	'id': lid, 
        	'item-id':pid, 
        	'item-type': type,
        	'hide-footer': hf,
        	'hide-header': hh,
        });
        $('#page-layout-design iframe').attr('src', src);
    },
    saveDesign: function (pid,callback) {
    	var type = "page";
        if($('#item_type').length > 0){
        	type = $('#item_type').val();
        }
    	var _editor = $('#form-page-infor').find('#page-layout-design iframe').contents().find("#layout-design-editor");
        var layout = {
            id: $('#page_layout').val(),
            pid: pid,
            locations: [],
            footer: 0,
            header: 0,
            item_type:type
        };
        layout.header = _editor.find('.hide_on_this_page.h-header').attr('df');
        layout.footer = _editor.find('.hide_on_this_page.h-footer').attr('df');
       
        var has_widget = false;
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
                has_widget = true;
            });
            layout.locations.push(location);
        });
        if(has_widget <=0){
        	bootbox.confirm({ 
    		  size: "small",
    		  message: _TL('page.are_you_sure_to_save_empty_page'), 
    		  callback: function(result){
    			  if(result){
    				  ADMIN_PAGE._saveDesign(layout,callback);
    			  }
    		  }
    		});
        	
        }else{
        	 ADMIN_PAGE._saveDesign(layout,callback);
        }
        
    },
    _saveDesign: function(layout,callback){
    	CORE.formProcessing($('#form-page-infor').parent());
        $.ajax({
            url: CORE.params['sBaseAdminUrl'] + 'page/design',
            method: 'POST',
            data: layout,
            dataType: 'JSON',
        }).done(function (msg) {
            CORE.formProcessing($('#form-page-infor').parent(), false);
            
            $('#hide_header_layout').val(layout.header);
            $('#hide_footer_layout').val(layout.footer);
            ADMIN_PAGE.loadLayout();
            if(typeof(callback) == "function"){
            	callback.call();
            }else{
            	alert(msg.message);
            }
        }).error(function () {
            CORE.formProcessing($('#form-page-infor').parent(), false);
            alert(msg.message);
        });
    },
    updatePages: function(f){
    	var is_default = 0;
        $('.switch-box-default').each(function (i, e) {
            var state = $(e).bootstrapSwitch('state');
            if (state == true) {
                is_default = $(e).attr('rel');
            }
        });
        $.ajax({
            url: CORE.params['sBaseAdminUrl'] + 'page/updateAll',
            data: {
                is_default: is_default,
            },
            method: 'POST',
            dataType: 'JSON'
        }).done(function () {
            
        }).error(function () {
            
        });
    },
    resetLandingPage: function(){
    	bootbox.confirm({
            size: "small",
            message: _TL('core.are_you_sure'),
            callback: function (result) {
                if (result) {
                    window.location.href = CORE.params['sBaseAdminUrl'] + 'page/resetLandingPage';
                }
            }
        }
        );
    }
}
$(document).ready(function () {
    ADMIN_PAGE.init();
});