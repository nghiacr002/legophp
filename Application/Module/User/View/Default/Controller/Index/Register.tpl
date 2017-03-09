{% extends "Blank.tpl" %}
{% block content %}
<div class="register-box">
    <div class="register-logo">
        <a href="{{ Template_Url() }}"> <strong>{{ App_Setting('core.site_name') }} </strong></a>
    </div>

    <div class="register-box-body">
        {% if flash.hasMessage() %}
        {% for flashMessage in flash.getMessages() %}
        <p class="login-box-msg flash-{{ flashMessage.type}}">
            {{ flashMessage.message }}
        </p>
        {% endfor %}
        {% else %}
        <p class="login-box-msg">
            {{ Translate('core.register_a_new_membership') }}
        </p>
        {% endif %}
		{%if bIsModeInstall %}
        	<form action="{{ Template_Url('user/register',{mode:'install'})}}" method="post" id="{{ registerForm.getId() }}" >
        {% else %}
        	<form action="{{ Template_Url('user/register')}}" method="post" id="{{ registerForm.getId() }}">
        {% endif %}
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="{{ Translate('user.full_name')}}" name="full_name" id="fullname" required value="{{ registerForm.full_name.getValue() }}">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
              <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="{{ Translate('user.user_name')}}" name="user_name" id="user_name" required value="{{ registerForm.user_name.getValue() }}">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input class="form-control" placeholder="{{ Translate('core.email') }}" name="email" id="email" type="email" required value="{{ registerForm.email.getValue() }}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="{{ Translate('core.password') }}" name="password" id="password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="{{ Translate('core.repeat_password') }}" name="repeatpassword" id="repeatpassword" required>
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <label>
                        <input type="checkbox" class="checkbox-inline" name="termofuse" value="1"> {{ Translate('core.i_agree_to_the_terms') }}
                    </label>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ Translate("core.sign_up")}}</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
		<!-- 
        <div class="social-auth-links text-center">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> {{ Translate('core.sign_up_using_provider',{"provider":"Facebook"}) }}</a>
            <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> {{ Translate('core.sign_up_using_provider',{"provider":"Google +"}) }}</a>
        </div>
         -->
        <a href="{{ Template_Url('user/login') }}" class="text-center">{{ Translate('core.i_already_have_a_membership') }}</a>
    </div>
    <!-- /.form-box -->
</div>
<!-- /.register-box -->
{% endblock %}