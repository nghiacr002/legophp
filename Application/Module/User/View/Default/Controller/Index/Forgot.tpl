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
        
        {% endif %}
        <form action="{{ Template_Url('user/forgot') }}" method="post">

            <div class="form-group has-feedback">
                <input class="form-control" placeholder="{{ Translate('core.email') }}" name="email" id="email" type="email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="row tip">
            	<p>{{ Translate('user.input_your_email_to_request_new_password') }}</p>
            </div>
            <div class="row">
                 <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ Translate('user.request') }}</button>
                </div>
            </div>
        </form>
		
    </div>
</div>
{% endblock %}