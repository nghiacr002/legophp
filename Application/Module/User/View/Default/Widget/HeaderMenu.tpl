{% if not currentUser %}
<ul class="nav navbar-nav">
    <li><a href="{{ Template_Url('user/login') }}">{{ Translate('core.sign_in')}}</a></li>
</ul>
{% else %}
<ul class="nav navbar-nav">
   
    <li class="dropdown user user-menu">
        <a href="{{ Template_Url(currentUser.user_name)}}" style="display:inline-block;">
            <span class="hidden-xs">{{ currentUser.full_name}}</span>
        </a>
    </li>
	<li class="dropdown user user-menu">
     
         <a href="{{ Template_Url('user/logout') }}" >
            <span class="hidden-xs">{{ Translate('core.logout')}}</span>
        </a>
    </li>
</ul>
{% endif %}