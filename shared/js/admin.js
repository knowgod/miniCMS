/**
 * Administration area script
 */
$(function() {
    window.editPageEnable = function() {
        var oContent = $('#content');
        var oContentEditor = $('#content-editor');
        oContentEditor.text(oContent.html());

        oContentEditor.fadeIn();
        oContentEditor.height(oContent.height() * 1.2);
        oContent.fadeOut();

        $('#link-edit').fadeOut();
        $('#link-save').fadeIn();
    };
});