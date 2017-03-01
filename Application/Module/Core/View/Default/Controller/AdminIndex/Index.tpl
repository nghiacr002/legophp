{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-md-4">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ Translate('core.system_information') }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-condensed no-border system_info">
                    <tbody>
                        <tr>
                            <td class="value-text">{{ Translate('core.name') }}</td>
                            <td style="width: 100px" class="value-info">{{ aSystemInformation.sName }}</td>
                        </tr>
                        <tr>
                            <td class="value-text">{{ Translate('core.version') }}</td>
                            <td style="width: 100px" class="value-info">{{ aSystemInformation.sVersion }}</td>
                        </tr>
                        <tr>
                            <td class="value-text">{{ Translate('core.php_version') }}</td>
                            <td style="width: 100px" class="value-info">{{ aSystemInformation.sPHPVersion }}</td>
                        </tr>
                        <tr>
                            <td class="value-text">{{ Translate('core.database_connector') }}</td>
                            <td style="width: 100px" class="value-info" >{{ aSystemInformation.aDatabaseInfo.connector }}</td>
                        </tr>
                        <tr>
                            <td class="value-text">{{ Translate('core.database_version') }}</td>
                            <td style="width: 100px" class="value-info">{{ aSystemInformation.aDatabaseInfo.version }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ Translate('core.system_stats') }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-condensed no-border">
                    <tbody>
                        {% for aStat in aStats %}
                        <tr>
                            <td class="value-text">{{ aStat.text }}</td>
                            <td   class="value-info">{{ aStat.value }}</td>
                        </tr>
                        {% endfor %}

                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class="col-md-8">
        <div class="box ">
            <div class="box-header with-border">
                <h3 class="box-title">{{ Translate('core.system_note') }}</h3>
            </div>
            <div class="box-body">
                <form action="" method="post" id="update_note_form">
                    <div class="control-group">
                        <textarea cols="" rows="" name="note_data" class="note_data" autocomplete="off">{{ oNote.note_description }}</textarea>
                    </div>
                    <input class="btn btn-primary" value="Save" id="update_note" autocomplete="off" type="submit">
                </form>
            </div>
        </div>
    </div>

</div>
{% endblock%}