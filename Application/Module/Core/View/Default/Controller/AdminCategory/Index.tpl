{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-list"></i>
                <h3 class="box-title ucfirst">{{ sCurrentType}}</h3>
                <div class="box-tools pull-right">
                    <a href="javascript:void(0)" title="{{ Translate('core.save_changes') }}" onclick="ADMIN_CATEGORY.saveChanges(this);" class="btn btn-success"><i class="fa fa-save"></i></a>
                </div>
            </div>
            <div class="box-body">
                <ol class="sortable" id="category-holder-editable">
                    {% for aCategory in aCategories %}
                    <li class="category-item" id="category-item-{{ aCategory.category_id }}">
                        <div class="category-content category-active-mode-{{ aCategory.is_active}}">
                            <span>{{ aCategory.category_name}}</span>
                            <span class="pull-right edit-row-category">
                                <a href="javascript:void(0)" class="btn btn-info" title="{{ Translate('core.edit') }}" onclick="ADMIN_CATEGORY.edit({{ aCategory.category_id}});"><i class="fa fa-pencil"></i></a>
                                <a href="javascript:void(0)" class="btn btn-danger" title="{{ Translate('core.delete') }}" onclick="ADMIN_CATEGORY.delete({{ aCategory.category_id}});"><i class="fa fa-trash"></i></a>
                            </span>
                        </div>
                        {% if aCategory.sub|length > 0 %}
                        <ol class="sub-category-items">
                            {% for aSubCategory in aCategory.sub %}
                            <li class="category-item" id="category-item-{{ aSubCategory.category_id }}">
                                <div class="category-content category-active-mode-{{ aSubCategory.is_active }}">
                                    <span>{{ aSubCategory.category_name}}</span>
                                    <span class="pull-right edit-row-category">
                                        <a href="javascript:void(0)" class="btn btn-info" title="{{ Translate('core.edit') }}" onclick="ADMIN_CATEGORY.edit({{ aSubCategory.category_id }});"><i class="fa fa-pencil"></i></a>
                                        <a href="javascript:void(0)" class="btn btn-danger" title="{{ Translate('core.delete') }}" onclick="ADMIN_CATEGORY.delete({{ aSubCategory.category_id }});"><i class="fa fa-trash"></i></a>
                                    </span>
                                </div>
                            </li>
                            {% endfor %}
                        </ol>
                        {% endif %}
                    </li>
                    {% endfor %}
                </ol>
                <input type="hidden" id="data-category-ordering" value="" />
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-default" id="box-form-category">
            <div class="box-header with-border">
                <i class="fa fa-diamond"></i>
                <h3 class="box-title">{{ Translate('core.add_new_category') }}</h3>
                <span class="pull-right" style="display:none" id="switch_form">
                    <a href="javascript:void(0)" onclick="ADMIN_CATEGORY.resetForm();"><i class="fa fa-rotate-left"></i> {{ Translate('core.switch_to_add_form') }}</a>
                </span>
            </div>
            <form method="post" id="form-category-adding" class="app-form-validation">
                <input type="hidden" name="category_type" value="{{ sCurrentType }}"/>
                <div class="box-body">
                    <input type="hidden" name="category_id" id="category_id" value=""/>
                    <div class="form-group">
                        <label for="menu_name">{{ Translate('core.category_name') }}</label>
                        <input type="text" id="category_name" class="form-control" name="category_name" required>
                    </div>
                    <div class="form-group">
                        <label for="url">{{ Translate('core.parent_menu') }}</label>
                        <select class="form-control" name="parent_id" id="parent_id">
                            <option value="0">{{ Translate('core.no_parent') }}</option>
                            {% for aCategory in aCategories %}
                            <option value="{{ aCategory.category_id }}">{{ aCategory.category_id}}</option>
                            {% endfor %}
                        </select>
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

                </div>
            </form>
        </div>
    </div>

</div>
{% endblock %}