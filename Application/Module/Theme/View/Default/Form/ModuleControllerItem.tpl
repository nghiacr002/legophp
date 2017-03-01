
<div class="form-group">
    <label for="custom_css">{{ form.element('custom_css').hasRequired() }} {{ Translate('page.custom_css') }}</label> 
    <textarea name="custom_css" id="custom_css" class="form-control" rows="5" >{{ form.element('custom_css').getValue() }}</textarea>
</div>
<div class="form-group">
    <label for="custom_js">{{ form.element('custom_js').hasRequired() }} {{ Translate('page.custom_js') }}</label> 
    <textarea name="custom_js" id="custom_js" class="form-control"  rows="5">{{ form.element('custom_js').getValue() }}</textarea>
</div>

