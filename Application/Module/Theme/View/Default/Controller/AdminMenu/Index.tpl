{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-list"></i>
                <h3 class="box-title">{{ Translate('core.manage_menus') }}</h3>
                <div class="box-tools pull-right">
                    <a href="javascript:void(0)" title="{{ Translate('core.save_changes') }}" onclick="MENU.saveChanges(this);" class="btn btn-success"><i class="fa fa-save"></i></a>
                </div>
            </div>
            <div class="box-body">
                <ol class="sortable" id="menu-holder-editable">
                    {% for aMenu in aMenus %}
                    <li class="menu-item" id="menu-item-{{ aMenu.menu_id}}">
                        <div class="menu-content menu-active-mode-{{ aMenu.is_active}}">
                            <span>{{ aMenu.menu_name}}</span>
                            <span class="pull-right edit-row-menu">
                                <a href="javascript:void(0)" class="btn btn-info" title="{{ Translate('core.edit') }}" onclick="MENU.edit({{ aMenu.menu_id}});"><i class="fa fa-pencil"></i></a>
                                <a href="javascript:void(0)" class="btn btn-danger" title="{{ Translate('core.delete') }}" onclick="MENU.delete({{ aMenu.menu_id}});"><i class="fa fa-trash"></i></a>
                            </span>
                        </div>
                        {% if aMenu.sub|length > 0 %}
                        <ol class="sub-menu-items">
                            {% for aSubMenu in aMenu.sub %}
                            <li class="menu-item" id="menu-item-{{ aSubMenu.menu_id}}">
                                <div class="menu-content menu-active-mode-{{ aSubMenu.is_active}}">
                                    <span>{{ aSubMenu.menu_name}}</span>
                                    <span class="pull-right edit-row-menu">
                                        <a href="javascript:void(0)" class="btn btn-info" title="{{ Translate('core.edit') }}" onclick="MENU.edit({{ aSubMenu.menu_id}});"><i class="fa fa-pencil"></i></a>
                                        <a href="javascript:void(0)" class="btn btn-danger" title="{{ Translate('core.delete') }}" onclick="MENU.delete({{ aSubMenu.menu_id}});"><i class="fa fa-trash"></i></a>
                                    </span>
                                </div>
                            </li>
                            {% endfor %}
                        </ol>
                        {% endif %}
                    </li>
                    {% endfor %}
                </ol>
                <input type="hidden" id="data-menu-ordering" value="" />
            </div>
        </div>
    </div>
    <div class="col-md-4">
    	<div class="nav-tabs-custom">
    		 <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab" id="tab_1_a">{{ Translate('core.add_new_menu') }}</a></li>
              <li><a href="#tab_2" data-toggle="tab">{{ Translate('theme.existed_items') }}</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
              	<form method="post" id="form-menu-adding" class="app-form-validation">
                <div class="box-body">
                    <input type="hidden" name="menu_id" id="menu_id" value=""/>
                    <div class="form-group">
                        <label for="menu_name">{{ Translate('core.menu_name') }}</label>
                        <input type="text" id="menu_name" class="form-control" name="menu_name" required>
                    </div>
                    <div class="form-group">
                        <label for="url">{{ Translate('core.parent_menu') }}</label>
                        <select class="form-control" name="parent_id" id="parent_id">
                            <option value="0">{{ Translate('core.no_parent') }}</option>
                            {% for aMenu in aMenus %}
                            <option value="{{ aMenu.menu_id}}">{{ aMenu.menu_name}}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="url">{{ Translate('core.menu_url') }}</label>
                        <input type="text" id="url" class="form-control" name="url" required>
                    </div>
                    <div class="form-group">
                        <label for="url">{{ Translate('core.is_active') }}</label>
                        <div>
                            <input type="checkbox" id="is_active" class="form-control" name="is_active" value="1" checked>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <input type="submit" class="btn btn-success" value="{{ Translate('core.submit') }}"/>
					 <span class="pull-right" style="display:none" id="switch_form">
                    <a href="javascript:void(0)" onclick="MENU.resetForm();"><i class="fa fa-rotate-left"></i> {{ Translate('core.switch_to_add_form') }}</a>
                </span>
                </div>
            </form>
              </div>
              <div class="tab-pane" id="tab_2">
              {% if aCustomMenuItems|length > 0 %}
              	<div id="existed-page-category-items">
                <ul>
                {% for key,groupItems in aCustomMenuItems %}
	                {% if groupItems|length > 0 %}
	                	<li>
	                		<span class="key-title">{{ key }}</span>
	                		<ol>
	                			 {% for key2,aItem in groupItems %}
	                			 	<li>
	                			 		 <div class="menu-content">
		                                    <span>{{ aItem.menu_name}}</span>
		                                    <span class="pull-right edit-row-menu">
		                                        <a href="javascript:void(0)" 
		                                        class="btn btn-info" title="{{ Translate('core.add') }}" 
		                                        onclick="MENU.insertExistedItem(this);" name="{{ aItem.menu_name }}" url="{{aItem.url}}"><i class="fa fa-plus"></i></a>
		                                    </span>
		                                </div>
	                			 	</li>
	                			 {% endfor %}
	                		</ol>
	                		
	                	</li>
	                {% endif %}
                {% endfor %}
                </ul>
                </div>
                {% endif %}
              </div>
            </div>
    	</div>
    	
    </div>

</div>
{% endblock %}