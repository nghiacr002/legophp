var debug_item;
var CAPPEDITOR = function(element,container){
	this.element = element; 
	this.container = container; 
};
CAPPEDITOR.prototype.insertContent = function(content){
	console.log(content); 
};
CAPPEDITOR.prototype.onChooseCallBack = null; 

CORE = {
    params: {},
    phrases: {},
    fileManager: null,
    init: function () {
        this.params = CORE_CUSTOM.params;
        this.phrases = CORE_CUSTOM.pharses;
        this._bindSelect();
        $(document).on('focusin', function(e) {
			if ($(e.target).closest(".mce-window").length) {
				e.stopImmediatePropagation();
			}
		});
    },
    phrase: function (txt) {
        if (CORE.phrases[txt]) {
            return CORE.phrases[txt];
        }
        return txt;
    },
    _bindSelect: function () {
        if ($('#select_all').length > 0) {
            $('#select_all').click(function () {
                var _bind = $(this).attr('bind');
                $('input[type="checkbox"].' + _bind).prop('checked', $(this).prop("checked"));
            });
        }
    },
    formProcessing: function (parentCaller, state) {
        if (typeof state == "undefined" || state == true) {
            $(parentCaller).find('form .form-message').remove();
            var _html = '<div class="overlay widget-overlay"><i class="fa fa-refresh fa-spin"></i></div>';
            $(parentCaller).append(_html).addClass('overlay-wrapper');
        } else {
            $(parentCaller).find('.widget-overlay').remove();
            $(parentCaller).removeClass('overlay-wrapper');
        }
    },
    formMessage: function (form, msg, type) {
        $(form).find('.form-message').remove();
        var _html = '<div class="form-message alert alert-' + type + '">' + msg + '</div>';
        $(form).prepend(_html);
    },
    formMessages: function (form, messages) {
        $(form).find('.has-error').removeClass('has-error');
        $(form).find('.error-message').remove();
        for (key in messages) {
            var msg = messages[key];
            $(form).find('#' + key).parent().append('<label class="error-message">' + msg + '</label>');
            $(form).find('#' + key).parent().addClass('has-error');
        }
    },
    formMapValues: function(form){
        var textareas = $(form).find('textarea');
        if (textareas.length > 0 && typeof(tinymce) != "undefined") {
            textareas.each(function (i, e) {
                var id = $(e).attr('id');
                editor = tinymce.get(id);
                if (editor) {
                    $(e).val(editor.getContent());
                }
            });
        }
    },
    editor: function (element, has_media) {
        var opts = {
            selector: element,
            height: 300,
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste imagetools"],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image filemanager",
            content_css: [

            ],
            //convert_urls:true,
            //relative_urls:false,
            relative_urls : false,
            remove_script_host:true
        }
        if (typeof has_media == "undefined" || has_media == true) {
            opts.setup = function (editor) {
                editor.addButton('filemanager', {
                    text: '',
                    tooltip: 'File Manager',
                    icon: 'fa-file-picture-o',
                    onclick: function () {
                        CORE.showFileManager(editor);
                    }
                });
            }
        }
        if (typeof tinymce != "undefined") {
            tinymce.suffix = '.min';
            tinymce.baseURL = CORE.params['sBaseUrl'] + 'Application/Module/Core/Public/Js/tinymce';
            tinymce.init(opts);
        }

    },
    showFileManager: function (editor,itemtype) {
        debug_item = editor;
        
        var dlg = bootbox.dialog({
            size: 'large',
            className: 'file-manager-box',
            message: "<div id='file-manager-holder' class='media-manager'><p><i class=\"fa fa-spin fa-spinner\"></i> Loading...</p></div>",
            title: "Media Explorer",
            buttons: {
                success: {
                    label: "Choose",
                    className: "btn-success",
                    callback: function(){
						var list = CORE.fileManager.getSelectedFiles();
						if(typeof(editor.onChooseCallBack) == "function"){
							return editor.onChooseCallBack.call(editor,list);
						}
						if (list.length > 0) {
							for (i = 0; i < list.length; i++) {
								var item = list[i];
								var html = '';
								if (typeof item.thumb != 'undefined') {
									html = '<p class="inline-image"><img src="' + item.url + '"/><span class="image-label">' + item.title + '</span></p>';
								} else {
									html = '<p class="inline-attachment"><a href="' + item.url + '">' + item.title + '</a></p>';
								}
								editor.insertContent(html);
							}
						}
					}
                }
            }
        });
        var file_url = CORE.params['sBaseUrl'] + 'core/media/browse?mode=explorer';
        if(itemtype){
			file_url += '&type=' + itemtype;
		}
        dlg.init(function(){
            setTimeout(function(){
                CORE.fileManager = $('#file-manager-holder').fxMediaManager({
                url: file_url,
                height: 350,
                views: 'thumbs',
                insertButton: true,
                token: CORE.random(),
                buttonHolder: '.file-manager-box .modal-footer',
                classHeader: '.file-manager-box .modal-title',
                upload: {
                    url: CORE.params['sBaseUrl'] + 'core/media/upload',
                    multiple: true,
                    maxFileCount: 1,
                },
                editor: editor,
                detailPanel: true,
            });
            }, 300);
        });
       
    },
    box: function (url, params, config) {
        CORE.POPUP.open(url, params, config);
    },
    hash: function (txt) {
        var hash = 0, i, chr, len;
        if (txt.length === 0)
            return hash;
        for (i = 0, len = txt.length; i < len; i++) {
            chr = txt.charCodeAt(i);
            hash = ((hash << 5) - hash) + chr;
            hash |= 0; // Convert to 32bit integer
        }
        return hash;
    },
    random: function () {
        return Math.random().toString(36).substring(9) + "" + (new Date()).getTime().toString();
    },
    isInIframe: function () {
        return (window != window.top);
    },
    reloadWindow: function(){
		window.location.reload();
	},
	editor4Code: function(textarea){
		var id = $(textarea).attr('id') + '_ace_' + CORE.random(); 
		var html = '<div id="' + id  + '" class="ace_editor_default"></div>';
		$(html).insertBefore($(textarea));
		$(textarea).hide();
		var editor = ace.edit(id);
		editor.getSession().setValue($(textarea).val());
		$(textarea).data('ace-editor',editor);
		return editor;
	}
};
CORE.URL = {
	build: function(url, params){
		var qs = ""; 
		for(var key in params) {
			var value = params[key];
			qs += encodeURIComponent(key) + "=" + encodeURIComponent(value) + "&";
		}
		if (qs.length > 0){
			qs = qs.substring(0, qs.length-1); 
			url = url + "?" + qs;
		}
		return url;
	},
	redirect: function(url){
		window.location.href = url;
	}
};
CORE.POPUP = {
    dialogs: [],
    open: function (url, params, config) {
        var popup_id = CORE.hash(url) + +(new Date()).getTime();
        var default_params = {
            'popup_id': popup_id,
            'wd': $('body')
        };
        if (typeof (params) == "undefined") {
            params = {};
        }
        var default_config = {
            'size': 'large',
            'height': 350,
            'message': '<div class="text-center popup-holder" id="popup-holder-' + popup_id + '"><i class="fa fa-spin fa-spinner"></i></div>',
            'title': '...',
            'className': '',
            onEscape: function () {
                if (typeof (tinymce) != "undefined") {
                    var textareas = $('#popup-id-' + popup_id).find('textarea');
                    if (textareas.length > 0) {
                        textareas.each(function (i, e) {
                            var id = $(e).attr('id');
                            editor = tinymce.get(id);
                            if (editor) {
                                editor.destroy();
                            }
                        });
                    }
                }
            }
        }
        config = $.extend({}, default_config, config);
        params = $.extend({}, default_params, params);
        var wd = params.wd;
        delete params.wd;
        var dialog_instance = bootbox.dialog(config);
        if (dialog_instance) {
            dialog_instance.init(function () {
                $(dialog_instance).attr('id', 'popup-id-' + popup_id);
            });
            dialog_instance.getWindow = function(){
                return wd;
            }
            
        }
        CORE.POPUP.dialogs.push({
            id: popup_id,
            instance: dialog_instance
        });
        $.ajax({
            url: url,
            data: params,
            method: 'POST',
            dataType: params.type
        }).done(function (content) {
            $('#popup-holder-' + popup_id).html(content);
            $('#popup-holder-' + popup_id).removeClass('text-center');
        }).error(function (content) {
            $('#popup-holder-' + popup_id).html(content);
        });
        return dialog_instance;
    },
    close: function (popup_id) {
        var dialog = this.getDialog(popup_id);
        
        if (dialog) {
            dialog.modal('hide');
            $(dialog).trigger('escape');
        }
    },
    getDialog: function (popup_id) {
        if (CORE.POPUP.dialogs.length > 0) {
            for (i in CORE.POPUP.dialogs) {
                var item = CORE.POPUP.dialogs[i];
                if (item.id == popup_id) {
                    return item.instance;
                }
            }
        }
        return null;
    },
    resize: function (popup_id, size) {
        var cl = "";
        switch (size) {
            case 'small':
                cl = "modal-sm";
                break;
            case "large":
                cl = "modal-lg";
                break;
        }
        $('#popup-id-' + popup_id).find('.modal-dialog').removeClass("modal-sm").removeClass('modal-lg').addClass(cl);
    }
};
$(document).ready(function () {
    CORE.init();
});

