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
            'custom-fields' 
        )
    );

    register_post_type( 'content', $args );
}

add_action( 'init', 'wpcc_register_content_post_type');



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
?>