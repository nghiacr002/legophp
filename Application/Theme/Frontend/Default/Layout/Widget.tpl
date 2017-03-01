{% block content %}
<div id="widget_{{wid}}" class="widget">
	{% if sTitle %}
    <div class="title">{{ sTitle|raw }}</div>
    {% endif %}
    <div class="content">{{ sContent|raw }}</div>
</div>   
{% endblock %}