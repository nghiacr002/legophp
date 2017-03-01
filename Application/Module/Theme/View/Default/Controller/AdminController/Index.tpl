{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body {% if aLayouts|length > 0 %} no-padding  {% endif %}">
                <div class="table-responsive">
                {% if aLayouts|length > 0 %}
                    <form method="post" action="" id="theme-form-holder" onsubmit="return false;">
                        <table class="table table-hover table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width:5%;" >#</th>
                                    <th >{{ Translate('theme.controler_name') }}</th>
                                    <th >{{ Translate('theme.router') }}</th>
                                    <th >{{ Translate('theme.module_name') }}</th>
                                    <th style="width:20%;">{{ Translate('theme.layout') }}</th>
                                    <th style="width:10%;"></th>
                                    {% for oLayout in aLayouts %}
	                                <tr class="folder-row" id="folder-row-{{ oLayout.controller_id }}">
	                                    <td>{{ oLayout.controller_id }}</td>
	                                    <td>{{ oLayout.controller_name }}</td>
	                                    <td>{{ oLayout.router_name }}</td>
	                                    <td>{{ oLayout.module_name }}</td>
	                                    <td>
	                                    	{% if oLayout.layout_title %}
	                                    		{{ oLayout.layout_title }}
	                                    	{% else %}
	                                    		N/A
	                                    	{% endif %}
	                                    </td>
	                                    <td>
	                                        <a class=" btn btn-info" href="{{ Template_Url('theme/controller/edit',{admincp:true,id: oLayout.controller_id }) }}"><i class="fa fa-pencil"></i></a>
	                                        <a href="{{ Template_Url('theme/controller/delete',{"admincp":true, "id" : oLayout.controller_id}) }}" class="btn btn-danger js_confirm" title="{{ Translate('core.delete') }}"><i class="fa fa-trash"></i></a>
	                                    </td>
	                                </tr>
	                                {% endfor %}

                            </tbody>

                        </table>
                    </form>
                    {% else %}
                    	<div class="alert alert-warning">{{ _TL('core.item_not_found') }}</div>
                    {% endif %}
                </div>
            </div>
        </div>
        <!-- /.box -->
    </div>
</div>
{% endblock %}