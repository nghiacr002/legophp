{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    {% if not oExistedGroup.user_group_id %}
    <form method="post" action="{{ Template_Url('user/group/add',{ 'admincp':true }) }}" >
        {% else %}
        <form method="post" action="{{ Template_Url('user/group/edit',{ 'admincp':true, 'id': oExistedGroup.user_group_id }) }}" >
            {% endif %}
            <div class="col-xs-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <span class="ucfirst">
                                {% if not oExistedGroup.user_group_id %}
                                {{ Translate('user.add_new_group') }}
                                {% else %}
                                {{ Translate('user.edit_group') }}
                                {% endif %}
                            </span> 
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">

                        <div class="box-body">
                            {% if flash.hasMessage() %}
                            {% for flashMessage in flash.getMessages() %}
                            <p class="flash-{{ flashMessage.type}}">
                                {{ flashMessage.message }}
                            </p>
                            {% endfor %}
                            {% endif %}
                            <div class="form-group">
                                <label for="group_name">{{ Translate('user.group_name') }}</label>
                                <input class="form-control" id="group_name" type="text" name="group_name" value="{{ oExistedGroup.group_name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="url">{{ Translate('user.parent_group') }}</label>
                                <select class="form-control" name="parent_group_id" id="parent_group_id">
                                    <option value="0">{{ Translate('core.no_parent') }}</option>
                                    {% for aGroup in aGroups %}
                                    {% if oExistedGroup.user_group_id != aGroup.user_group_id %} 
                                    <option value="{{ aGroup.user_group_id }}" {% if oExistedGroup.parent_group_id == aGroup.user_group_id %} selected {% endif %} >{{ aGroup.group_name}}</option>
                                    {% endif %}
                                    {% endfor %}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="url">{{ Translate('core.is_active') }}</label>
                                <div>
                                    <input type="checkbox" id="is_active" class="form-control switch-box" name="is_active" value="1" {% if oExistedGroup.is_active %} checked {% endif %}>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">{{ Translate('core.submit') }}</button>
                        </div>

                    </div>
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-8">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <span class="ucfirst">
                                {{ Translate('user.group_permissions') }}
                            </span> 
                        </h3>
                        <div class="box-tools">
                            <div style="width: 200px;" class="input-group input-group-sm">
                                <input type="text" placeholder="Search" class="form-control pull-right input_search">

                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body no-padding" id="permission-holder">
                        <table class="table table-hover table-bordered">
                            {% for oPerm in aPerms %}
                            <tr class="perm-row" id="perm-row-{{ oPerm.permission_id }}">
                                <td>
                                    <p class="perm-title">{{ Translate( oPerm.permission_title ) }} [<strong>{{ oPerm.permission_title }}</strong>]</p>
                                </td>
                                <td>
                                    <input type="checkbox" class="switch-box" name="perms[{{ oPerm.permission_id }}]" value="1" {% if oPerm.gp_value == 1 %} checked {% endif %} >
                                </td>
                            </tr>

                            {% endfor %}
                        </table>
                    </div>
                </div>
            </div>
        </form>
</div>
{% endblock %}