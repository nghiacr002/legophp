<div class="form-group">
    <label for="url">{{ form.element('category_id').hasRequired() }}  {{ Translate('blog.category') }}</label>
    <select class="form-control" name="category_id" style="width:50%;">
        {% for key,cat in form.element('category_id').getOptions() %}
        {% if cat|is_array %}
        <optgroup label="{{ cat.name}}">
            {% for key2,catsub in cat.sub %}
            <option value="{{ key2 }}" {%if form.element('category_id').getValue() == key2  %} selected {% endif %}> {{ catsub }}</option>
            {% endfor %}
        </optgroup>
        {% else %}
        <option value="{{ key }}" {%if form.element('category_id').getValue() == key  %} selected {% endif %}> {{ cat }}</option>
        {% endif %}
        {% endfor %}
    </select>
</div>
<div class="form-group">
    <label for="blog_title">{{ form.element('blog_title').hasRequired() }} {{ Translate('blog.blog_title') }}</label> 
    <input type="text" id="blog_title" class="form-control" name="blog_title" value="{{ form.element('blog_title').getValue() }}"  style="width:60%;" >
</div>
<div class="form-group">
    <label for="slug">{{ form.element('slug').hasRequired() }} {{ Translate('blog.slug') }}</label> 
    <input type="text" id="slug" class="form-control slug" name="slug" value="{{ form.element('slug').getValue() }}"  style="width:60%;" >
</div>
<div class="form-group">
    <label for="sort_description">{{ form.element('sort_description').hasRequired() }} {{ Translate('blog.sort_description') }}</label> 
    <textarea name="sort_description" id="sort_description" class="form-control"  rows="3" style="width:60%;">{{ form.element('sort_description').getValue() }}</textarea>
</div>

<div class="form-group">
    <label for="blog_description">{{ form.element('blog_description').hasRequired() }} {{ Translate('blog.blog_description') }}</label> 
    <textarea name="blog_description" id="blog_description" class="form-control">{{ form.element('blog_description').getValue() }}</textarea>
</div>

<div class="form-group">
    <label for="cover_image">{{ form.element('cover_image').hasRequired() }} {{ Translate('blog.cover_image') }}</label> 
    <input type="file" name="cover_image" id="cover_image"/> <label class="extra_info">{{ Translate('blog.max_file_size_allow_to_upload') }} {{ form.element('cover_image').getMaxFileSize()|file_size}}</label>
    <div style="margin-top:15px;">
        {% if oExistedBlog.cover_image_url %}
        <img src="{{ oExistedBlog.cover_image_url }}" />
        {% endif %}
    </div>
</div>

<div class="form-group">
    <label for="blog_title">{{ form.element('hashtag').hasRequired() }} {{ Translate('core.hashtag') }}</label> 
    <input type="text" id="hashtag" class="form-control" name="hashtag" value="{{ form.element('hashtag').getValue() }}"  >
    <span class="extra_info">{{ Translate('core.separated_by_comma') }}</span>
</div>

<div class="form-group">
    <label for="url">{{ form.element('blog_status').hasRequired() }}  {{ Translate('blog.blog_status') }}</label>
    <select class="form-control" name="blog_status" id="blog_status" style="width:150px;">
        {% for key,value in form.element('blog_status').getOptions() %}
        <option value="{{ key }}" {%if form.element('blog_status').getValue() == key  %} selected {% endif %}> {{ value }}</option>
        {% endfor %}
    </select>
</div>
<div class="form-group ">
      <label for="url">{{ oFormPageItem.element('page_layout').hasRequired() }}  {{ Translate('page.page_layout') }}</label>
      <select class="form-control" name="layout_id" id="layout_id"  style="width:250px;">
          {% for key,value in form.element('layout_id').getOptions() %}
          <option value="{{ key }}" {%if form.element('layout_id').getValue() == key  %} selected {% endif %}> {{ value }}</option>
          {% endfor %}
      </select>
     
  </div>

