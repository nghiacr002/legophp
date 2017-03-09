{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
            	{% if bIsEdit %}
            	<h3 class="box-title">{{ Translate('user.edit_user') }}</h3>
            	{% else %}
                <h3 class="box-title">{{ Translate('user.add_new_user') }}</h3>
                {% endif %}
            </div>
            {{ oFormAdminAddUser.start({"action": sFormUrl,"method":"post","id": "form-user-infor","enctype":"multipart/form-data"} ) }}
            <div class="box-body">
                {{ oFormAdminAddUser.render()}}
            </div>
            <div class="box-footer">
                <button class="btn btn-primary" type="submit">{{ Translate('core.submit') }}</button>
            </div>
            {{ oFormAdminAddUser.end() }}
        </div>
    </div>
</div>
{% endblock %}