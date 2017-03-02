<div class="row">
    <div class="col-xs-12">
        
            {% if aSettings|length > 0 %}
            <form method="POST" action="{{ Template_Url('theme/widget/edit',{admincp:true}) }}" onsubmit="return on_submit_theme_update(this);">
            	{% for key,aSetting in aSettings %}
            		<div class="form-group">
            			  <label>{{ Translate(aSetting.title) }}</label>
            			  {% if aSetting.type == 'text' %}
            			  	 <input type="text" id="params_{{ key }}" class="form-control" name="params[{{ key }}]" value="{{ aSetting.value}}" >
            			  {% endif %}
            			  {% if aSetting.type == 'slider' %}
            			  	<script type="text/javascript">
                                  CORE.POPUP.resize('{{ iPopupId }}','medium');
                                  CORE.editor('#params_{{ key }}');
            			  	</script>
            			  	<div class="image-slider">
            			  		{%if aSetting.value %}
            			  		{% else %}
            			  		{% endif %}
            			  	</div>
            			  	<div class="file-manager">
            			  		<a href="javascript:void(0)" class="btn btn-info btn-xs" onclick="add_media_slider(this);">{{ Translate('core.add_media') }}</a>
            			  		<script type="text/javascript">
            			  			function add_media_slider(element)
            			  			{
            			  				var item = new CAPPEDITOR(element);
            			  				CORE.showFileManager(item);
            			  			}
            			  			
            			  		</script>
            			  	</div>
            			  {% endif %}
            			  {% if aSetting.type == 'editor' %}
            			  	<textarea id="params_{{ key }}" class="form-control" name="params[{{ key }}]">{{ aSetting.value}}</textarea>
            			  	<script type="text/javascript">
                                  CORE.POPUP.resize('{{ iPopupId }}','large');
                                  CORE.editor('#params_{{ key }}');
            			  	</script>
            			  {% endif %}
            		</div>
            	{% endfor %}
            	<div class="form-group">
                <input type="submit" class="btn btn-success" value="{{ Translate('core.submit') }}"/>
                <input type="hidden" name="action" value="submit"/>
                <input type="hidden" name="wid" value="{{ wid }}"/>
                <input type="hidden" name="pwid" value="{{ pwid }}"/>
                <input type="hidden" name="popup_id" value="{{ iPopupId }}"/>
                <input type="hidden" name="ehash" value="{{ sHash }}"/>
            </div>
             </form>
            {% else %}
            	<div class="alert alert-info">{{ Translate('theme.no_options_found') }}</div>
            {% endif %}
            
       
    </div>
</div>
<script>
    function on_submit_theme_update(f)
    {
    	 CORE.formMapValues(f); 
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
         }).error(function (content) {
             CORE.formProcessing($('#popup-id-{{ iPopupId }} .modal-dialog'), false);
             content = content.responseJSON;
             alert(content.message);
             if (content.params) {
                 CORE.formMessages(f, content.params);
             }
         });
         return false;
    }
</script>