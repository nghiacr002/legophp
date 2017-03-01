<div class="row-design">
	{% if bIsDesignLayout %}
    <div class="page-location-header">
        <span>
            {{ Translate('page.global_header') }}
            <span>
                <a href="javascript:void(0);" class="hide_on_this_page h-header" df="{{ bIsHideHeader }}" t="header">
                {% if bIsHideHeader %} 
                	{{ Translate('page.show_on_this_page') }}
                {% else %} 
                	{{ Translate('page.hide_on_this_page') }}
                {% endif %}
                </a>
            </span>
        </span>
    </div>
    {% endif %}
    <div class="col-md-12 col-sm-12 main-contain-wrapper has-4-location" id="page-3-column-middle-big">
        <div class="col-md-12 col-sm-12">
            {{ Location(4) }}	
        </div>
        <div class="col-md-3 col-sm-3">
            {{ Location(1) }}	
        </div>
        <div class="col-md-6 col-sm-6">
            {{ Location(2) }}	
        </div>	
        <div class="col-md-3 col-sm-3">
            {{ Location(3) }}	
        </div>	
    </div>
    {% if bIsDesignLayout %}
    <div class="page-location-footer">
        <span>
            {{ Translate('page.global_footer') }}
            <span>
                <a href="javascript:void(0);"  class="hide_on_this_page h-footer" df="{{ bIsHideFooter }}" t="footer">
                	{% if bIsHideFooter %} 
                	{{ Translate('page.show_on_this_page') }}
	                {% else %} 
	                	{{ Translate('page.hide_on_this_page') }}
	                {% endif %}
                </a>
            </span>
        </span>
    </div>
    {% endif %}
</div>
