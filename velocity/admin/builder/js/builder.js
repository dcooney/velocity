var velocityBuilder = velocityBuilder || {};

(function($){ 

   velocityBuilder.speed = 175,
       output_div = $('#velocity-shortcode-output'),
       output = '[velocity]';    
       
   output_div.text(output); //Init the shortcode output 
   
   
   // Toggle heading
   $(document).on('click', 'h3.clickable', function(){
		var el = $(this);
		if($(el).hasClass('open')){
			$(el).next('.expand-wrap').slideDown(velocityBuilder.speed, 'easeInOutQuad', function(){
				$(el).removeClass('open');
			});
		}else{
			$(el).next('.expand-wrap').slideUp(velocityBuilder.speed, 'easeInOutQuad', function(){
				$(el).addClass('open');
			});
		}
	});
	
	
	// Select Image
	$('#velocity_upload_btn').click(function(e) {
      e.preventDefault();
      var image = wp.media({ 
         title: window.parent.velocity_localize.image_select,
         multiple: false
      }).open()
      .on('select', function(e){
         
         // This will return the selected image from the Media Uploader, the result is an object
         var selected_image = image.state().get('selection').first();
         var image_id = selected_image.toJSON().id,
             image_size = $('#velocity_image_size').val();
             
         // Let's assign the url value to the input field 
         $('#velocity_image_id').val(image_id);  
          
         velocityBuilder.getImage(image_id, image_size); // Get the correct image           
         
      });
   });
   
   
   // Image size on change
   $('#velocity_image_size').on('change', function(){
      var image_size = $(this).val(),
          image_id = $('#velocity_image_id').val();
          
      if(image_id !== ''){
         velocityBuilder.getImage(image_id, image_size);
      }
   });
   
   
   /*
   *  velocityBuilder.build
   *  Build Shortcode
   *
   *  @since 1.0
   */
   velocityBuilder.build = function(){
      output = '[velocity';      
      
      // Get values
      var img = $('#velocity_image_path').val(),
          alt = $('#velocity_image_alt').val(),
          type = $('input[name="velocity_type"]:checked').val(),
          id = $('#velocity_type_id').val(),
          btn = $('input[name="velocity_play_btn"]:checked').val(),
          color = $('#velocity_btn_color').val(),
          bkg_color = $('#velocity_bkg_color').val();   
       
      if(type){
         output += ' type="'+type+'"';
      }   
      if(id){
         output += ' id="'+id+'"';
      }  
      if(img){
         output += ' img="'+img+'"';
      }  
      if(alt){
         output += ' alt="'+alt+'"';
      }  
      
      // Play Button
      if(btn === 'true'){
         $('#velocity_play_btn_controls').slideDown(200);
         output += ' color="'+color+'"';
         output += ' bkg_color="'+bkg_color+'"';
         
         $('.velocity-play-btn').show();
         $('.velocity-arrow').css({'border-left-color': color});
         $('.velocity-play-btn').css({'background-color': bkg_color});
         
      }else{
         $('#velocity_play_btn_controls').slideUp(200);
         $('.velocity-play-btn').hide();
      }    
          
      output += ']';  //Close shortcode          
      output_div.text(output);     
   };  
   
   
   $(document).on('change keyup', '.velocity_element', function() {     
	   var el = $(this); 
      el.addClass('changed');
      velocityBuilder.build();
   });

      
      
   
   /*
   *  velocityBuilder.getImage
   *  Get image src
   *
   *  @since 1.0
   */ 
   
   velocityBuilder.getImage = function(id, size){       	   
	   // Get value from Ajax
	   if(id && size){
   	   $('#velocity-img-selection .velocity-loading').fadeIn(velocityBuilder.speed);
   	   $.ajax({
      		type: 'GET',
      		url: window.parent.velocity_localize.ajaxurl,
      		data: {
      			action: 'velocity_get_image',
      			id: id,
      			size: size,
      			nonce: window.parent.velocity_localize.velocity_nonce,
      		},
      		success: function(data) {  
               $('.velocity-preview-img img').attr('src', data); // Change preview
               $('select#velocity_image_size').removeAttr('disabled'); // enable select
               $('#velocity_image_path').val(data); // Set path
               $('#velocity_image_id').val(id); // Set id
               $('#velocity-img-selection .velocity-loading').fadeOut(velocityBuilder.speed);
               $('.velocity-preview-img .clear-img').show();
               velocityBuilder.build();
      		},
      		error: function(xhr, status, error) {
         		console.log(status);
               $('#velocity-img-selection .velocity-loading').fadeOut(velocityBuilder.speed);
      		}
      	});
   	}
   }; 
   
   
   
   // CLear Preview Img
   $('.velocity-preview-img .clear-img').on('click', function(){
      var el = $(this);
      $('.velocity-preview-img img').attr('src', el.data('placeholder'));
      $('select#velocity_image_size').attr('disabled', 'disabled'); // Disable select
      $('#velocity_image_path').val(''); // Set path
      $('#velocity_image_id').val(''); // Set id
      $('.velocity-preview-img .clear-img').hide();
   }); 
   
   // On change
   $('#velocity_image_url').on('input',function(e){
      $('#img-preview img').attr('src', $('#image_url').val());
   });
	
	
	
	/*
   *  velocityBuilder.SelectText
   *  Click to select text
   *
   *  @since 2.0.0
   */  
   
   velocityBuilder.SelectText = function(element) {
       var doc = document, 
         text = doc.getElementById(element), 
         range, 
         selection;    
       if (doc.body.createTextRange) {
           range = document.body.createTextRange();
           range.moveToElementText(text);
           range.select();
       } else if (window.getSelection) {
           selection = window.getSelection();        
           range = document.createRange();
           range.selectNodeContents(text);
           selection.removeAllRanges();
           selection.addRange(range);
       }
   }
   $('#velocity-shortcode-output').click(function() {
     velocityBuilder.SelectText('velocity-shortcode-output');
   });
	
	
	
	/*
	*  _alm.copyToClipboard
	*  Copy shortcode to clipboard
	*
	*  @since 2.0.0
	*/     
	
	velocityBuilder.copyToClipboard = function(text) {
		window.prompt ("Copy link to your clipboard: Press Ctrl + C then hit Enter to copy.", text);
	}
	
	// Copy link on shortcode builder
	$('.velocity-shortcode-display .copy').click(function(){
		var c = $('#velocity-shortcode-output').html();
		velocityBuilder.copyToClipboard(c);
	});
	
	
	
	/*
   *  velocityBuilder.easeInOutQuad
   *  Easing
   *  @since 1.0.0
   */  
   
	$.easing.easeInOutQuad = function (x, t, b, c, d) {
      if ((t/=d/2) < 1) return c/2*t*t + b;
      return -c/2 * ((--t)*(t-2) - 1) + b;
   }
   
   		
})(jQuery);