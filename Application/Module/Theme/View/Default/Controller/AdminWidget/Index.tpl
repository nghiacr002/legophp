{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <span class="ucfirst">
                        {{ Translate('theme.registered_widgets') }}
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
                                    <th style="width:50px;" >#</th>
                                    <th >{{ Translate('theme.widget_name') }}</th>
                                    <th >{{ Translate('theme.widget_router') }}</th>
                                    <th >{{ Translate('theme.module') }}</th>
                                    <th style="width:100px;"></th>
                                        {% for oWidget in aWidgets %}
                                <tr class="folder-row" id="folder-row-{{ oWidget.widget_id }}">
                                    <td>{{ oWidget.widget_id }}</td>
                                    <td>{{ oWidget.widget_name_translated }}</td>
                                    <td>{{ oWidget.widget_router }}</td>
                                    <td>{{ oWidget.module_name }}</td>
                                    <td>
                                        <a class=" btn btn-info" href="{{ Template_Url('theme/widget/edit',{admincp:true,id:oWidget.widget_id }) }}"><i class="fa fa-pencil"></i></a>
                                        <a class="{% if oWidget.can_remove %} js_confirm {% endif %} btn btn-danger " {% if oWidget.can_remove %} href="{{ Template_Url('theme/widget/delete',{admincp:true,id:oWidget.widget_id }) }}" {% else %} disabled {% endif %} ><i class="fa fa-trash"></i></a>
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