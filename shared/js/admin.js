/**
 * Administration area script
 */
$(function() {
    window.editPageEnable = function() {
        var oContent = $('#content');
        var oContentEditor = $('#edit_page_content');

        oContentEditor.text($('.page_content').html());

        $('#page-editor').fadeIn()
        oContentEditor.height(oContent.height());
        oContent.fadeOut();

        $('#link-edit').fadeOut();
    };

    window.editPageCancel = function() {
        var oContent = $('#content');
        $('#page-editor').fadeOut()
        oContent.fadeIn();
        $('#link-edit').fadeIn();
    };
});