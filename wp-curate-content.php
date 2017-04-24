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
    $args = array(
        'public' => true,
        'label'  => 'Content'
    );

    register_post_type( 'content', $args );
}

add_action( 'init', 'wpcc_register_content_post_type');
?>