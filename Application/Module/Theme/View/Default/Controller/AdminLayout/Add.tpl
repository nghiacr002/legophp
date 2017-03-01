{% extends "Master.tpl"%}
{% block content %}
<div class="row">
<div class="col-xs-12">
		<div class="alert alert-warning">{{ Translate('theme.you_cannot_edit_layout_after_creation') }}</div>
      <div class="box">
          <form method="POST" id="form-layout-design" enctype="multipart/form-data" action="{{ Template_Url('theme/layout/add',{admincp:true}) }} " onsubmit="return ADMIN_DESIGN.save(this);">
			  <input type="hidden" value="1" name="footer" id="footer"/>    
			  <input type="hidden" value="1" name="header" id="header"/>    
           <div class="box-body ">
          
          <div class="form-group">
		    <label for="layout_title">  {{ Translate('theme.layout_title') }}</label> 
		    <input type="text" id="layout_title" class="form-control" name="layout_title" value=""  style="width:60%;" required>
		  </div>
		  
		   <input type="hidden" id="hide_header_layout" value="1"/>
                <input type="hidden" id="hide_footer_layout" value="1"/>
		<div class="col-md-9 col-sm-9" style="padding:0;" >
		    <div id="layout-design-editor">
		        {% include sLayoutPagePathFile %}
		    </div>
		</div>
		<div class="col-md-3 col-sm-3" >
		    <div id="widget-list-holders" class="container-frame-holder">
		        <div class="widget-sortable">
		        	{% for i in 12..1 %}
		           <div class="frame-item sortable col-md-{{i}}  col-sm-{{i}} col-xs-{{i}}">
		           		<span class="widget-text">{{ i }}</span>
		           </div>
		           <div class="clear"></div>
		           {% endfor %}
		        </div>
		    </div>
		</div>
	</div>
	<div class="box-footer">
        <button class="btn btn-primary" type="submit">{{ Translate('core.save') }}</button>
    </div>
    </form>
</div>
</div>
</div>

{% endblock %}