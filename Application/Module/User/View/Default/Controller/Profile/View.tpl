{% extends App_Template() %}
{% block content %}
<div class="box box-primary">
      <div class="box-header with-border">
      <div>
      	 {% if user.user_image_url %}
        <img src="{{ user.user_image_url }}" />
        {% endif %}
      </div>
        <h3 class="box-title">{{ user.full_name}}</h3>
        {% if viewer_id == user.user_id%}
        <span class="pull-right">
        	<a href="{{ Template_Url('user/profile/edit') }}"><i class="fa fa-edit"></i></a>
        </span>
        {% endif %}
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <strong><i class="fa fa-envelope-o margin-r-5"></i> {{ Translate('user.email') }}</strong>

        <p class="text-muted">
          {{ user.email}}
        </p>

        <hr>

        <strong><i class="fa fa-map-marker margin-r-5"></i>{{ Translate('user.last_online') }}</strong>

        <p class="text-muted">{{ user.lasted_login |date_format }}</p>
         <hr>
         <strong><i class="fa fa-map-marker margin-r-5"></i>{{ Translate('user.address') }}</strong>

        <p class="text-muted">{{ user.address }}</p>
         <hr>
		<strong><i class="fa fa-envelope-o margin-r-5"></i> {{ Translate('user.user_title') }}</strong>
        <p class="text-muted">
          {{ user.user_title}}
        </p>

        <hr>
        
      </div>
      <!-- /.box-body -->
    </div>
{% endblock %}

