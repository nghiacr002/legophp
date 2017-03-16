{% extends App_Template() %}
{% block content %}
 <form id="user-browse-filter" action='{{ Template_Url('blog') }}' method="get">
     {{ oFilter.render() }}
 </form>
 <div id="blog-list-holder">
 	{{ Template_Include('blog.css','module_blog') }}
	<ul class="blog-list-holder">
	  {% for aBlog in aBlogs %}
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
 </div>
 <div class="paging-right-holder">
      {{ paginator.render() }}
  </div>
{% endblock %}