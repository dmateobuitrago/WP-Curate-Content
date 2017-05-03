<?php
/*
Plugin Name: Curate Content
Plugin URI:  https://pappcorn.com
Description: Allow users to save content from other websites
Version:     1.0
Author:      Mateo Buitrago
Author URI:  https://github.com/dmateobuitrago
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wp-curate-content
*/

if( ! defined ('ABSPATH')){
    exit();
}

// Add styles and scripts

add_action( 'wp_enqueue_scripts', 'enqueue_my_styles' );
function enqueue_my_styles() {
    wp_enqueue_style('curated-content-styles', plugin_dir_url( __FILE__ ) . 'css/cc-styles.css' );
}

//REGISTER POST TYPE TO SAVE CONTENT
function wpcc_register_content_post_type(){
    $singular = 'Contenido Curado';
    $plural = 'Contenidos Curados';

    $slug = str_replace( ' ', '_', strtolower( $singular ) );

    $labels = array(
        'name'                  => $singular,
        'singular_name'         => $singular,
        'add_name'              => 'Agregar nuevo',
        'add_name_item'         => 'Agregar nuevo ' . $singular,
		'edit'		            => 'Editar',
		'edit_item'	            => 'Editar ' . $singular,
		'new'	                => 'Añadir Nuevo ',
		'new_item'	            => 'Añadir Nuevo ' . $singular,
		'view' 			        => 'Ver' . $singular,
		'view_item' 		    => 'Ver' . $singular,
		'search_term'   	    => 'Buscar ' . $plural,
		'parent' 		        => 'Parent ' . $singular,
		'not_found' 		    => 'No encotramos ' . $plural,
		'not_found_in_trash' 	=> 'No encontramos ' . $plural .' en la papelera'
    );

    $args = array(
        'public' => true,
        'labels'  => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'exclude_from_search' => false,
        'show_in_nav_menus'   => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 10,
        'menu_icon'           => 'dashicons-media-code',
        'can_export'          => true,
        'delete_with_user'    => false,
        'hierarchical'        => false,
        'has_archive'         => true,
        'query_var'           => true,
        'capability_type'     => 'post',
        'map_meta_cap'        => true,
        'taxonomies' => array('post_tag'),
        // 'capabilities' => array(),
        'rewrite'             => array( 
            'slug' => $slug,
            'with_front' => true,
            'pages' => true,
            'feeds' => true,
        ),
        'supports'            => array( 
            'title', 
            'editor', 
            'author', 
            'custom-fields',
            'thumbnail'
        )
    );

    register_post_type( 'content', $args );
}

add_action( 'init', 'wpcc_register_content_post_type');


// ADD PAGE TEMPLATE
add_filter( 'template_include', 'submit_content_page_template', 99 );

function submit_content_page_template( $template ) {
    $file_name = 'submit-content.php';

    if ( is_page( 'agregar-contenido' ) ) {
        if ( locate_template( $file_name ) ) {
            $template = locate_template( $file_name );
        } else {
            // Template not found in theme's folder, use plugin's template as a fallback
            $template = dirname( __FILE__ ) . '/templates/' . $file_name;
        }
    }

    return $template;
}


