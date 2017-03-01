{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <span class="ucfirst">
                        {{ Translate('core.installed_languages') }}
                    </span> 
                </h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding ">
                <div class="table-responsive">
                    <form method="post" action="" id="language-form-holder" onsubmit="return false;">
                        <table class="table table-hover table-bordered">
                            <tbody>
                                <tr>
                                    <th >{{ Translate('core.language_name') }}</th>
                                    <th >{{ Translate('core.language_code') }}</th>
                                    <th style="width:100px;" >{{ Translate('core.is_default') }}</th>
                                    <th style="width:100px;" >{{ Translate('core.is_active') }}</th>

                                    {% for oLanguage in aLanguages %}
                                <tr class="folder-row" id="folder-row-{{ oLanguage.language_id }}">
                                    <td>{{ oLanguage.language_name }}</td>
                                    <td>{{ oLanguage.language_code }}</td>
                                    <td>
                                        <input type="radio" class="switch-box switch-box-default" name="is_default" value="{{ oLanguage.language_id }}" 
                                               {% if oLanguage.is_default %}checked  {% endif %}
                                               rel="{{ oLanguage.language_id }}">
                                    </td>
                                    <td>
                                        <input type="checkbox" class="switch-box switch-box-active" name="is_active[]" value="{{ oLanguage.language_id }}" 
                                               {% if oLanguage.is_active %}checked  {% endif %}
                                               {% if oLanguage.is_default %} disabled  {% endif %}
                                               rel="{{ oLanguage.language_id }}">

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