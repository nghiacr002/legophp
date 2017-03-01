{% for group,aMetaTag in aDefaultMetaTags %}
{% set group_text = 'core.meta_' ~ group ~ '' %}
<div class="group-meta-tags">
    <h3 class="box-title">{{ Translate(group_text) }}</h3>
    {% for key,value in aMetaTag %}	
    <div class="form-group">
        <label for="label_{{ key }}" class="ucfirst">{{ key }}</label> 
        <textarea name="meta[{{group}}][{{key}}]" id="id_{{group}}_{{key}}" class="form-control" rows="2">{{ value }}</textarea>
        <!-- 
        <span class="extra-info">{{ Translate('core.ref') }}: <a href="{{ Template_Url('core/meta/info',{key:key,'admincp':true}) }}" target="_blank">{{ Template_Url('core/meta/info',{key:key,'admincp':true}) }}</a></span>
        -->
    </div>
    {% endfor %}
</div>
{% endfor %}