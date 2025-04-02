jQuery(document).ready(function($) {
    $("button").click(() => {
        $.ajax({
            url: ctmsg_ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'ctmsg_ajax_action',
                data: $("#ctmsg-text").val()
            },
            success: function(){
                alert("Shortcode message has been saved.");
                }
            });
        })
    }
);