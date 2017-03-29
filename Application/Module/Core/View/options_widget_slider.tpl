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
	  				<div class="image-holder-item">
	  				<p class="inline-image" style="float:left;">
	  					<a class="js-remove-a" onclick="remove_item(this)" href="javascript:void(0)">
  						<i class="fa fa-trash"></i></a>
  						<img src="{{ sImg.url|image_path('small-square') }}"/>
  						<input type="hidden" name="params[{{ key }}][{{loop.index0}}][url]" value="{{ sImg.url }}"/>
	  				</p>
	  				<div  class="img-content-holder" style="float:left;width:70%;">
	  				<input class="form-control" type="text" value="{{sImg.title}}" name="params[{{ key }}][{{loop.index0}}][title]" placeholder="{{ Translate('core.title')}}"/>
	  				<input class="form-control" type="text" value="{{sImg.link}}" name="params[{{ key }}][{{loop.index0}}][link]" placeholder="{{ Translate('core.link')}}"/>
	  				</div>
	  				<div class="clear"></div>
	  				</div>
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
	  					var l = $('.image-slider-holder .image-holder-item').length;
	  					if (list.length > 0) {
							for (i = 0; i < list.length; i++) {
								var item = list[i];
								var index = l + i; 
								var html = '';
								if (typeof item.thumb != 'undefined') {
									html = '<div class="image-holder-item"> <p class="inline-image" style="float:left;"><a class="js-remove-a" onclick="remove_item(this)" href="javascript:void(0)"><i class="fa fa-trash"></i></a><img src="' + item.thumb + '"/><input type="hidden" name="params['+key_slider_name+']['+index+'][url]" value="'+item.absolute_path+'"/></p>';
									html+='<div class="img-content-holder" style="float:left;width:70%;">';
									html+='<input class="form-control" type="text" value="" name="params['+key_slider_name+']['+index+'][title]" placeholder="{{ Translate('core.title')}}"/>';
									html+='<input class="form-control" type="text" value="" name="params['+key_slider_name+']['+index+'][link]" placeholder="{{ Translate('core.link')}}"/>';
									html+='<div class="clear"></div>';
									html+='</div>';
									html+='<div class="clear"></div>';
									html+='</div>';
								} 
								this.container.append(html);
							}
	  					}
	  				};
	  				CORE.showFileManager(item,'image');
	  			}
	  			function remove_item(element){
	  				var parent = $(element).parent().parent();
	  				bootbox.confirm(_TL('core.are_you_sure'), function(result){
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
 	.img-content-holder input{
 		margin-bottom:5px;
 	}
</style>