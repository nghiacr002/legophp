<div class="row">
    <div class="col-xs-12">
        <form method="POST" action="{{ Template_Url('theme/widget/add',{admincp:true})}}" onsubmit="return on_submit_theme_adding(this);">
            {% if sWidgetType == "html" %}
            <input type="hidden" name="widget_type" value="{{ sWidgetType }}"/>
            <input type="hidden" name="module_name" value="page"/>
            <input type="hidden" name="widget_router" value="page/html"/>
            <input type="hidden" name="action" value="submit"/>
            <div class="form-group">
                <label><span class="required">*</span>{{ Translate('theme.widget_name') }}</label>
                <input type="text" id="widget_name" class="form-control" name="widget_name" value=""  >
            </div>
            <div class="form-group">
                <label>{{ Translate('theme.widget_content') }}</label>
                <textarea name="params[content]" id="params_content" class="form-control"></textarea>
            </div>
            <script>
                CORE.editor('#params_content');
            </script>
            {% else %}
            {% endif %}
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="{{ Translate('core.submit') }}"/>
            </div>
        </form>
    </div>
</div>
<script>
    function on_submit_theme_adding(f)
    {
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
            //content = content.responseJSON;
            console.log(content);
            alert(content.message);
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