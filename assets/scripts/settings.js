jQuery(document).ready(function ($) {
    $('.js-toggle-button').on('click', function () {
        toggleWidget(this);
    });

    function toggleWidget(thisContext) {
        $.ajax({
            url: umnico_ajax.ajax_url,
            type: 'post',
            data: {
                _ajax_nonce: umnico_ajax.nonce,
                action: 'toggle_widget',
            },
            success: function (data) {
                $(thisContext).text(data);
            }
        });
    }

});
