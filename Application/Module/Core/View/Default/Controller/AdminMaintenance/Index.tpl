{% extends "Master.tpl"%}
{% block content %}
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <span class="ucfirst">
                        {{ Translate('core.folders') }}
                    </span> 
                </h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding ">
                <div class="table-responsive">
                    <form method="post" action="" >
                        <table class="table table-hover table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width:30px"><input type="checkbox" value="1"  id="select_all" bind="selectall"/></th>
                                    <th >{{ Translate('core.name') }}</th>
                                    <th >{{ Translate('core.last_update') }}</th>
                                    <th >{{ Translate('core.total_file') }}</th>
                                    <th >{{ Translate('core.total_file_size') }}</th>
                                    <th style="width:100px;"></th>

                                </tr>
                                {% for key,aFolder in aMaintainFolders %}
                                <tr class="folder-row" id="folder-row-{{ key }}">

                                    <td><input type="checkbox" value="{{ aFolder.path }}" name="path[]" class="selectall"/></td>
                                    <td>{{ key }}</td>
                                    <td>{{ aFolder.last_modified|date_format('M d Y h:i:s') }}</td>
                                    <td class="total-file">N/A</td>
                                    <td  class="total-size">N/A</td>
                                    <td>
                                        <a href="javascript:void(0)" title="{{ Translate('core.calculate') }}" class="btn btn-success calculate-folder"><i class="fa fa-calculator"></i></a>
                                        <a href="javascript:void(0)" title="{{ Translate('core.clean_folder') }}" class="btn btn-danger empty-folder"><i class="fa fa-trash"></i></a>
                                    </td>

                                </tr>
                                {% endfor%}

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" align="left">
                                        <input class="btn btn-danger" value="{{ Translate('core.clean_selected') }}" type="submit"/>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </div>
</div>
{% endblock %}