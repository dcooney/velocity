<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Velocity: Shortcode Builder</title>
<link rel='stylesheet' href='<?php echo admin_url(); ?>/load-styles.php?c=1&amp;dir=ltr&amp;load=dashicons,admin-bar,buttons,media-views,wp-admin,wp-auth-check&amp;ver=4.4.2' type='text/css' />
<link rel="stylesheet" href="<?php echo VELOCITY_URL; ?>/admin/css/font-awesome.min.css" />
<link rel="stylesheet" href="<?php echo VELOCITY_URL; ?>/admin/css/admin.css" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.js"></script>
<script type="text/javascript" src="<?php echo includes_url('/js/tinymce/tiny_mce_popup.js'); ?>"></script>
<script type="text/javascript">  
   var velocityModal = {
   	local_ed : 'ed',
   	init : function(ed) {
   		velocityModal.local_ed = ed;
   		tinyMCEPopup.resizeToInnerSize();
   	},
   	insert : function insertButton(ed) {	    		
   		tinyMCEPopup.execCommand('mceRemoveNode', false, null); // Try and remove existing style / blockquote   		
   		output = $('#velocity-shortcode-output').text(); // setup the output of our shortcode to show in the wp editor
   		tinyMCEPopup.execCommand('mceInsertContent', false, output);			 
   		tinyMCEPopup.close();// Return
   	}
   };
   tinyMCEPopup.onInit.add(velocityModal.init, velocityModal); 
</script>
   <?php 
      wp_enqueue_media(); 
   ?>
</head>
<body id="velocity-builder-body">
   <div id="velocity-builder">
      <div id="velocity-editor-container" class="velocity velocity-plugin-wrap wp-core-ui">                  
         <?php include VELOCITY_PATH .'admin/builder/elements.php' ;?>      	
      </div>	
   </div>
   <?php wp_footer(); ?>   
   <script type="text/javascript" src="<?php echo VELOCITY_URL; ?>/admin/builder/js/builder.js""></script>
</body>
</html>