_TL = function (txt) {
    return CORE.phrase(txt);
};
function convert_vni(alias)
{
    var str = alias;
    str = str.toLowerCase();
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g, "-");
    str = str.replace(/-+-/g, "-");
    str = str.replace(/^\-+|\-+$/g, "");
    return str;
}
;

jQuery.fn.slug = function (options) {
    var settings = {
        slug: 'slug',
        hide: true,
    };
    if (options) {
        jQuery.extend(settings, options);
    }
    $this = jQuery(this);
    jQuery(document).ready(function () {
        if (settings.hide) {
        }
    });
    makeSlug = function () {
        var slugcontent = $this.val();
        slugcontent = convert_vni(slugcontent);
        var slugcontent_hyphens = slugcontent.replace(/\s/g, '-');
        var finishedslug = slugcontent_hyphens.replace(/[^a-zA-Z0-9\-]/g, '');
        jQuery('input.' + settings.slug).val(finishedslug.toLowerCase());
    };
    jQuery(this).keyup(makeSlug);
    jQuery(this).blur(makeSlug);
    return $this;
};
$.fn.serializeObject = function ()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

window.alert = function (msg, title) {
    if (typeof (title) == "undefined") {
        title = "Alert";
    }
    bootbox.alert({
        size: "small",
        title: title,
        message: msg
    });
};
