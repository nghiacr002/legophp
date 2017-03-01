{% extends App_Template() %}
{% block content %}
	{{ oBlogItem.blog_description|raw }}
{% endblock %}