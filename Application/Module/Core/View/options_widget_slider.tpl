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
	  	<div class="image-slider-holder">
	  		{%if aSetting.value %}
	  			{% for key,sImg in aSetting.value %}
	  				<p class="inline-image">
	  					<a class="js-remove-a" onclick="remove_item(this)" href="javascript:void(0)">
	  						<i class="fa fa-trash"></i></a>
	  						<img src="{{ sImg|image_path('small-square') }}"/><span class="image-label">{{ sImg }}</span><input type="hidden" name="params[{{ key }}][]" value="{{ sImg }}"/></p>
	  			{% endfor %}
	  		{% endif %}
	  	</div>
	  	<div class="file-manager">
	  		<a href="javascript:void(0)" class="btn btn-info btn-xs" onclick="add_media_slider(this);" holder="image-slider-holder">{{ Translate('core.add_media') }}</a>
	  		<script type="text/javascript">
	  			var key_slider_name = "{{ key }}"; 
	  			function add_media_slider(element)
	  			{
	  				var container = $(element).parent().parent().find('.image-slider-holder'); 
	  				var item = new CAPPEDITOR(element, container);
	  				item.onChooseCallBack = function(list){
	  					
	  					if (list.length > 0) {
							for (i = 0; i < list.length; i++) {
								var item = list[i];
								var html = '';
								if (typeof item.thumb != 'undefined') {
									html = '<p class="inline-image"><a class="js-remove-a" onclick="remove_item(this)" href="javascript:void(0)"><i class="fa fa-trash"></i></a><img src="' + item.thumb + '"/><span class="image-label">' + item.title + '</span><input type="hidden" name="params['+key_slider_name+'][]" value="'+item.absolute_path+'"/></p>';
								} 
								this.container.append(html);
							}
	  					}
	  				};
	  				CORE.showFileManager(item,'image');
	  			}
	  			function remove_item(element){
	  				var parent = $(element).parent();
	  				bootbox.confirm(_TL('core.are_you_sure'), function(e){
	  					if(result){
	  						parent.remove();	
	  					}
	  				});
	  			}
	  		</script>
	  	</div>
	  {% endif %}
</div>
{% endfor %}
<style>
	.js-remove-a{
		display:none; 
		position: absolute;
		top:3px;
		right:3px;
	}
	.image-slider-holder{
		margin-bottom:10px;
	}
	.image-slider-holder .inline-image:hover .js-remove-a{
		display:block;
	}
 	.image-slider-holder .inline-image{
 		width:80px;
 		background:#fff; 
 		border:1px solid #dfdfdf; 
 		display:inline-block; 
 		margin:3px 5px;
 		position: relative;
 	}
 	.image-slider-holder .inline-image span{
 		background:#efefef;
 		display:block;
 		padding:3px 5px;
 	}
 	.image-slider-holder .inline-image img{
 		width:100%;
 	}
</style>