<div class="page-location {% if bDesignMode %} page-location-design-mode {% endif %}" id="page-location-{{ iLocationId }}" rel="{{ iLocationId }}">
    {% if bDesignMode %}
    <ul class="widget-sortable">
        {% for aWidget in aLayoutWidgets %}
        <li rel="{{ aWidget.widget_router }}" class="widget-item" wid="{{ aWidget.widget_id }}" pw="{{ aWidget.pw_id }}" t="{{ aWidget.widget_type}}" {%if aWidget.params %}hp="1" {% endif %} default="{{ aWidget.isDefaultDesign()}}">
            <span class="widget-text">{{ aWidget.widget_name }}</span>
           
            <span class="widget-controls-holder">
                <a href="javascript:void(0)" title="{{ Translate('core.edit') }}" onclick="ADMIN_WIDGET.editWidget(this);">
                    <i class="fa fa-pencil"></i>
                </a>
                <a href="javascript:void(0)" title="{{ Translate('core.delete') }}" onclick="ADMIN_WIDGET.removeWidget(this);">
                    <i class="fa fa-trash"></i>
                </a>
            </span>

        </li>
        {% endfor %}
    </ul>
    {% else %}
    	{% for aWidget in aLayoutWidgets %}
    		{{ Twig_App_Widget(aWidget.widget_router,aWidget.param_values) }}
		{% endfor %}
    {% endif%}
</div>