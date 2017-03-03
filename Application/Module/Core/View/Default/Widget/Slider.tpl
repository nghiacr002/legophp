
<div id="carosel-slider-{{widgetId}}" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
    	{% for key,sImg in aSliderImages %}
    		<li data-target="#carosel-slider-{{widgetId}}" data-slide-to="{{key}}" ></li>
    	{% endfor %}
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
		{% for key,sImg in aSliderImages %}
    		<div class="item {% if key == 0 %} active {% endif %}">
		        <img src="{{ sImg|image_path('origin')}}" alt="">
		     </div>
    	{% endfor %}
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#carosel-slider-{{widgetId}}" role="button" data-slide="prev">
      <span class="fa fa-angle-left"></span>
    </a>
    <a class="right carousel-control" href="#carosel-slider-{{widgetId}}" role="button" data-slide="next">
       <span class="fa fa-angle-right"></span>
    </a>
 </div>
 