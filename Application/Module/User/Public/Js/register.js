REGISTER_FORM = {
	error: "",
	init: function(f){
		$('#registerForm').on('submit',function(){
			if(!REGISTER_FORM._validateUsername($('#user_name').val())){
				$('.login-box-msg').html(REGISTER_FORM.error); 
				return false;
			}	
			return true;
		});
	},
	_validateUsername(user_name) {
	    var error = "";
	    var illegalChars = /\W/; // allow letters, numbers, and underscores
	 
	    if (user_name == "") {
	        error = _TL('user.user_name_cannot_be_empty');
	    } else if ((user_name.length < 4) || (user_name.length > 30)) {
	    	error = _TL('user.user_name_wrong_length');
	 
	    } else if (illegalChars.test(user_name)) {
	        error = _TL('user.user_name_contain_illegal_characters');
	    } 
	    if(error != ""){
	    	REGISTER_FORM.error = error; 
	    	return false;
	    }	    
	    return true;
	}
}
$(document).ready(function(){
	REGISTER_FORM.init();
})