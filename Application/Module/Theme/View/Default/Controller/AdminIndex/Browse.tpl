{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <span class="ucfirst">
                        {{ Translate('theme.installed_themes') }}
                    </span> 
                </h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding ">
                <div class="table-responsive">
                    <form method="post" action="" id="theme-form-holder" onsubmit="return false;">
                        <table class="table table-hover table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width:30px;"></th>
                                    <th >{{ Translate('theme.theme_title') }}</th>
                                    <th >{{ Translate('theme.folder') }}</th>
                                    <th >{{ Translate('theme.version') }}</th>
                                    <th style="width:100px;" >{{ Translate('core.is_default') }}</th>
                                    <th style="width:100px;" >{{ Translate('core.is_active') }}</th>

                                    {% for oTheme in aThemes %}
                                <tr class="folder-row" id="folder-row-{{ oTheme.theme_id }}">
                                	<td><a href="{{ Template_Url('theme/edit',{ admincp:true ,id: oTheme.theme_id }) }}">#{{ oTheme.theme_id }}</a></td>
                                    <td>{{ oTheme.theme_title }}</td>
                                    <td>{{ oTheme.folder }}</td>
                                    <td>{{ oTheme.theme_version }}</td>
                                    <td>
                                        <input type="radio" class="switch-box switch-box-default" name="is_default" value="{{ oTheme.theme_id }}" 
                                               {% if oTheme.is_default %}checked  {% endif %}
                                               rel="{{ oTheme.theme_id }}">
                                    </td>
                                    <td>
                                        <input type="checkbox" class="switch-box switch-box-active" name="is_active[]" value="{{ oTheme.theme_id }}" 
                                               {% if oTheme.is_active %}checked  {% endif %}
                                               {% if oTheme.is_default %} disabled  {% endif %}
                                               rel="{{ oTheme.theme_id }}">

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