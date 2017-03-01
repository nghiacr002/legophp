{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
<div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ Translate('theme.edit_theme') }}</h3>
        </div>
        <form method="POST" action="{{ Template_Url('theme/edit',{admincp: true, id: iId })}}" enctype="multipart/form-data">
        
        <div class="box-body">
        	<div class="form-group">
        		<label for="theme_title">{{ Translate('theme.theme_title') }}</label>
          		 <input type="text" name="theme_title" value="{{ oTheme.theme_title }}" class="form-control" id="theme_title" />
           </div>
           <div class="form-group">
        		<label for="theme_title">{{ Translate('theme.folder') }}</label>
          		 <input type="text" name="folder" value="{{ oTheme.folder }}" class="form-control" id="folder" />
           </div>
           <div class="form-group">
        		<label for="theme_title">{{ Translate('theme.version') }}</label>
          		 <input type="text" name="theme_version" value="{{ oTheme.theme_version }}" class="form-control" id="theme_version" />
           </div>
           <div class="form-group">
        		<label for="theme_title">{{ Translate('theme.logo') }}</label>
        		<div>
          			<input type="file" name="file_logo" accept="image/*"/>
          			<span class="tip flash-warning">{{ Translate('core.filesize_limit_string',{filesize:sFileSizeLimit }) }}</span>
        		</div>
        		{% if oTheme.logo %}
        			<img src="{{ Template_Url('image/origin',{path: oTheme.logo}) }}" />
        		{% endif %}
           </div>
           <div class="form-group">
        		<label for="theme_title">{{ Translate('core.is_active') }}</label>
        		<div>
          		 <input type="checkbox" class="switch-box switch-box-active" name="is_active" value="{{ oTheme.theme_id }}" 
                                             {% if oTheme.is_active %}checked  {% endif %}
                                             rel="{{ oTheme.theme_id }}">
				
        		
           </div>
           </div>
           <div class="form-group">
        		<label for="theme_title">{{ Translate('core.is_default') }}</label>
        		<div>
          		 <input type="checkbox" class="switch-box switch-box-active" name="is_default" value="{{ oTheme.theme_id }}" 
                                             {% if oTheme.is_default %} checked  {% endif %}
                                             rel="{{ oTheme.theme_id }}">
        		</div>
           </div>
           
        </div>
        <div class="box-footer">
            <button class="btn btn-primary" type="submit">{{ Translate('core.submit') }}</button>
        </div>
        </form>
    </div>
   </div>
 </div>
{% endblock %}