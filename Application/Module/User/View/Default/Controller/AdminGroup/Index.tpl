{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <span class="ucfirst">
                        {{ Translate('user.groups') }}
                    </span> 
                </h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding ">
                <div class="table-responsive">
                    <form method="post" action="" >
                        <table class="table table-hover table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width:50px;" >{{ Translate('core.id') }}</th>
                                    <th >{{ Translate('user.group_name') }}</th>
                                    <th >{{ Translate('user.group_parent') }}</th>
                                    <th >{{ Translate('user.total_member') }}</th>
                                    <th style="width:100px;" >{{ Translate('core.is_active') }}</th>
                                    <th style="width:50px;"></th>

                                    {% for oGroup in aGroups %}
                                <tr class="folder-row" id="folder-row-{{ oGroup.user_group_id }}">
                                    <td><a href="{{ Template_Url('user/group/edit',{"admincp":true, "id" : oGroup.user_group_id}) }}">#{{ oGroup.user_group_id}}</a></td>
                                    <td>{{ oGroup.group_name }}</td>
                                    <td>{{ oGroup.parent_group_id }}</td>
                                    <td>{{ oGroup.total_member }}</td>
                                    <td>
                                        <input type="checkbox" class="switch-box" name="is_active" value="1" 
                                               {% if oGroup.is_active %}checked  {% endif %}
                                               >

                                    </td>
                                      <td>
	                                    <a href="{{ Template_Url('user/group/delete',{"admincp":true, "id" : oGroup.user_group_id}) }}" class="btn btn-danger js_confirm" title="{{ Translate('core.delete') }}"><i class="fa fa-trash"></i></a>
	                                </td>
                                </tr>
                                {% endfor%}

                            </tbody>

                        </table>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </div>
</div>
{% endblock %}