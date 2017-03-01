$(document).ready(function () {
    THEME.init();
});
THEME = {
    init: function () {
        $('input,select,radio').attr('autocomplete', 'off');
    }
}