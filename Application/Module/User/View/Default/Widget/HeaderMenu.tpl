{% if not currentUser %}
<ul class="nav navbar-nav">
    <li><a href="{{ Template_Url('user/login') }}">{{ Translate('core.sign_in')}}</a></li>
</ul>
{% else %}
<ul class="nav navbar-nav">
    <!-- Messages: style can be found in dropdown.less-->
    <li class="dropdown messages-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-envelope-o"></i>
            <span class="label label-success">4</span>
        </a>
        <ul class="dropdown-menu">

        </ul>
    </li>
    <!-- Notifications: style can be found in dropdown.less -->
    <li class="dropdown notifications-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            <span class="label label-warning">10</span>
        </a>
        <ul class="dropdown-menu">

        </ul>
    </li>
    <!-- Tasks: style can be found in dropdown.less -->
    <li class="dropdown tasks-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-flag-o"></i>
            <span class="label label-danger">9</span>
        </a>
        <ul class="dropdown-menu">

        </ul>
    </li>
    <!-- User Account: style can be found in dropdown.less -->
    <li class="dropdown user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <!--  <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image"> -->
            <span class="hidden-xs">{{ currentUser.full_name}}</span>
        </a>
        <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
                <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                    {{ currentUser.full_name}}
                    <small>Member since Nov. 2012</small>
                </p>
            </li>
            <!-- Menu Body -->
            <li class="user-body" style="display:none;">
                <div class="row">
                    <div class="col-xs-4 text-center">
                        <a href="#">Followers</a>
                    </div>
                    <div class="col-xs-4 text-center">
                        <a href="#">Sales</a>
                    </div>
                    <div class="col-xs-4 text-center">
                        <a href="#">Friends</a>
                    </div>
                </div>
                <!-- /.row -->
            </li>
            <!-- Menu Footer-->
            <li class="user-footer">
                <div class="pull-left">
                    <a href="{{ Template_Url('user/profile') }}" class="btn btn-default btn-flat">{{ Translate('core.profile') }}</a>
                </div>
                <div class="pull-right">
                    <a href="{{ Template_Url('user/logout') }}" class="btn btn-default btn-flat">{{ Translate('core.sign_out')}}</a>
                </div>
            </li>
        </ul>
    </li>

</ul>
{% endif %}