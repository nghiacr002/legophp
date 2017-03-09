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
            {{ Translate('core.sign_in_to_start_your_session') }}
        </p>
        {% endif %}
        <form action="{{ Template_Url('user/login') }}" method="post">
            <input type="hidden" name="url" value="{{ sRedirectUrl }}" />
            <div class="form-group has-feedback">
                <input class="form-control" placeholder="{{ Translate('core.email') }}" name="email" id="email" type="email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="{{ Translate('core.password') }}" name="password" id="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <label>
                        <input type="checkbox" class="checkbox-inline" name="remember" value="1" checked> {{ Translate('core.remember_me') }}
                    </label>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ Translate('core.sign_in') }}</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
		<!-- 
        <div class="social-auth-links text-center">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> {{ Translate('core.sign_in_using_provider',{"provider":"Facebook"}) }}</a>
            <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> {{ Translate('core.sign_in_using_provider',{"provider":"Google +"}) }}</a>
        </div>
 -->
        <a href="{{ Template_Url('user/forgot') }}">{{ Translate('core.i_forgot_my_password') }}</a><br>
        <a href="{{ Template_Url('user/register') }}" class="text-center">{{ Translate('core.register_a_new_membership') }}</a>

    </div>
</div>
{% endblock %}