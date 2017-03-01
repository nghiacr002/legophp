{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <span class="ucfirst">
                        {{ Translate('core.installed_modules') }}
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
                                    <th >{{ Translate('core.module_title') }}</th>
                                    <th >{{ Translate('core.description') }}</th>
                                    <th >{{ Translate('core.module_version') }}</th>
                                    <th >{{ Translate('core.module_owner') }}</th>
                                    <th style="width:100px;" >{{ Translate('core.is_active') }}</th>

                                    {% for oModule in aInstalledModules %}
                                <tr class="folder-row" id="folder-row-{{ oModule.module_id }}">
                                    <td>{{ oModule.module_title }}</td>
                                    <td>{{ oModule.module_description }}</td>
                                    <td>{{ oModule.module_version }}</td>
                                    <td>{{ oModule.owner }}</td>
                                    <td>
                                        <input type="checkbox" class="switch-box" name="is_active" value="1" 
                                               {% if oModule.is_active %}checked  {% endif %}
                                               {% if oModule.is_core %} disabled  {% endif %}
                                               >

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