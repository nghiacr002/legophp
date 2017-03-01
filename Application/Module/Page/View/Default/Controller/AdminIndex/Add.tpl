{% extends "Master.tpl"%}
{% block content %}
<div class="box nav-tabs-custom">
    <ul class="nav nav-tabs pull-right" btn-submit="#btn-submit-page">
        {% if oExistedPage.page_id %}
        <li class=""><a href="#tab_1-3" data-toggle="tab" aria-expanded=true no-submit=true>{{ Translate('page.page_layout') }}</a></li>
            {% endif %}
        <li class=""><a href="#tab_1-1" data-toggle="tab" aria-expanded="true">{{ Translate('core.meta_seo') }}</a></li>
        <li class="active"><a href="#tab_2-2" data-toggle="tab" aria-expanded="true">{{ Translate('core.basic_information') }}</a></li>

        <li class="pull-left header"><i class="fa fa-th"></i>
            {% if oExistedPage.page_id %}

            <span class="box-title">{{ Translate('page.edit_page') }}</span>
            {% else %}
            <span class="box-title">{{ Translate('page.add_new_page') }}</span>
            {% endif %}
        </li>
    </ul>
    <input type="hidden" value="{{ oExistedPage.page_id  }}" id="item_id"/>
    <input type="hidden" value="page" id="item_type"/>
    {{ oFormPageItem.start({"action": sFormUrl,"method":"post","id": "form-page-infor","enctype":"multipart/form-data"} ) }}
    <input type="hidden" name="page_id" id="page_id" value="{{ oExistedPage.page_id }}" />
    <div class="tab-content">
        <!-- /.tab-pane -->
        {% if oExistedPage.page_id %}
        <div class="tab-pane" id="tab_1-3">
            <div class="box-body">
                <div class="form-group ">
                    <label for="url">{{ oFormPageItem.element('page_layout').hasRequired() }}  {{ Translate('page.page_layout') }}</label>
                    <select class="form-control" name="page_layout" id="page_layout" style="width:250px;display:inline-block;">
                        {% for key,value in oFormPageItem.element('page_layout').getOptions() %}
                        <option value="{{ key }}" {%if oFormPageItem.element('page_layout').getValue() == key  %} selected {% endif %}> {{ value }}</option>
                        {% endfor %}
                    </select>
                    <a class="btn btn-success" href="javascript:void(0)" onclick="ADMIN_PAGE.saveDesign({{ oExistedPage.page_id }});">{{ Translate('page.save_design') }}</a>
                </div>
                <input type="hidden" id="hide_header_layout" value="{{ oExistedPage.hide_header_layout}}"/>
                <input type="hidden" id="hide_footer_layout" value="{{ oExistedPage.hide_footer_layout}}"/>
                <div class="form-group" id="page-layout-design">
                    <iframe src="" allowfullscreen></iframe>

                </div>
            </div>
        </div>
        {% endif %}
        <div class="tab-pane" id="tab_1-1">
            <div class="box-body">
                {{ Twig_App_Widget('Core/MetaTag',{ type:"page",id: oExistedPage.page_id })}}
            </div>
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane active" id="tab_2-2">
            <div class="box-body">
                {{ oFormPageItem.render()}}
            </div>
        </div>

    </div>
    <div class="box-footer">
        <button class="btn btn-primary" type="submit" id="btn-submit-page">{{ Translate('core.submit') }}</button>
    </div>
    <!-- /.tab-content -->
    {{ oFormPageItem.end() }}
</div>
{% endblock %}