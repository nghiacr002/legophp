
<div id="widget_{{wid}}" class="widget">
	{% if sTitle %}
    <div class="title">{{ sTitle|raw }}</div>
    {% endif %}
   
    <div class="content">
    	 {% block content %}
    		{{ sContent|raw }}
    	{% endblock %}
    </div>
    
</div>   
