
{% if aCustomMenus|length > 0 %}
<div class="admin-right-header-menu">
	{% for key,aMenu in aCustomMenus %}
		  <a href="{{ aMenu.action }}" class="{{ aMenu.class }}">
		           {{ aMenu.title }}
		        </a>
	{% endfor %}
</div>
{% endif %}