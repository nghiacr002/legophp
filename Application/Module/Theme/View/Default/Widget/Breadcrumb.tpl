{% if not bNoBreadcrumb %}
	{% if aBreadCrumb.title %}
		<h1>
		    {{ aBreadCrumb.title}}
		    <small>{{ aBreadCrumb.extra_title}}</small>
		</h1>
	{% endif%}
	{% if aBreadCrumb.path|length >0 %}
	<ol class="breadcrumb">
	    <li><a href="{{ Template_Url('',{'admincp':true}) }}"><i class="fa fa-dashboard"></i> {{ Translate('core.home') }}</a></li>
	        {% for url,sBreadCrumb in aBreadCrumb.path %}
	    <li>
	        <a href="{{ Template_Url(url) }}"> {{ sBreadCrumb}}</a>
	    </li>
	    {% endfor %}
	</ol>
	{% endif %}
{% endif %}