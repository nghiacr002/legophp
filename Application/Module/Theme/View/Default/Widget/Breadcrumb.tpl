{% if not bNoBreadcrumb %}
{% if (not bIsAdminCP) and  (aBreadCrumb.title or aBreadCrumb.path|length >0)%}
<div class="breadcrumb-holder">
{% endif%}
	{% if aBreadCrumb.title %}
		<h1>
		    {{ aBreadCrumb.title}}
		    <small>{{ aBreadCrumb.extra_title}}</small>
		</h1>
	{% endif%}
	{% if aBreadCrumb.path|length >0 %}
	<ol class="breadcrumb">
	    <li><a href="{{ sHomeURL}}"><i class="fa fa-dashboard"></i> {{ Translate('core.home') }}</a></li>
	        {% for url,sBreadCrumb in aBreadCrumb.path %}
	    <li>
	        <a href="{{ Template_Url(url) }}"> {{ sBreadCrumb}}</a>
	    </li>
	    {% endfor %}
	</ol>
	{% endif %}
{% if (not bIsAdminCP) and (aBreadCrumb.title or aBreadCrumb.path|length >0)%}
</div>
{% endif%}
{% endif %}