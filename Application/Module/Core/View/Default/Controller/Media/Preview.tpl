<div class="file-preview">
    <div class="thumb-view">
        <a href="javascript:void(0)" title="{{ Translate('core.remove_selected_file') }}" class="remove-item" path="{{ aFileInfo.absolute_path_2}}"><i class="fa fa-trash"></i></a>
            {% if aFileInfo.thumb %}
        <img src="{{ aFileInfo.thumb}}" />
        {% else %}
        <i class="fa fa-file-o no-file-preview"></i>
        {% endif %}
    </div>
    <div class="file-detail">
        <div class="info-row">
            <span class="title-info">{{ Translate('core.file_name') }}</span>
            <span class="text-info">{{ aFileInfo.title }}</span>
        </div>
        <div class="info-row">
            <span class="title-info">{{ Translate('core.file_size') }}</span>
            <span class="text-info">{{ aFileInfo.file_size_view }}</span>
        </div>
        <div class="info-row">
            <span class="title-info">{{ Translate('core.last_modified') }}</span>
            <span class="text-info">{{ aFileInfo.time_view }}</span>
        </div>
        <div class="info-row">
            <span class="title-info">{{ Translate('core.path') }}</span>
            <span class="text-info">{{ aFileInfo.absolute_path }}</span>
        </div>
    </div>
</div>