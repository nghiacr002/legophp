{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <span class="ucfirst">
                        {% if sSettingType  =='core' %}
                        {{ Translate('core.general') }}
                        {% else %}
                        {{ sSettingType }}
                        {% endif %}
                    </span> 
                </h3>
                <div class="box-tools">
                    <div style="width: 200px;" class="input-group input-group-sm">
                        <input type="text" placeholder="Search" class="form-control pull-right input_search" name="table_search">

                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding ">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tbody>
                            <tr>
                                <th style="width:5%;">{{ Translate('core.id') }}</th>
                                <th style="width:50%;">{{ Translate('core.name') }}</th>
                                <th style="width:10%;">{{ Translate('core.type') }}</th>
                                <th>{{ Translate('core.value') }}</th>
                            </tr>
                            {% for key,aSetting in aSettings %}
                            <tr class="setting-row">

                                <td>#{{ aSetting.setting_id }}</td>
                                <td>
                                    <p class="setting-title">{{ Translate(aSetting.setting_title)}} [<strong>{{ key }}</strong>]</p>
                                    <p>{{ Translate(aSetting.description)}}</p>
                                </td>
                                <td>{{ aSetting.setting_type }}</td>
                                <td>
                                    <a href="#" id="setting-data-{{ aSetting.setting_id}}" 
                                       data-type="{{ aSetting.setting_type }}" 
                                       data-pk="{{ aSetting.setting_id}}" data-title="{{ Translate('core.enter_value') }}" class="editable-field">
                                        {{ aSetting.value}}
                                    </a>
                                </td>
                            </tr>
                            {% endfor%}

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </div>
</div>
{% endblock %}