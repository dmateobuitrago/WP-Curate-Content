jQuery(function($){
    function preview_content() {
        var ajax_url = ajax_product_params.ajax_url; //Get ajax url (added through wp_localize_script)
        var contentUrl = $('#content-url-input').val();
        $.ajax({
            type: 'GET',
            url: ajax_url,
            data: {
                action: 'ajax_preview_content',
                content_url: contentUrl
            },
            beforeSend: function ()
            {
                //You could show a loader here
                $('#loader').show();
            },
            success: function(data)
            {
                // Hide loader here
                setTimeout(function() {
                    $('#loader').hide();
                }, 500);
                $('#show-content').html(data);
            },
            error: function()
            {
              //If an ajax error has occured, do something 
              $("#show-content").html('Lo sentimos, hubo un error, intentelo más tarde.');
            }
        });
    }

    $('#preview_curated_content_url').on('submit', function(e){
        e.preventDefault();
        preview_content();
    });


    function post_new_content(){

    }

    $('#new_curated_content').on('submit', function(e){
        e.preventDefault();
        post_new_content()
    });
});