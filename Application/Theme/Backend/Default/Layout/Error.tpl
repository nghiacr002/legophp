{% extends "Master.tpl" %}
{% block content %}
<p>{{ Translate('core.error_code') }}: <strong>{{ system_code }}</strong></p>
<p>{{code}}: {{message}}</p>
<div class="debug-trace">
    {{ trace|nl2br }}
</div>
{% endblock %}