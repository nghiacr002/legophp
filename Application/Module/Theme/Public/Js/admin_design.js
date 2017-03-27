ADMIN_DESIGN = {
	init: function(){
		if($('.widget-sortable .frame-item').length > 0){
			$('#widget-list-holders .widget-sortable').sortable({
		        placeholder: "frame-item",
		        connectWith: "#layout-design-editor .frame-wrapper",
                helper: function (e, li) {
                    this.copyHelper = li.clone().insertAfter(li);
                    $(this).data('copied', false);
                    return li.clone();
                },
                stop: function (event,ui) {
                    var copied = $(this).data('copied');
                    if (!copied) {
                        this.copyHelper.remove();
                    }
                    this.copyHelper = null;
                }
		    }).disableSelection();
			$('#layout-design-editor .frame-wrapper').sortable({
                connectWith: "#layout-design-editor .frame-wrapper",
                receive: function (e, ui) {
                    ui.sender.data('copied', true);
                    var _controls = [];
                    _controls.push('<a href="javascript:void(0)" title="' + _TL('core.delete') + '" onclick="ADMIN_DESIGN.removeWidget(this);" class="a-remove"><i class="fa fa-trash"></i></a>');
                    $(ui.item).append('<div class="frame-wrapper-container"></div>');
                    $(ui.item).append('<span class="widget-controls-holder">' + _controls.join('') + '</span>');
                    $(ui.item).addClass('frame-wrapper-child');
                    $(ui.item).droppable({
                        drop: function (event, ui) {
                        	ui.draggable.addClass('drag-to-child');
                        	var ehash = CORE.random();
                        	ui.draggable.attr('ehash',CORE.random());
                        	$(this).find('.frame-wrapper-container').append(ui.draggable[0].outerHTML);
                        	$(this).find('.frame-wrapper-container .frame-item').show().addClass('can-sort');
                        	ui.draggable.remove();
                        	$(this).find('.frame-wrapper-container .frame-item').removeAttr('style','');
                        	if($(this).find('.frame-wrapper-container .frame-item[hash="'+ehash+'"] .widget-controls-holder').length <=0 ){
                        		var _controls = [];
                                _controls.push('<a href="javascript:void(0)" title="' + _TL('core.delete') + '" onclick="ADMIN_DESIGN.removeWidget(this);" class="a-remove"><i class="fa fa-trash"></i></a>');
                        		$(this).find('.frame-wrapper-container .frame-item').append('<span class="widget-controls-holder">' + _controls.join('') + '</span>');
                        	}
                        	$(this).find('.frame-wrapper-container').sortable({
                        		placeholder: "can-sort",
                		        connectWith: "#layout-design-editor .frame-wrapper",
                		        stop: function(event, ui){
                		        	$(this).removeAttr('style','');
                		        },
                		        out:function(event, ui){
                		        	$(ui.item).remove();
                		        }
                        	});
                        }
                    });
                    
                },
                update:function(event,ui){
                	
                },
                stop: function(event,ui){
                }
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
		/*if($('#layout_title').length > 0){
			$('#layout_title').on('keyup',function(){
				var v = $(this).val();
				var n = v.toLowerCase().replace(/[^a-zA-Z0-9]+/g, "") + ".tpl";
				$('#layout_name').val(n);
			});
		}*/
	},
	save: function(f){
		var layout = {
			title: '',
			name: '',
			content: '',
			footer:1,
			header:1,
		}
		layout.title =  $('#layout_title').val();
		layout.name =  $('#layout_name').val();
		layout.header =  $('.hide_on_this_page.h-header').attr('df');
		layout.footer =  $('.hide_on_this_page.h-footer').attr('df');
		$('#footer').val(layout.header);
		$('#header').val(layout.footer);
		
		var location_counter = 1;
		$('#layout-design-editor #page-design-skeleton > .frame-item').each(function(i,e){
			$(e).find('.frame-wrapper-container .frame-item').each(function(i2,e2){
				var t = "{{ Location(" + location_counter +") }}";
				$(e2).html(t);
				location_counter++;
			});
			if($(e).find('.frame-wrapper-container .frame-item').length <= 0){
				var t = "{{ Location(" + location_counter +") }}";
				location_counter++;
				$(e).html(t);
			}
		});
		$('.frame-item').removeClass('sortable').removeClass('ui-droppable').removeClass('ui-sortable-handle');
		$('.frame-item').removeAttr('style');
		$('.frame-item .widget-text').remove();
		$('.widget-controls-holder').remove();
		$(f).find('textarea.hidden-area').remove();
		$(f).append('<textarea name="layout_content" class="hidden-area" style="display:none">' + $('#layout-design-editor').html() + '</textarea>');
        return true;
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
};
$(document).ready(function(){
	ADMIN_DESIGN.init();
});