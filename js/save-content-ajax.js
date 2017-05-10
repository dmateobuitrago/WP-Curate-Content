jQuery(function($){
    function save_new_content() {
        var ajax_url = ajax_product_params.ajax_url; //Get ajax url (added through wp_localize_script)
        var contentUrl = $('#content-url-input').val();
        var contentTitle = $('#content_title').val();
        var contentDescription = $('#content_description').val();
        var contentTags = $('#content_keywords').val();
        var contentImgUrl = $('#content_image_url').val();
        var parentComunidad = $('#parent_comunidad').val();
        $.ajax({
            type: 'GET',
            url: ajax_url,
            data: {
                action: 'ajax_save_content',
                content_url: contentUrl,
                content_title: contentTitle,
                content_excerpt: contentDescription,
                content_tags: contentTags,
                content_image_url: contentImgUrl,
                parent_comunidad: parentComunidad
            },
            beforeSend: function ()
            {
                // You could show a loader here
                $('#loader').show();
            },
            success: function(data)
            {
                // Hide loader here
                setTimeout(function() {
                    $('#loader').hide();
                }, 500);
                $('#show-content').html(data);
                setTimeout(function() {
                    location.reload();
                }, 500);
            },
            error: function()
            {
              //If an ajax error has occured, do something 
              $("#show-content").html('Lo sentimos, hubo un error, intentelo más tarde.');
            }
        });
    }
    $(document).on('submit','#new_curated_content', function(e){
        e.preventDefault();
        save_new_content();
    });
});