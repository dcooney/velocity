<?php
add_action( 'admin_head', 'velocity_admin_editor_head' );
add_action( 'wp_ajax_velocity_lightbox', 'velocity_ajax_tinymce' );
add_action( 'after_setup_theme', 'velocity_add_editor_style' );   
   
   
   
/**
 * velocity_add_editor_style
 * Add the editor css
 * @return void
 */
function velocity_add_editor_style() {
   add_editor_style( plugins_url( 'css/mce-editor.css' , __FILE__ ) );
}



/**
 * velocity_ajax_tinymce
 * calls the editor page
 * @return void
 */
function velocity_ajax_tinymce(){
	// check for rights
	if ( ! current_user_can('edit_pages') && ! current_user_can('edit_posts') )
		die( __("You are not allowed to be here", 'velocity') );

	$window = dirname(__FILE__) . '/popup.php';
	include_once( $window );

	die();
}



/**
 * velocity_admin_editor_head
 * calls your functions into the correct filters
 * @return void
 */
function velocity_admin_editor_head() {
	// check user permissions
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}
	
	// check if WYSIWYG is enabled
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'velocity_mce_external_plugins' );
		add_filter( 'mce_buttons', 'velocity_mce_buttons' );
	}
}



/**
 * velocity_mce_external_plugins 
 * Adds our tinymce plugin
 * @param  array $velocity_plugin_array 
 * @return array
 */
function velocity_mce_external_plugins( $velocity_plugin_array ) {
	$velocity_plugin_array['velocity'] = plugins_url( 'js/mce-button.js' , __FILE__ );
	return $velocity_plugin_array;
}



/**
 * velocity_mce_buttons 
 * Adds our tinymce button
 * @param  array $velocity_buttons 
 * @return array
 */
function velocity_mce_buttons( $velocity_buttons ) {
	array_push( $velocity_buttons, 'velocity' );
	return $velocity_buttons;
}


