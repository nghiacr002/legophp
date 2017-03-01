{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <form id="user-browse-filter" action='{{ Template_Url('page/manage',{"admincp":true}) }}' method="get">
                    {{ oFilter.render() }}
                </form>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <span class="ucfirst">
                        {{ Translate('page.manage_page') }}
                    </span> 
                </h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding ">
                {% if aPages|length > 0 %}
                <form method="post" action="" id="page-form-holder" onsubmit="return false;">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tbody>
                            <tr>
                                <th style="width:50px;" >{{ Translate('core.id') }}</th>
                                <th >{{ Translate('page.page_title') }}</th>
                                <th style="width:150px;"  >{{ Translate('page.created_time') }}</th>
                                <th style="width:100px;" >{{ Translate('page.page_status') }}</th>
                                <th style="width:100px;" >{{ Translate('page.is_landing') }}</th>
                                <th style="width:100px;" ></th>


                                {% for aPage in aPages %}
                            <tr class="blog-row" id="page-row-{{ aPage.page_id }}">
                                <td><a href="{{ Template_Url('page/edit',{"admincp":true, "id" : aPage.page_id}) }}">#{{ aPage.page_id}}</a></td>
                                <td>
                                    <a href="{{ aPage.href }}" target="_blank">
                                        {{ aPage.page_title }}
                                    </a>
                                </td>
                                <td>{{ aPage.created_time| date_format('d/m/Y h:i:s') }}</td>
                                <td>
                                    <label class="label label-status-{{ aPage.page_status }}">
                                        {{ aPage.page_status_text }}
                                    </label>
                                </td>
                                <td>
                                   	 <input type="radio" class="switch-box switch-box-default" name="is_landing_page" value="{{ aPage.page_id}}" 
                                              {% if aPage.is_landing_page %}checked  {% endif %}
                                              rel="{{ aPage.page_id }}">
                                   </td>
                                <td>
                                    <a href="{{ Template_Url('page/edit',{"admincp":true, "id" : aPage.page_id}) }}" class="btn btn-info" title="{{ Translate('core.edit') }}" ><i class="fa fa-pencil"></i></a>
                                    <a href="{{ Template_Url('page/delete',{"admincp":true, "id" : aPage.page_id}) }}" class="btn btn-danger js_confirm" title="{{ Translate('core.delete') }}"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            {% endfor%}

                        </tbody>

                    </table>
                </div>
                </form>
                
                {% else %}
                <div class="alert alert-warning" style="margin:5px 10px;">{{ Translate('core.no_items_found') }}</div>
                {% endif %}
            </div>
            {% if aPages|length > 0 %}
            <div class="paging-right-holder">
            	<div class="pull-left" style="margin-bottom:15px;">
                	<a href="javascript:void(0)" onclick="ADMIN_PAGE.resetLandingPage()" class="btn btn-danger">{{ Translate('page.reset_landing') }}</a>
                </div>
                {{ paginator.render() }}
                <div class="clear"></div>
            </div>
            {% endif%}
        </div>
        <!-- /.box -->
    </div>
</div>
{% endblock %}