{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
    	<div class="box">
            <div class="box-body">
                <form id="user-browse-filter" action='{{ Template_Url('core/language/customize',{"admincp":true}) }}' method="get">
                    {{ oFilter.render() }}
                </form>
            </div>
        </div>
        <div class="box">
           <form method="post" action="" id="language-search">
            <!-- /.box-header -->
            {% if bHasSearch %}
            <div class="box-header">
                <h3 class="box-title">
                    <span class="ucfirst">
                        {{ Translate('core.search_results') }}
                    </span> 
                </h3>

            </div>
            {% endif %}
            <div class="box-body no-padding ">
             	{% if aFoundItems|length > 0 %}
                <div class="table-responsive">
                    
                       	<table class="table table-hover table-bordered">
                       		<thead>
                       			<tr>
                       				<th style="width:5%">
                       					{{ Translate('core.language') }}
                       				</th>
                       				<th style="width:30%;">
                       					{{ Translate('core.phrase_var') }}
                       				</th>
                       				<th>
                       					{{ Translate('core.phrase_value') }}
                       				</th>
                       				
                       			</tr>
                       		</thead>
	                        <tbody>
	                        	{%for aItem in aFoundItems %}
	                            <tr>
	                            	<td>
	                            		{{ aItem.language}}
	                            	</td>
	                            	<td>
	                            		{{ aItem.key }}
	                            	</td>
	                            	<td>
	                            		<input type="text" class="form-control" value="{{ aItem.value }}" name="val[{{ aItem.language}}|{{ aItem.key }}]"/>
	                            	</td>
	                            </tr>
	                            {% endfor %}
	                        </tbody>
                        </table>
                   
                </div>
                {% else %}
                	<div class="alert alert-warning" style="margin:5px 10px;">{{ Translate('core.no_items_found') }}</div>
                	
                {% endif %}
            </div>
            {% if aFoundItems|length > 0 %}
            <div class="box-footer">
            	<input type="submit" class="btn btn-success" value="{{ _TL('core.submit') }}" name="submit"/>
            </div>
            {% endif %}
            </form>
        </div>
        <!-- /.box -->
    </div>
</div>
{% endblock %}