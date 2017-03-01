{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <span class="ucfirst">
                        {{ Translate('theme.layouts') }}
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
                                    <th >{{ Translate('theme.layout_name') }}</th>
                                    <th >{{ Translate('theme.layout_template') }}</th>
                                    <th style="width:100px;">*{{ Translate('theme.template') }}</th>
                                    <th style="width:100px;"></th>
                                        {% for oLayout in aLayouts %}
                                <tr class="folder-row" id="folder-row-{{ oLayout.layout_id }}">
                                    <td>{{ oLayout.layout_id }}</td>
                                    <td>{{ oLayout.layout_title }}</td>
                                    <td>{{ oLayout.layout_name }}</td>
                                    <td>
                                    	 <input type="radio" class="switch-box switch-box-default" name="is_default" value="{{ oLayout.layout_id}}" 
                                               {% if oLayout.is_template_default %}checked  {% endif %}
                                               rel="{{ oLayout.layout_id }}">
                                    </td>
                                    
                                    <td>
                                        <a class=" btn btn-info" href="{{ Template_Url('theme/layout/edit',{admincp:true,id:oLayout.layout_id }) }}"><i class="fa fa-pencil"></i></a>
                                         <a href="{{ Template_Url('theme/layout/delete',{"admincp":true, "id" : oLayout.layout_id}) }}" class="btn btn-danger js_confirm" title="{{ Translate('core.delete') }}"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                {% endfor%}

                            </tbody>
                            <tfooter>
                            	<tr>
                            		<td colspan="5">
                            			<div class="tip">
                	(*){{ Translate('theme.default_layout_for_webpage')}}
                </div>
                            		</td>
                            	</tr>
                            </tfooter>

                        </table>
                    </form>
                </div>
                
            </div>
        </div>
        <!-- /.box -->
    </div>
</div>
{% endblock %}