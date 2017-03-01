MC = {
	isSubmiting:false,
	onEditLayout: function(f){
		if(MC.isSubmiting == true){
			$('textarea').each(function(i,e){
				var editor = $(e).data('ace-editor');
				if(typeof(editor) !="undefined"){
					$(e).val(editor.getSession().getValue());
				}
			});
			return true;
		}
		ADMIN_PAGE.saveDesign($('#item_id').val(),function(){
			MC.isSubmiting = true;
			$(f).submit();
		});
		return false;
	}
}
$(document).ready(function(){
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
});
