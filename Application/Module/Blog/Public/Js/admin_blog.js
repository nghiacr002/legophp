ADMIN_BLOG = {
    init: function () {
        $('#blog_title').slug({});
        CORE.editor('#blog_description', true);
    }
};
$(document).ready(function () {
    ADMIN_BLOG.init();
});