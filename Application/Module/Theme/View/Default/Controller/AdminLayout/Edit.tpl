{% extends "Master.tpl"%}
{% block content %}
<div class="row">
<div class="col-xs-12">
      <div class="box">
          
          <form method="POST" id="form-layout-design" enctype="multipart/form-data" action="{{ Template_Url('theme/layout/edit',{id:oLayout.layout_id,admincp:true}) }}" onsubmit="return ADMIN_WIDGET.saveDesign(this);">
          <input type="hidden" name="id" value="{{ oLayout.layout_id }} " id="layout_id"/>
          <input type="hidden" id="hide_header_layout" value="{{ oLayout.header }}"/>
          <input type="hidden" id="hide_footer_layout" value="{{ oLayout.footer }}"/>
           <div class="box-body ">
		<div class="col-md-9 col-sm-9" style="padding:0;" >
		    <div id="layout-design-editor" class="edit-widget-mode">
		        {% include sLayoutPagePathFile %}
		    </div>
		</div>
		<div class="col-md-3 col-sm-3" >
		    <div id="widget-list-holders">
		        <ul class="column_stock">
		            {% for key,aWidgets in aRegisteredWidgets %}
		            {%if aWidgets|length > 0 %}
		            <li>
		                <div class="widget-group-title">
		                    <span class="group-text">{{ Translate(key~'.'~key) }}</span>
		                    <span class="group-toggle"><a class="i-toggle" onclick=""><i class="fa fa-minus"></i></a></span>
		                </div>
		                <ul class="widget-sortable">
		                    {% for key,aWidget in aWidgets %}
		                    <li rel="{{ key }}" class="widget-item" wid="{{ aWidget.widget_id }}" t="{{ aWidget.widget_type }}" {%if aWidget.params %}hp="1" {% endif %}>
		                        <span class="widget-text">{{ aWidget.widget_name }}</span>
		                    </li>
		                    {% endfor %}
		                </ul>
		            </li>
		            {% endif %}
		            {% endfor %}
		           
		        </ul>
		    </div>
		</div>
	</div>
	<div class="box-footer">
        <button class="btn btn-primary" type="submit">{{ Translate('core.save') }}</button>
    </div>
    </form>
</div>
</div>
</div>

{% endblock %}