{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <form id="user-browse-filter" action='{{ Template_Url('blog/manage',{"admincp":true}) }}' method="get">
                    {{ oFilter.render() }}
                </form>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <span class="ucfirst">
                        {{ Translate('blog.manage_blog') }}
                    </span> 
                </h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding ">
                {% if aBlogs|length > 0 %}
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tbody>
                            <tr>
                                <th style="width:50px;" >{{ Translate('core.id') }}</th>
                                <th style="width:50px;"  >{{ Translate('blog.image') }}</th>
                                <th >{{ Translate('blog.blog_title') }}</th>
                                <th style="width:150px;" >{{ Translate('blog.category') }}</th>
                                <th style="width:150px;"  >{{ Translate('blog.created_time') }}</th>
                                <th style="width:100px;" >{{ Translate('blog.blog_status') }}</th>
                                <th style="width:100px;" ></th>


                                {% for aBlog in aBlogs %}
                            <tr class="blog-row" id="blog-row-{{ aBlog.blog_id }}">
                                <td>
                                <a href="{{ Template_Url('blog/edit',{"admincp":true, "id" : aBlog.blog_id}) }}">#{{ aBlog.blog_id}}</a></td>

                                <td>
                                    {%if aBlog.cover_image_url %}
                                    <img src="{{ aBlog.cover_image_url }} " style="width:30px;"/>
                                    {% else %}

                                    {% endif %}
                                </td>
                                <td>
                                    <a href="{{ aBlog.href }}" target="_blank">
                                        {{ aBlog.blog_title }}
                                    </a>
                                </td>
                                <td>{{ aBlog.category_name }}</td>
                                <td>{{ aBlog.created_time| date_format('d/m/Y h:i:s') }}</td>
                                <td>
                                    <label class="label label-status-{{ aBlog.blog_status }}">
                                        {{ aBlog.blog_status_text }}
                                    </label>
                                </td>
                                <td>
                                    <a href="{{ Template_Url('blog/edit',{"admincp":true, "id" : aBlog.blog_id}) }}" class="btn btn-info" title="{{ Translate('core.edit') }}" ><i class="fa fa-pencil"></i></a>
                                    <a href="{{ Template_Url('blog/delete',{"admincp":true, "id" : aBlog.blog_id}) }}" class="btn btn-danger js_confirm" title="{{ Translate('core.delete') }}"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            {% endfor%}

                        </tbody>

                    </table>
                </div>
                {% else %}
                <div class="alert alert-warning" style="margin:5px 10px;">{{ Translate('core.no_items_found') }}</div>
                {% endif %}
            </div>
            <div class="paging-right-holder">
                {{ paginator.render() }}
            </div>
        </div>
        <!-- /.box -->
    </div>
</div>
{% endblock %}