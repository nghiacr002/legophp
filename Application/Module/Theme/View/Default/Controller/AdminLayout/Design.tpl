{% extends "Blank.tpl"%}
{% block content %}
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
                    <li rel="{{ key }}" class="widget-item" wid="{{ aWidget.widget_id }}" t="{{ aWidget.widget_type }}" {%if aWidget.params %}hp="1" {% endif %} {%if aWidget.pw_id%}pwid="{{aWidget.pw_id}}"{%endif%}>
                        <span class="widget-text">{{ aWidget.widget_name }}</span>
                    </li>
                    {% endfor %}
                </ul>
            </li>
            {% endif %}
            {% endfor %}
             {%if aItemWidgets|length > 0%}
            	<li id="layout-widget-item-{{sItemType}}-{{iItemId}}" class="layout-widget-item-holder">
            		<div class="widget-group-title">
	                    <span class="group-text">{{ Translate('theme.item_widgets') }}</span>
	                    <span class="group-toggle"><a class="i-toggle" onclick=""><i class="fa fa-minus"></i></a></span>
	                </div>
	                <ul class="widget-sortable">
	                    {% for key,aWidget in aItemWidgets %}
	                    <li rel="{{ key }}" class="widget-item" wid="{{ aWidget.widget_id }}" t="{{ aWidget.widget_type }}" {%if aWidget.param_values %}hp="1" {% endif %} pw="{{aWidget.pw_id}}" removeondraf="1">
	                        <span class="widget-text">{{ aWidget.widget_name }} - #{{aWidget.pw_id}}</span>
	                    </li>
	                    {% endfor %}
	                </ul>
            	</li>
            {%endif%}
        </ul>
    </div>
</div>
{% endblock %}