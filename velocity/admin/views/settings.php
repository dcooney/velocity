<?php
   $builder = false;
   if (isset($_REQUEST['vb'])){   
     if(empty($_REQUEST['vb'])){
        $builder = false;
     }else{
        $builder = true;
     }  
  }

?>

<div class="wrap velocity-plugin-wrap metabox-holder">
   <hgroup class="title">
      <h1><?php echo VELOCITY_NAME; ?> <span>v<?php echo VELOCITY_VERSION; ?></span></h1>
      <p class="tagline"><?php echo VELOCITY_TAGLINE; ?>.</p>
   </hgroup>
   <div class="velocity-main">     
      <?php 
         wp_enqueue_script('jquery');
         wp_enqueue_media();
      ?> 
      <h2 class="nav-tab-wrapper">
         <a class="nav-tab<?php if(!$builder){ echo ' nav-tab-active'; }?>" href="<?php echo admin_url() ?>options-general.php?page=velocity"><?php _e('Settings', 'velocity'); ?></a>
         <a class="nav-tab<?php if($builder){ echo ' nav-tab-active'; }?>" href="<?php echo admin_url() ?>options-general.php?page=velocity&vb=true"><?php _e('Velocity Builder', 'velocity'); ?></a>
      </h2> 
      
      <div class="velocity-content-toggle-wrap">
         <?php 
            // Settings 
            if(!$builder){
         ?>     
         <div class="content-toggle settings" id="velocity-builder">
            <div class="item-wrap last">
               <h3 class="clickable"><a href="javascript: void(0);"><?php _e('Velocity Settings', 'velocity'); ?></a></h3>
               <div class="expand-wrap">
                  <div class="inner clearfix">      
                     <div class="input-selection">
                        <label for="image_url"><?php _e('Default Velocity Image', 'velocity'); ?>
                           <span><?php _e('Will be used if an image has not been attached to the Velocity shortcode', 'framework'); ?>.</span>
                        </label>
                        <?php $placeholder = get_option('velocity_placeholder'); ?>
                        <input type="text" name="image_url" id="image_url" class="readonly" readonly="readonly" disabled="disabled" value="<?php if(isset($placeholder)){ echo $placeholder;
                  } ?>"><br/>
                        <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="<?php _e('Select Image', 'velocity'); ?>" style="margin-top: 10px;">
                        <div class="clear"></div>
                        <div id="img-preview">
                        <?php if(isset($placeholder) && !empty($placeholder)){ ?>
                           <img class="uploaded" src="<?php echo esc_url($placeholder); ?>" />
                           <a class="clear-img" href="javascript:void(0);">&times;</a>
                        <?php } else { ?>
                           <img src="<?php echo esc_url(VELOCITY_PLACEHOLDER); ?>" />              
                        <?php } ?>
                        </div> 
                        <div class="clear"></div>                        
                     </div>
                     <div class="input-selection">
                        <button class="button button-primary" id="submit-options" type="button"><?php _e('Save Settings', 'velocity'); ?></button>
                     </div>
                  </div>
                  <div class="velocity-loading"></div>
               </div>
            </div>
         </div>
         <?php 
            }
            // End Settings 
            ?>
         
         <?php 
            // Shortcode Builder
            if($builder){             
         ?>
         <div class="content-toggle" id="velocity-builder">         
            <?php include VELOCITY_PATH .'admin/builder/elements.php' ;?>
         </div> 
         <?php 
            }
            // End Shortcode Builder 
         ?>
         
      </div>
      
   </div>
   <div class="velocity-sidebar">
      
      <div class="postbox">
         <h3 class=""><span><?php _e('Features of Velocity', 'velocity'); ?></span></h3>
         <div class="inside">
	         <ul>
		         <li><strong>Improve Performance</strong><br/>Improve your website loading time by lazy loading YouTube, Vimeo and SoundCloud media on-demand only when requested by a user.<br/><br/>
		         </li>		         
		         <li><strong>Beautification</strong><br/>Take full control over the look and feel of your media preview images - style your previews exactly how you want to get maximum views and user engagement.<br/><br/>
		         </li>	         
		         <li><strong>Fully Responsive</strong><br/>Velocity will scale your media and preview images to fit the screen size of your users. 
		         </li>
	         </ul>
         </div>
         <div id="major-publishing-actions">
            <p><a href="https://connekthq.com/plugins/velocity/<?php echo $campaign; ?>Velocity" target="_blank" style="font-weight: 600;"><?php _e('Visit Velocity Homepage', 'velocity'); ?></a></p>
            <div class="clear"></div>
         </div>
      </div>
      
      <div class="postbox">
         <h3 class=""><span><?php _e('Other WordPress Plugins', 'velocity'); ?></span></h3>
         <div class="inside velocity-links">
	         <?php $campaign = '?utm_source=WP%20Admin&utm_medium=Velocity%20Settings&utm_campaign='; ?>
            <ul>
               <li>
               	<a href="https://connekthq.com/plugins/ajaxloadmore/<?php echo $campaign; ?>ALM" target="_blank">
	               	<img src="<?php echo esc_url(VELOCITY_URL); ?>/admin/img/plugins/alm-logo-48x48.png" />
                  	<h4>Ajax Load More</h4>
							<p>A powerful plugin to add infinite scroll functionality to your website.</p>
               	</a>
               </li>
               <li>
               	<a href="https://connekthq.com/plugins/easy-query/<?php echo $campaign; ?>EasyQuery" target="_blank">
	               	<img src="<?php echo esc_url(VELOCITY_URL); ?>/admin/img/plugins/eq-logo-48x48.png" />
                  	<h4>Easy Query</h4>
							<p>A simple solution to build and display WordPress queries without touching a single line of code.</p>
               	</a>
               </li>
               <li>
               	<a href="https://connekthq.com/plugins/unsplash-wp/<?php echo $campaign; ?>Unsplash" target="_blank">
	               	<img src="<?php echo esc_url(VELOCITY_URL); ?>/admin/img/plugins/usp-logo-48x48.png" />
                  	<h4>UnsplashWP</h4>
							<p>The fastest way to upload stock photos directly from unsplash.com to your WP media library</p>
               	</a>
               </li>
            </ul>
         </div>
         
         <div id="major-publishing-actions">
            <p>Brought to you by <a href="https://connekthq.com/<?php echo $campaign; ?>cnkt" target="_blank" style="font-weight: 600;">ConnektHQ</a></p>
            <div class="clear"></div>
         </div>
      </div>
      
   </div>
   <div class="loader"></div>
