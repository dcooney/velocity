<?php
/*
Plugin Name: Velocity
Plugin URI: https://connekthq.com/plugins/velocity/
Description: Speed up your website by lazy loading your media embeds.
Author: Darren Cooney
Twitter: @KaptonKaos
Author URI: https://connekthq.com
Version: 1.0
License: GPL
Copyright: Darren Cooney & Connekt Media

*/


/*
*  velocity_install
*  Install the plugin
*
*  @since 1.0
*/

register_activation_hook( __FILE__, 'velocity_install' );
function velocity_install() {   
   // Nothing
}



if( !class_exists('velocity') ):
   class velocity{	  
      
      public $counter = 0;
       
   	function __construct(){		      	
      	
   		define('VELOCITY_VERSION', '1.0');
   		define('VELOCITY_RELEASE_DATE', 'March 3, 2016');
   		define('VELOCITY_NAME', 'Velocity');
   		define('VELOCITY_TAGLINE', 'Improve website performance by lazy loading and customizing your media embeds');
   		define('VELOCITY_PATH', plugin_dir_path(__FILE__));
   		define('VELOCITY_URL', plugins_url('', __FILE__));
   		define('VELOCITY_PLACEHOLDER', plugins_url('/core/img/placeholder.gif', __FILE__));
   		 
         add_action( 'admin_menu', array(&$this, 'velocity_admin_menu')); // Admin Menu
   		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'velocity_action_links') );
         add_action( 'wp_enqueue_scripts', array(&$this, 'velocity_enqueue_scripts')); // Scripts         
         add_action( 'admin_enqueue_scripts', array(&$this, 'velocity_enqueue_admin_scripts' )); // Admin scripts
         add_action( 'admin_head', array(&$this, 'velocity_admin_vars' )); // Localized Variables	  
         add_action( 'wp_ajax_velocity_get_image', array(&$this, 'velocity_get_image')); // Get image w/ Ajax
         add_action( 'wp_ajax_velocity_save_options', 'velocity_save_options' ); // Ajax Save Options
         add_shortcode( 'velocity', array(&$this, 'velocity_shortcode')); // Shortcode
         
   		load_plugin_textdomain( 'velocity', false, dirname(plugin_basename( __FILE__ )).'/lang/'); //load text domain   		
         // includes WP admin core
   		$this->velocity_before_theme();
   	   
   	}  
   	
   	
   	/*
   	*  velocity_before_theme
   	*  Load these files before the theme loads
   	*
   	*  @since 1.0.0
   	*/
   	
   	public function velocity_before_theme(){
   		if( is_admin()){
   			include_once('admin/builder/builder.php');
   		}		
      }
      
   	
   	
   	//The CTA shortcode   
   	public function velocity_shortcode( $atts) {
      	$this->counter++;
      	wp_enqueue_script( 'velocity' );
   		$atts = shortcode_atts( array(
      		'type'   => 'youtube',
      		'id'     =>  null,
      		'img'    =>  null,
      		'alt'    =>  __('Play', 'velocity'),
      		'color'    => '',
      		'bkg_color'    => ''
      	), $atts, 'velocity' );
         
         // Set vars
         $type = $atts['type'];
         $img = $atts['img'];
         $btn = false;
         $btn_color = $atts['color']; 
         $btn_bkg_color = $atts['bkg_color']; 
         
         if(!empty($btn_color) && !empty($btn_bkg_color)){
            $btn = true;
         }
                 
         // Locate img file       
         if(!isset($img) || empty($img)){
            $default = get_option('velocity_placeholder');
            if(isset($default) && !empty($default)){
               $img = $default;
            }else{
               $img = VELOCITY_PLACEHOLDER;
            }
         }  
         
         if(!empty($img) && !empty($type)){    
			
				$return  = '<div class="velocity-embed">';
							
	         $return .= '<a href="#" data-video-type="'. esc_attr($type) .'" data-video-id="'. esc_attr($atts['id']) .'">';
	         $return .= '<img class="velocity-img aligncenter" src="'. esc_url($img) .'" alt="'. esc_attr($atts['alt']) .'" />';
	         if($btn){
	            $return .= '<span class="velocity-play-btn" style="background-color: '. esc_attr($btn_bkg_color) .'"><span class="velocity-arrow" style="border-left-color: '. esc_attr($btn_color) .';"></span></span>'; 
	         }
	         $return .= '</a>';         
	         $return .= '<div class="velocity-target"></div>';
	         
	         $return .= '</div>';
	         
	         if($this->counter === 1){ // only render once
	            $return .= '<style>';
	            $return .= '.velocity-embed {overflow: hidden !important; display: block; margin: 0 0 20px;} .velocity-target .fluid-width-video-wrapper {padding-top: 56% !important; background: #f7f7f7 url('.VELOCITY_URL.'/core/img/ajax-loader.gif) no-repeat center center;}';
	            $return .= '.velocity-embed iframe {max-width: 100%;}';
	            $return .= '.velocity-embed img{padding: 0; margin: 0; border: none;}';
	            $return .='</style>';
	         }
	         
	         if($btn){
	            $return .= '<style>';
	            
	            $return .= '.velocity-embed a{position: relative; display: block;}';
	            
	            $return .= '.velocity-embed .velocity-arrow {display: block; position: absolute; z-index: 10;  top: 50%; left: 50%; margin-top: -17px; margin-left: -9px; width: 0; height: 0; border-top: 18px solid transparent; border-bottom: 18px solid transparent; border-left: 26px solid transparent;}';
	            
	            $return .= '.velocity-embed .velocity-play-btn {display: block; position: absolute; z-index: 9;  top: 50%; left: 50%; margin-top: -50px; margin-left: -50px; width: 100px; height: 100px; border-radius: 80px; -webkit-border-radius: 80px; -moz-border-radius: 80px; opacity: 0.4;-webkit-transition: all 0.3s ease; -moz-transition: all 0.3s ease; transition: all 0.3s ease;}';
	            
	            $return .= '.velocity-embed a:hover .velocity-play-btn{opacity: 0.8; -webkit-transform: scale(1.1); -moz-transform: scale(1.1); transform: scale(1.1);}';
	           
	            $return .='</style>';
	         }  
	         
         }
         
         return $return;	
      }
      
      
      /*
      *  velocity_get_image
      *  Get image src from id and size.
      *
      *  @since 1.0.0
      */
      function velocity_get_image(){
         if (current_user_can( 'edit_posts' ) && current_user_can('edit_pages')){
      	
      		$nonce = sanitize_text_field($_GET['nonce']);
      		$id = sanitize_text_field($_GET['id']);
      		$size = sanitize_text_field($_GET['size']);
      		
      		// Check our nonce, if they don't match then bounce!
      		if (! wp_verify_nonce( $nonce, 'velocity_nonce' ))
      			die('Error - unable to verify nonce, please try again.');			
      		
      		$img = wp_get_attachment_image_src( $id, $size ); // Get image path
            $img = $img[0];
      		
      		echo $img;      		
            
            die();
         }
      }
   	
   	
   	
   	/*
   	*  velocity_enqueue_scripts
   	*  Enqueue our scripts
   	*
   	*  @since 1.0
   	*/
   
   	public function velocity_enqueue_scripts(){   		
   		//wp_register_script( 'velocity', plugins_url( '/core/js/velocity.js', __FILE__ ), array('jquery'),  '1.0', true );  
   		wp_register_script( 'velocity', plugins_url( '/core/js/velocity.min.js', __FILE__ ), array('jquery'),  '1.0', true );   		
   	}	
   	
   	
   	
   	/*
      *  velocity_admin_menu
      *  Create admin menu item
      *
      *  @since 1.0.0
      */
   	public function velocity_admin_menu() {         
         add_submenu_page( 
            'options-general.php', 
            'Velocity', 
            'Velocity', 
            'edit_theme_options', 
            'velocity', 
            'velocity_settings_callback'
         );
      }         
      
      
      
      /*
      *  velocity_enqueue_admin_scripts
      *  Enqueue admin scripts
      *
      *  @since 1.0.0
      */
      public function velocity_enqueue_admin_scripts(){               
         wp_enqueue_style( 'velocity-font-awesome', VELOCITY_URL. '/admin/css/font-awesome.min.css');
         wp_enqueue_style( 'velocity-admin', VELOCITY_URL. '/admin/css/admin.css');
   		wp_enqueue_script( 'velocity-builder', VELOCITY_URL. '/admin/builder/js/builder.js', array('jquery'),  '1.0', true );   
      }   
      
      
      
      /*
      *  velocity_admin_vars
      *  Create admin variables for Velocity
      *
      *  @since 1.0.0
      */
      function velocity_admin_vars() { ?>
         <script type='text/javascript'>
         /* <![CDATA[ */
         var velocity_localize = <?php echo json_encode( array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'velocity_nonce' => wp_create_nonce( 'velocity_nonce' ),
            'pluginurl' => VELOCITY_URL,
            'image_select' => __('Select Image', 'velocity'),
         )); ?>
         /* ]]> */
         </script>
      <?php }     
	      
	      
	      
	   /*
   	*  velocity_action_links
   	*  Add plugin action links to WP plugin screen
   	*
   	*  @since 1.0
   	*/   
      
      function velocity_action_links( $links ) {
         $links[] = '<a href="'. get_admin_url(null, 'options-general.php?page=velocity') .'">'.__('Settings', 'velocity').'</a>';
         $links[] = '<a href="'. get_admin_url(null, 'options-general.php?page=velocity&vb=true') .'">'.__('Builder', 'velocity').'</a>';
         return $links;
      }    
         
   }     
   	
   	
   // Settings screen	
   function velocity_settings_callback(){   
      include_once( VELOCITY_PATH . 'admin/views/settings.php');   
   }
      
      
   // AJAX Save options!
   function velocity_save_options(){
      if (current_user_can( 'edit_theme_options' )){
		
   		$nonce = sanitize_text_field($_POST["nonce"]);
   		$image_url = sanitize_text_field($_POST["image_url"]);
   		
   		// Check our nonce, if they don't match then bounce!
   		if (! wp_verify_nonce( $nonce, 'velocity_nonce' ))
   			die('Error - unable to verify nonce, please try again.');			
   		
   		update_option('velocity_placeholder', $image_url);
   		
         echo __('Settings Updated!', 'framework');
         
         die();
      }
   }
   
   
   // get image dimensions
   function velocity_get_image_dimensions( $name ) {
   	global $_wp_additional_image_sizes;
   
   	if ( isset( $_wp_additional_image_sizes[$name] ) )
   		return $_wp_additional_image_sizes[$name];
   
   	return false;
   }
   	
   	
   	
   /*
   *  velocity
   *  The main function responsible for returning our plugin class
   *
   *  @since 1.0
   */	
   
   function velocity(){
   	global $velocity;
   
   	if( !isset($velocity) ){
   		$velocity = new velocity();
   	}
   
   	return $velocity;
   }
   
   // initialize
   velocity();

endif; // class_exists check