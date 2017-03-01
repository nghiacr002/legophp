<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ Template_Title() }}</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <!-- Ionicons -->
        <!--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">-->
        <!-- Theme style -->
        {{ Template_CSS() }}
        {{ Template_Meta() }}
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body class="hold-transition skin-blue fixed sidebar-mini">
        <div class="wrapper">

            <!-- Main Header -->
            <header class="main-header">

                <!-- Logo -->
                <a href="{{ Template_Url('',{admincp:true}) }}" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <!-- <span class="logo-mini"><b>S</b>C</span> -->
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg">{{ Template_Logo() }}</span>
                </a>

                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        {{ Twig_App_Widget('core/adminMenuRight') }}
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">

                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">

                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="{{ aCurrentUser.user_image|image_path('small-square') }}" class="img-circle" alt="">
                        </div>
                        <div class="pull-left info">
                            <p>{{ aCurrentUser.full_name}}</p>
                            <!-- Status -->
                            <a href="#"><i class="fa fa-circle text-success"></i> {{ Translate('core.online') }}</a> 
                            <a href="{{ Template_Url('user/logout') }}"><i class="fa fa-power-off" style="color:red;"></i>{{ Translate('core.sign_out')}}</a>
                        </div>
                    </div>

                    {{ Twig_App_Widget('theme/menu')}}	

                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    {{ Twig_App_Widget('theme/Breadcrumb')}}
                </section>
                <section class="content-flash"  style="display:none;">
                    {{ Twig_App_Widget('core/Flash') }}
                </section>
                <!-- Main content -->
                <section class="content">

                    {% block content %}
                    <!-- Homepage content -->
                    	{{ site_content }}
                    {% endblock %}

                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <!-- Main Footer -->
            <footer class="main-footer">
                <!-- To the right -->
                <div class="pull-right hidden-xs">
                    {{ Twig_App_Debug_Output()}}
                </div>
                <!-- Default to the left -->
                <strong>{{ Translate('core.copyright') }} &copy; {{ "now"|date('Y') }} <a href="{{ App_BrandNameUrl() }}" target="_blank">{{ App_BrandName() }}</a></strong>. 
            </footer>
        </div>
        <!-- ./wrapper -->

        {{ Template_JS() }}
    </body>
</html>
