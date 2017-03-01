{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">{{ Translate('core.uploaded_files') }}</h3>
                <span class="box-tools pull-right media-toolbox" id="media-toolbox">

                </span>
            </div>
            <div class="box-body pad">
                <div id="media-items-holder" class="media-manager" path = "">

                </div>
            </div>
        </div>
    </div>
</div>
{% endblock %}