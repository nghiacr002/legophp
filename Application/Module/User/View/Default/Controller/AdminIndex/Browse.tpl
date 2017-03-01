{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <form id="user-browse-filter" action='{{ Template_Url('user/browse',{"admincp":true}) }}' method="get">
                    {{ oFilter.render() }}
                </form>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <span class="ucfirst">
                        {{ Translate('user.manage_user') }}
                    </span> 
                </h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body ">
                {% if aUsers|length > 0 %}
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tbody>
                            <tr>
                                <th style="width:50px;" >{{ Translate('core.id') }}</th>
                                <th >{{ Translate('user.full_name') }}</th>
                                <th >{{ Translate('user.email') }}</th>
                                <th >{{ Translate('user.group_name') }}</th>
                                <th >{{ Translate('user.last_online') }}</th>
                                <th style="width:100px;"></th>

                                {% for oUser in aUsers %}
                            <tr class="user-row" id="user-row-{{ oUser.user_id }}">
                                <td><a href="{{ Template_Url('user/edit',{"admincp":true, "id" : oUser.user_id}) }}">#{{ oUser.user_id}}</a></td>
                                <td>{{ oUser.full_name }}</td>
                                <td>{{ oUser.email }}</td>
                                <td>{{ oUser.group_name }}</td>
                                <td>{{ oUser.lasted_login |date_format }}</td>
                                <td>
                                    <a href="{{ Template_Url('user/edit',{"admincp":true, "id" : oUser.user_id}) }}" class="btn btn-info" title="{{ Translate('core.edit') }}" ><i class="fa fa-pencil"></i></a>
                                    <a href="{{ Template_Url('user/delete',{"admincp":true, "id" : oUser.user_id}) }}" class="btn btn-danger js_confirm" title="{{ Translate('core.delete') }}"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            {% endfor%}

                        </tbody>

                    </table>
                </div>
                {% else %}
                <div class="alert alert-warning" style="margin:5px 10px;">{{ Translate('user.no_users_found') }}</div>
                {% endif %}
            </div>
            <div class="">
                {{ paginator.render() }}
            </div>
        </div>
        <!-- /.box -->
    </div>
</div>
{% endblock %}