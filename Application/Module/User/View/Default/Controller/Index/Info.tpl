{% extends "Master.tpl" %}
{% block content %}
{%for a,b in user.toArray(true) %}
<p>{{a}} : {{b}}</p>
{%endfor%}
{% endblock %}