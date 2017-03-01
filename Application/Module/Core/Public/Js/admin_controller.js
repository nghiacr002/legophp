
ADMIN_CONTROLLER = {
    init: function () {
        $('.add-new-controller').on('click',function(e){
        	var url = CORE.params['sBaseAdminUrl'] + 'theme/controller/add';
            var params = {
               
            };
            var config = {
                'title': _TL('theme.add_new_controller'),
                'size': 'small',
            };
            CORE.box(url, params, config);
        	e.preventDefault();
        	return false;
        });
    },
    
}
$(document).ready(function () {
	ADMIN_CONTROLLER.init();
});