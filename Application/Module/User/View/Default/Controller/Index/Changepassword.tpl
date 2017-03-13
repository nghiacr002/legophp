{% extends "Blank.tpl" %}
{% block content %}
<div class="login-box">
    <div class="login-logo">
        <a href="{{ Template_Url() }}"> <strong>{{ App_Setting('core.site_name') }}</strong></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        {% if flash.hasMessage() %}
	        {% for flashMessage in flash.getMessages() %}
	        <p class="login-box-msg flash-{{ flashMessage.type}}">
	            {{ flashMessage.message }}
	        </p>
	        {% endfor %}
	    {% else %}
	    	<p class="login-box-msg">
        		{{ Translate('user.input_your_new_password') }}
        	<p>   	
        {% endif %}
        <form action="{{ Template_Url('user/changepassword') }}" method="post">

            <div class="form-group">
                <input class="form-control" placeholder="{{ Translate('core.password') }}" name="password" id="password" type="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group">
                <input class="form-control" placeholder="{{ Translate('core.repeat_password') }}" name="repeat_password" id="repeat_password" type="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                 <div class="col-xs-12">
                 <input type="hidden" value="{{sRequestToken}}" name="token">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ Translate('user.request') }}</button>
                </div>
            </div>
        </form>
		
    </div>
</div>
{% endblock %}