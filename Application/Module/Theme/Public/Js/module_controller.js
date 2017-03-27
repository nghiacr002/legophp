MC = {
	isSubmiting:false,
	onEditLayout: function(f){
		var _editor = $('#form-page-infor').find('#page-layout-design iframe').contents().find("#layout-design-editor");
		_editor.find('.widget-sortable li.widget-item').each(function (i2, e2) {
			var hash = $(e2).attr('hash'); 
			if(typeof(hash) == "undefined" || hash == ""){
				hash = CORE.random(); 
				$(e2).attr('hash',hash);
			}
		});
		if(MC.isSubmiting == true){
			$('textarea').each(function(i,e){
				var editor = $(e).data('ace-editor');
				if(typeof(editor) !="undefined"){
					$(e).val(editor.getSession().getValue());
				}
			});
			return true;
		}
		
		//ADMIN_PAGE.saveDesign($('#item_id').val());
		ADMIN_PAGE.saveDesign($('#item_id').val(),function(){
			MC.isSubmiting = true;
			$(f).submit();
		});
		return false;
	}
};
$(document).ready(function(){
	/*if($('#custom_css').length > 0){
		editor = CORE.editor4Code('#custom_css');
		var CSSMode = ace.require("ace/mode/css").Mode;
		editor.session.setMode(new CSSMode());
	}
	if($('#custom_js').length > 0){
		editor = CORE.editor4Code('#custom_js');
		var JavaScriptMode = ace.require("ace/mode/javascript").Mode;
		editor.session.setMode(new JavaScriptMode());
	}*/
	//try to add ehash for widget
	
	
});
