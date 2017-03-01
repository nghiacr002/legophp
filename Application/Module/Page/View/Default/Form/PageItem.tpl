
<div class="form-group">
    <label for="blog_title">{{ form.element('page_title').hasRequired() }} {{ Translate('page.page_title') }}</label> 
    <input type="text" id="page_title" class="form-control" name="page_title" value="{{ form.element('page_title').getValue() }}"  style="width:60%;" >
</div>
<div class="form-group">
    <label for="page_url">{{ form.element('page_url').hasRequired() }} {{ Translate('page.page_url') }}</label> 
    <input type="text" id="page_url" class="form-control slug" name="page_url" value="{{ form.element('page_url').getValue() }}"  style="width:60%;" >
</div>
<div class="form-group">
    <label for="page_content">{{ form.element('page_content').hasRequired() }} {{ Translate('page.page_content') }}</label> 
    <textarea name="page_content" id="page_content" class="form-control">{{ form.element('page_content').getValue() }}</textarea>
</div>

<div class="form-group">
    <label for="custom_css">{{ form.element('custom_css').hasRequired() }} {{ Translate('page.custom_css') }}</label> 
    <textarea name="custom_css" id="custom_css" class="form-control"  rows="5" style="width:60%;">{{ form.element('custom_css').getValue() }}</textarea>
</div>
<div class="form-group">
    <label for="custom_js">{{ form.element('custom_js').hasRequired() }} {{ Translate('page.custom_js') }}</label> 
    <textarea name="custom_js" id="custom_js" class="form-control"  rows="5" style="width:60%;">{{ form.element('custom_js').getValue() }}</textarea>
</div>

<div class="form-group">
    <label for="blog_title">{{ form.element('hashtag').hasRequired() }} {{ Translate('core.hashtag') }}</label> 
    <input type="text" id="hashtag" class="form-control" name="hashtag" value="{{ form.element('hashtag').getValue() }}"  >
    <span class="extra_info">{{ Translate('core.separated_by_comma') }}</span>
</div>

<div class="form-group">
    <label for="url">{{ form.element('page_status').hasRequired() }}  {{ Translate('page.page_status') }}</label>
    <select class="form-control" name="page_status" id="page_status" style="width:150px;">
        {% for key,value in form.element('page_status').getOptions() %}
        <option value="{{ key }}" {%if form.element('page_status').getValue() == key  %} selected {% endif %}> {{ value }}</option>
        {% endfor %}
    </select>
</div>

