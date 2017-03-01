{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
        <div class="box">
        	<div class="box-header with-border">
                <h3 class="box-title">
                    <span class="ucfirst">
                        {{ Translate('theme.edit_controller_layout') }}
                    </span> 
                </h3>
            </div>
            <input type="hidden" value="{{ oExistedLayout.controller_id }}" id="item_id"/>
            
            {{ oFormItem.start({"action": sFormUrl,"method":"post","id": "form-page-infor","enctype":"multipart/form-data","onsubmit":"return MC.onEditLayout(this);"} ) }}
            <input type="hidden" value="controller" id="item_type" name="item_type"/>
            <input type="hidden" value="{{ oFormItem.controller_id }}" name="controller_id" id="controller_id"/>
            <div class="box-body">
                 <div class="form-group ">
                    <label for="url">{{ oFormItem.element('layout_id').hasRequired() }}  {{ Translate('page.page_layout') }}</label>
                    <select class="form-control" name="layout_id" id="page_layout">
                        {% for key,value in oFormItem.element('layout_id').getOptions() %}
                        <option value="{{ key }}" {%if oFormItem.element('layout_id').getValue() == key  %} selected {% endif %}> {{ value }}</option>
                        {% endfor %}
                    </select>
                    <!-- 
                    <a class="btn btn-success" href="javascript:void(0)" onclick="ADMIN_PAGE.saveDesign({{ oExistedPage.page_id }});">{{ Translate('page.save_design') }}</a>
                     -->
                </div>
                <input type="hidden" id="hide_header_layout" value="{{ oExistedLayout.hide_header_layout}}"/>
                <input type="hidden" id="hide_footer_layout" value="{{ oExistedLayout.hide_footer_layout}}"/>
                <div class="form-group" id="page-layout-design">
                    <iframe src="" allowfullscreen></iframe>
                </div>
                {{ oFormItem.render()}}
           
            </div>
            <div class="box-footer">
		        <button class="btn btn-primary" type="submit" id="btn-submit-page">{{ Translate('core.submit') }}</button>
		    </div>
		     {{ oFormPageItem.end() }}
        </div>
    </div>
</div>
{% endblock %}
