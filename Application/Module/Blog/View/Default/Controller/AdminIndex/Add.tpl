{% extends "Master.tpl"%}
{% block content %}
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs pull-right">
        <li class=""><a href="#tab_1-1" data-toggle="tab" aria-expanded="false">{{ Translate('core.meta_seo') }}</a></li>
        <li class="active"><a href="#tab_2-2" data-toggle="tab" aria-expanded="false">{{ Translate('core.basic_information') }}</a></li>

        <li class="pull-left header"><i class="fa fa-th"></i>
            {% if oExistedBlog.blog_id %}
            <span class="box-title">{{ Translate('blog.edit_blog') }}</span>
            {% else %}
            <span class="box-title">{{ Translate('blog.add_new_blog') }}</span>
            {% endif %}
        </li>
    </ul>
    {{ oFormBlogItem.start({"action": sFormUrl,"method":"post","id": "form-blog-infor","enctype":"multipart/form-data"} ) }}
    <div class="tab-content">
        <div class="tab-pane" id="tab_1-1">
            <div class="box-body">
                {{ Twig_App_Widget('Core/MetaTag',{ type:"blog",id: oExistedBlog.blog_id })}}
            </div>
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane active" id="tab_2-2">
            <div class="box-body">
                {{ oFormBlogItem.render()}}
            </div>
        </div>
        <!-- /.tab-pane -->

    </div>
    <div class="box-footer">
        <button class="btn btn-primary" type="submit">{{ Translate('core.submit') }}</button>
    </div>
    <!-- /.tab-content -->
    {{ oFormBlogItem.end() }}
</div>
{% endblock %}