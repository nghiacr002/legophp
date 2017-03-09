{% extends App_Template() %}
{% block content %}
<div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">{{ Translate('user.edit_profile')}}</h3>
       
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
{% endblock %}

