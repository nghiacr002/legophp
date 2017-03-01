<div class="row">
    <div class="col-xs-12">
        <form method="POST" action="{{ Template_Url('theme/controller/add',{admincp:true})}}" onsubmit="on_submit_controller_adding(this);return false;" id="form-controller-adding">
            <div class="form-group">
                <label for="controler_name"><span class="required">*</span>{{ Translate('theme.controller_name') }}</label>
                <input type="text" name="controller_name" id="controller_name" class="form-control" required/>
            </div>
            <div class="form-group">
                <label for="router_name"><span class="required">*</span>{{ Translate('theme.router') }}</label>
                <div class="input-group">
                <input type="text" name="router_name" id="router_name" class="form-control" required/>
                <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" onclick="parse_url($('#router_name').val());"><i class="fa fa-search"></i></button>
                    </span>
                
                </div>
                <span class="tip">{{ _TL('theme.add_router_tip') }}</span>
                
            </div>
            <div class="form-group">
                <label for="module_name">{{ Translate('theme.module') }}</label>
                <select class="form-control" name="module_name" id="module_name">
                	{%for key,oModule in aModules %}
                		<option value="{{oModule.module_name }}">{{ oModule.module_title}}</option>
                	{% endfor %}
                </select>
            </div>
            <div class="form-group">
                <label for="layout_id">{{ Translate('theme.layout') }}</label>
                <select class="form-control" name="layout_id" id="layout_id">
                	{%for key,oLayout in aLayouts %}
                		<option value="{{ oLayout.layout_id }}">{{ oLayout.layout_title}}</option>
                	{% endfor %}
                </select>
            </div>
            <div class="form-group">
            	<input type="hidden" name="action" value="submit"/>
                <input type="submit" class="btn btn-success" value="{{ Translate('core.submit') }}"/>
            </div>
        </form>
    </div>
</div>
<script>
	var REQUIRE_CHECK_URL = false;
	function parse_url(url){
		if(url.indexOf(CORE.params['sBaseUrl']) < 0 || url.indexOf(CORE.params['sBaseAdminUrl']) === 0){
			bootbox.alert({
				message: "{{ _TL('core.invalid_url_should_only_front_end_internal_url')}}",
				size:'small'
			});
			return false;
		}
		$.ajax({
            url: url,
            data: {},
            type: 'GET',
            dataType: 'JSON',
            headers: {
            	'router-detect':true
            }
        }).done(function (content) {
            if(content){
            	$('#router_name').val(content.module + '.' + content.controller + '.' + content.action);
            	if(REQUIRE_CHECK_URL == true){
            		on_submit_controller_adding($('#form-controller-adding'));
            	}
            }
            REQUIRE_CHECK_URL = false;
        }).error(function (content) {
            if(content.responseJSON){
            	bootbox.alert({
            		message: content.responseJSON.message, 
            		size: 'small'
            	});
            }
            REQUIRE_CHECK_URL = false;
        });
        return false;
	}
    function on_submit_controller_adding(f){
    	var url = $('#router_name').val(); 
    	if(url.indexOf('http') === 0){
    		REQUIRE_CHECK_URL = true;
    		parse_url(url);
    		return false;
    	}
        CORE.formProcessing($('#popup-id-{{ iPopupId }} .modal-dialog'));
        $(f).find('.has-error').removeClass('has-error');
        $(f).find('.error-message').remove();
        $.ajax({
            url: $(f).attr('action'),
            data: $(f).serialize(),
            type: $(f).attr('method'),
            dataType: 'JSON'
        }).done(function (content) {
            CORE.formProcessing($('#popup-id-{{ iPopupId }} .modal-dialog'), false);
            CORE.POPUP.close('{{ iPopupId }}');
            //CORE.reloadWindow();
            CORE.URL.redirect(content.redirect);
        }).error(function (content) {
            CORE.formProcessing($('#popup-id-{{ iPopupId }} .modal-dialog'), false);
            content = content.responseJSON;
            if(content.message){
            	bootbox.alert({
            		message: content.message, 
            		size: 'small'
            	});
            }
            if (content.params) {
                CORE.formMessages(f, content.params);
            }
        });
        return false;
    }
</script>