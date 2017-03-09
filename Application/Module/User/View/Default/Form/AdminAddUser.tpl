
<div class="form-group">
    <label for="full_name">{{ form.element('full_name').hasRequired() }} {{ Translate('user.full_name') }}</label> 
    <input type="text" id="full_name" class="form-control" name="full_name" value="{{ form.element('full_name').getValue() }}">
</div>
<div class="form-group">
    <label for="cover_image">{{ form.element('user_image').hasRequired() }} {{ Translate('user.user_image') }}</label> 
    <input type="file" name="user_image" id="user_image"/> <label class="extra_info">{{ Translate('core.max_file_size_allow_to_upload') }} {{ form.element('user_image').getMaxFileSize()|file_size}}</label>
    <div style="margin-top:15px;">
        {% if oExitUser.user_image_url %}
        <img src="{{ oExitUser.user_image_url }}" />
        {% endif %}
    </div>
</div>
<div class="form-group">
    <label for="user_name">{{ form.element('user_name').hasRequired() }} {{ Translate('user.user_name') }}</label> 
    <input type="text" id="user_name" class="form-control" name="user_name" value="{{ form.element('user_name').getValue() }}" >
</div>
<div class="form-group">
    <label for="user_title">{{ form.element('user_name').hasRequired() }} {{ Translate('user.user_title') }}</label> 
    <input type="text" id="user_title" class="form-control" name="user_title" value="{{ form.element('user_title').getValue() }}" >
</div>
<div class="form-group">
    <label for="email">{{ form.element('email').hasRequired() }} {{ Translate('user.email') }}</label> 
    <input type="email" id="full_name" class="form-control" name="email" value="{{ form.element('email').getValue() }}" >
</div>
{% if form.element('password') %}
<div class="form-group">
    <label for="password">{{ form.element('password').hasRequired() }} {{ Translate('user.password') }}</label> 
    <input type="password" name="password" id="password" class="form-control">
</div>
{% endif %}
<div class="form-group">
    <label for="birthday">{{ form.element('address').hasRequired() }} {{ Translate('user.address') }}</label> 
    <input type="text" name="address" id="address" class="form-control" value="{{ form.element('address').getValue() }}">
</div>
<div class="form-group">
    <label for="birthday">{{ form.element('birthday').hasRequired() }} {{ Translate('user.birthday') }}</label> 
    <input type="text" name="birthday" id="birthday" class="form-control" value="{{ form.element('birthday').getValue() }}">
</div>
<div class="form-group">
    <label for="user_text">{{ form.element('user_text').hasRequired() }} {{ Translate('user.user_text') }}</label> 
    <textarea name="user_text" id="user_text" class="form-control">{{ form.element('user_text').getValue() }}</textarea>
</div>
{% if form.element('main_group_id') %}
<div class="form-group">
    <label for="url">{{ form.element('main_group_id').hasRequired() }}  {{ Translate('user.group') }}</label>
    <select class="form-control" name="main_group_id">
        {% for key,value in form.element('main_group_id').getOptions() %}
        <option value="{{ key }}" {%if form.element('main_group_id').getValue() == key  %} selected {% endif %}> {{ value }}</option>
        {% endfor %}
    </select>
</div>
{% endif %}
{% if form.element('status') %}
<div class="form-group">
    <label for="url">{{ Translate('core.is_active') }}</label>
    <div>
        <input type="checkbox" id="status" class="form-control switch-box"
               name="status" value="1" {% if form.element('status').getValue() %} checked {% endif %}>
    </div>
</div>
{% endif %}