</div>

<script type="text/javascript">
   
   jQuery(document).ready(function($){
       
       // Upload Btn
       $('#upload-btn').click(function(e) {
           e.preventDefault();
           var image = wp.media({ 
               title: velocity_localize.image_select,
               // mutiple: true if you want to upload multiple files at once
               multiple: false
           }).open()
           .on('select', function(e){
               // This will return the selected image from the Media Uploader, the result is an object
               var uploaded_image = image.state().get('selection').first();
               // We convert uploaded_image to a JSON object to make accessing it easier
               // Output to the console uploaded_image
               console.log(uploaded_image);
               var image_url = uploaded_image.toJSON().url;
               // Let's assign the url value to the input field
               $('#image_url').val(image_url); // Set value               
               $('#img-preview img').attr('src', image_url); // Change preview
           });
       });
       
       // On change
      $('#image_url').on('input',function(e){
         $('#img-preview img').attr('src', $('#image_url').val());
      });       
       
      // Clear Image
      $('a.clear-img').click(function(){
         $('#image_url').val('');
         $('#img-preview img').attr('src', '<?php echo esc_url(VELOCITY_PLACEHOLDER); ?>');
      });      
          
       // Submit Form
       $('#submit-options').click(function(){  
          $('.content-toggle.settings .velocity-loading').fadeIn(200);  
          $.ajax({
				type: 'POST',
				url: velocity_localize.ajaxurl,
				data: {
					action: 'velocity_save_options',
					image_url: $('#image_url').val(), 
					nonce: velocity_localize.velocity_nonce,
				},
				success: function(response) {											  		
				  //console.log(response);
				  location.reload(); 															
				},
				error: function(xhr, status, error) {
				   console.log(status);
              $('.content-toggle.settings .velocity-loading').fadeOut(200); 
				}
         });                    
       });
       
   });
</script>