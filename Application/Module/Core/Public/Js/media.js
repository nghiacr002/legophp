MEDIA_ADMIN = {
    init: function () {
        $("#media-items-holder").fxMediaManager({
            // Url absolute of file conector,
            url: CORE.params['sBaseUrl'] + 'core/media/browse',
            height: 390,
            views: 'thumbs',
            insertButton: true,
            token: 'jashd4a5sd4sa',
            buttonHolder: '#media-toolbox',
            upload: {
                url: CORE.params['sBaseUrl'] + 'core/media/upload',
                multiple: true,
                maxFileCount: 3,
            },
            deleteUrl: CORE.params['sBaseUrl'] + 'core/media/delete',
        });
    }
}
$(document).ready(function () {
    MEDIA_ADMIN.init();
});