//Preview content from other website
// REGISTER AJAX
//Enqueue Ajax Scripts
function enqueue_ajax_preview_content() {
  wp_register_script( 'preview-content-ajax', plugins_url('wp-curate-content/js/preview-content-ajax.js'), array( 'jquery' ), '', true );
  wp_localize_script( 'preview-content-ajax', 'ajax_product_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
  wp_enqueue_script( 'preview-content-ajax' );
}
add_action('wp_enqueue_scripts', 'enqueue_ajax_preview_content');

//Add Ajax Actions
add_action('wp_ajax_ajax_preview_content', 'ajax_preview_content');
add_action('wp_ajax_nopriv_ajax_preview_content', 'ajax_preview_content');


function ajax_preview_content(){
    $query_data = $_GET;
	$content_url = $query_data['content_url'];

    function file_get_contents_curl($url){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    $html = file_get_contents_curl($content_url);

    //parsing begins here:
    $doc = new DOMDocument();
    @$doc->loadHTML($html);
    $nodes = $doc->getElementsByTagName('title');

    //get and display what you need:
    $title = $nodes->item(0)->nodeValue;

    $metas = $doc->getElementsByTagName('meta');

    for ($i = 0; $i < $metas->length; $i++)
    {
        $meta = $metas->item($i);
        if($meta->getAttribute('name') == 'description')
            $description = $meta->getAttribute('content');
        if($meta->getAttribute('name') == 'keywords')
            $keywords = $meta->getAttribute('content');
        if($meta->getAttribute('property') == 'og:url')
            $url = $meta->getAttribute('content');
        if($meta->getAttribute('property') == 'og:image')
            $image = $meta->getAttribute('content');
    }    
    
    $title = utf8_decode($title);
    $description = utf8_decode($description);
    $keywords = utf8_decode($keywords);
    ?>
        <div class="curated-content-preview">
            <form action="" id="new_curated_content">
                <?php if( strlen($image) > 0 ): ?>
                    <input type="hidden" name="content_image_url" id="content_image_url" value="<?php echo  $image ?>">
                    <div class="image_preview" style="background: url(<?php echo  $image ?>); background-size: cover;"></div>
                <?php endif; ?>
                <div class="field_container">
                    <label for="content_title">Título</label>
                    <input type="text" name="content_title" id="content_title" value="<?php echo  $title ?>">
                </div>
                <div class="field_container">
                    <label for="content_description">Descripción</label>
                    <textarea name="content_description" id="content_description" ><?php echo  $description ?></textarea>
                </div>
                <div class="field_container">
                    <label for="content_keywords">Palabras Clave</label>
                    <input type="text" name="content_keywords" id="content_keywords" value="<?php echo  $keywords ?>">
                </div>
                <input type="hidden" name="content_url" id="content_url" value="<?php echo  $url ?>">
                <input type="submit" id="submit_curated_content" value="Guardar nuevo contenido">
            </form>
        </div>    
    
    <?php
    die();
}

//Save content
// REGISTER AJAX
//Enqueue Ajax Scripts
function enqueue_ajax_save_content() {
  wp_register_script( 'save-content-ajax', plugins_url('wp-curate-content/js/save-content-ajax.js'), array( 'jquery' ), '', true );
  wp_localize_script( 'save-content-ajax', 'ajax_product_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
  wp_enqueue_script( 'save-content-ajax' );
}
add_action('wp_enqueue_scripts', 'enqueue_ajax_save_content');

//Add Ajax Actions
add_action('wp_ajax_ajax_save_content', 'ajax_save_content');
add_action('wp_ajax_nopriv_ajax_save_content', 'ajax_save_content');


function ajax_save_content(){ 
    $query_data = $_GET;
	$content_url = $query_data['content_url'];
	$content_title = $query_data['content_title'];
	$content_excerpt = $query_data['content_excerpt'];
	$content_tags = $query_data['content_tags'];
	$content_image_url = $query_data['content_image_url'];

    $post_information = array(
        'post_title' => $content_title,
        'post_content' => $content_excerpt,
        'post_excerpt' => $content_excerpt,
        'post_type' => 'content',
        'post_status' => 'publish'
    );
 
    $post_id = wp_insert_post( $post_information );
    wp_set_post_tags( $post_id, $content_tags );

    function new_attachment( $att_id ){
        // the post this was sideloaded into is the attachments parent!

        // fetch the attachment post
        $att = get_post( $att_id );

        // grab it's parent
        $post_id = $att->post_parent;

        // set the featured post
        set_post_thumbnail( $post_id, $att_id );
    }

    // add the function above to catch the attachments creation
    add_action('add_attachment','new_attachment');

    // load the attachment from the URL
    media_sideload_image($content_image_url, $post_id, $content_title);

    // we have the image now, and the function above will have fired too setting the thumbnail ID in the process, so lets remove the hook so we don't cause any more trouble 
    remove_action('add_attachment','new_attachment');
    die();
}
?>