<nav class="navbar navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <a href="{{ Template_Url() }}" class="navbar-brand">{{ Template_Logo() }}</a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <i class="fa fa-bars"></i>
            </button>
        </div>
        {{ Twig_App_Widget('theme/menu') }}
    </div>
    <!-- /.container-fluid -->
</nav>
