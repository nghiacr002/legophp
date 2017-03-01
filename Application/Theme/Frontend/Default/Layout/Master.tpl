<!DOCTYPE html>
<html>
     <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ Template_Title() }}</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Theme style -->
        {{ Template_CSS() }}
        {{ Template_Meta() }}
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition skin-black layout-top-nav">
        <div class="wrapper">
        	{% if not bIsHideHeader %}
            <header class="main-header">
                {{ Twig_App_Widget('theme/header') }}
            </header>
            {% endif %}
            <!-- Full Width Column -->
            <div class="content-wrapper">
                <div class="container">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        {{ Twig_App_Widget('theme/breadcrumb') }}
                    </section>
					<section class="content-flash" >
                    	{{ Twig_App_Widget('core/Flash') }}
                	</section>
                    <!-- Main content -->
                    <section class="content">
                        {% block content %}
                        	{{ site_content|raw }}
                        {% endblock %}
                    </section>
                    <!-- /.content -->
                </div>
                <!-- /.container -->
            </div>
            {% if not bIsHideFooter %}
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="container">
                    <div class="pull-right hidden-xs">
                        <strong>{{ Translate('core.copyright') }} &copy; {{ "now"|date('Y') }} <a href="{{ App_BrandNameUrl() }}" target="_blank">{{ App_BrandName() }}</a></strong>.
                    </div>
                    {{ Twig_App_Debug_Output() }}
                </div>
            </footer>
            {% endif %}
        </div>
        {{ Template_JS() }}

    </body>
</html>
