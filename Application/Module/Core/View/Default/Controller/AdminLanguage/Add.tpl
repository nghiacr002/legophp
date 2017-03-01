{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
    	
        <div class="box">
           <form method="post" action="{{ Template_Url('core/language/add',{ admincp:true} ) }}" id="language-search">
            <!-- /.box-header -->
           <div class="box-header with-border">
                <h3 class="box-title ">
                    <span class="ucfirst">
                        {{ Translate('core.add_phrase') }}
                    </span> 
                </h3>
            </div>
            <div class="box-body  ">
             	<div class="form-group">
				    <label for="phrase_var"><span class="required">*</span>{{ Translate('core.phrase_var') }}</label> 
				    <input type="text" id="phrase_var" class="form-control" name="phrase_var" value="" required >
				</div>
				{% for key,oLanguage in aLanguages %}
					<div class="form-group">
					    <label for="slug"><span class="language-code">[{{ oLanguage.language_code}}]</span>{{ Translate('core.phrase_value') }}</label> 
					    <textarea class="form-control" name="phrase_value[{{ oLanguage.language_code}}]"></textarea>
					</div>
				{% endfor %}
            </div>
           
            <div class="box-footer">
            	<input type="submit" class="btn btn-success" value="{{ _TL('core.submit') }}" name="submit"/>
            </div>
            </form>
        </div>
        <!-- /.box -->
    </div>
</div>
{% endblock %}