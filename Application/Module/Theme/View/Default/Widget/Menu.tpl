{% if not bIsAdminPanel %}
<!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
    <ul class="nav navbar-nav">
    	{% for key,aMenu in aSystemMenus %}
    		<li class="{% if aMenu.sub %} dropdown {% endif %} {% if aMenu.active %} active {% endif %}" >
    		<a href="{{ Template_Url(aMenu.url) }}" {% if aMenu.sub %} class="dropdown-toggle" data-toggle="dropdown" {% endif %}>
    			<span> {{ aMenu.menu_name }}</span>
    			{% if aMenu.sub %}
	             <span class="caret"></span>
	            {% endif %}
    		</a>
    		{% if aMenu.sub %}
    			<ul class="dropdown-menu" role="menu">
    				 {% for aSubMenu in aMenu.sub %}
    				 	<li>
    				 	<a href="{{ Template_Url(aSubMenu.url) }}">{{ aSubMenu.menu_name }}</a>
    				 	</li>
    				 {% endfor %}
    			</ul>
    		{% endif %}
    		</li>
    	{% endfor %}
        
    </ul>
    {{ Twig_App_Widget('core/globalSearch')}}
</div>
<!-- /.navbar-collapse -->
<!-- Navbar Right Menu -->
<div class="navbar-custom-menu">
    {{ Twig_App_Widget('user/headerMenu') }}
</div>
<!-- /.navbar-custom-menu -->
{% else %}
<!-- Sidebar Menu -->
<ul class="sidebar-menu">
    {% for key,aMenus in aSystemMenus %}
    <li class="header">{{ Translate('core.'~key) }}</li>
        {% for aMenu in aMenus %}
    <li class="{% if aMenu.sub %} treeview {% endif %} {% if aMenu.active %} active {% endif %}" >
        <a href="{{ Template_Url(aMenu.url) }}">
            <i class="{{ aMenu.icon}} "></i> <span> {{ aMenu.name }}</span>
            {% if aMenu.sub %}
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            {% endif %}
        </a>
        {% if aMenu.sub %}
        <ul class="treeview-menu">
            {% for aSubMenu in aMenu.sub %}
            <li class="{% if aSubMenu.active %} active {% endif %}"><a href="{{ Template_Url(aSubMenu.url) }}">{{ aSubMenu.name }}</a></li>
                {% endfor %}
        </ul>
        {% endif%}
    </li>

    {% endfor %}
    {% endfor%}
</ul>
<!-- /.sidebar-menu -->
{% endif %}