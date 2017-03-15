{% extends 'WidgetCustom.tpl' %}
{% block content %}
{{ Template_Include('blog.css','module_blog') }}
	<ul class="blog-list-holder">
	  {% for aBlog in aBlogItems %}
	  	<li>
	  		 <div class="blog-item">
	  		 	<div class="blog-img">
	  		 		{%if aBlog.cover_image_url %}
               			<img src="{{ aBlog.cover_image_url }} "/>
	               {% else %}
	
	               {% endif %}
	  		 	</div>
	  		 	<div class="blog-content">
	  		 		<div class="blog-title"><a href="{{ aBlog.href }}"> {{ aBlog.blog_title }}</a></div>
	  		 		<div class="blog-description">
	  		 			{{aBlog.sort_description| length > 75 ? aBlog.sort_description | slice(0, 75) ~ '...' : aBlog.sort_description}}
	  		 		</div>
	  		 		
	  		 	</div>
	  		 	 <div class="clear"></div>
	  		 </div>
	  	</li>
	  {% endfor%}
	 </ul>
	
{% endblock %}
