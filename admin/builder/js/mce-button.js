tinymce.PluginManager.add('velocity', function( velocity_editor, url ) {
   	
	var velocity_shortcode = 'velocity';

	//helper functions 
	function velocity_getAttr(s, n) {
		n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
		return n ?  window.decodeURIComponent(n[1]) : '';
	};

	function velocity_html( classes, attr) {
		var placeholder = url + '/img/velocity-placeholder.jpg';
		
		//var obj = JSON.parse(attr);
		attr = window.encodeURIComponent( attr );		
		//console.log(obj);

		return '<img style="clear:both; display:block; width: 100%; margin: 0 0 10px;" src="' + placeholder + '" class="mceItem ' + classes + '" ' + 'data-sh-attr="' + attr + '" data-mce-resize="false" data-mce-placeholder="1" title="Edit this Velocity shortcode" />';
	}

	function velocity_replaceShortcodes( content ) {
		return content.replace( /\[velocity([^\]]*)\]([^\]]*)/g, function( all, attr) {
			return velocity_html( 'wp-velocity alignnone', attr);
		});
	}
   
	function velocity_restoreShortcodes( content ) {
		//match any image tag with our class and replace it with the shortcode's content and attributes\           
		return content.replace( /(?:<p(?: [^>]+)?>)*(<img [^>]+>)(?:<\/p>)*/g, function( match, image ) {
			var data = velocity_getAttr( image, 'data-sh-attr' );
			return '<p>[' + velocity_shortcode + data + ']</p>';
			return match;
		});
	}

	//add popup
	velocity_editor.addCommand('velocity_mcebutton', function(ui, v) {
		// Register commands
      var w = document.body.clientWidth / 1.2,
          h = document.body.clientHeight / 1.2;
      if(w > 1000) w = 1000;
      if(h > 800) h = 800;
      velocity_editor.windowManager.open({
         title: "Velocity: Shortcode Builder",
         //file: ajaxurl + '?action=velocity_lightbox&img=meow',
         file: ajaxurl + '?action=velocity_lightbox',
         width: w, // size of our window
         height: h , // size of our window
         inline: 1,
		});
   });

	//add button
	velocity_editor.addButton('velocity', {
		title: 'Insert Velocity',
		tooltip: 'Insert Velocity',
      cmd: 'velocity_mcebutton',
      classes: 'widget btn velocity-btn',
      image: url + '/img/add.png',
	});

	//replace from shortcode to an image placeholder
	velocity_editor.on('BeforeSetcontent', function(event){ 
		//event.content = velocity_replaceShortcodes( event.content );
	});

	//replace from image placeholder to shortcode
	velocity_editor.on('GetContent', function(event){
		//event.content = velocity_restoreShortcodes(event.content);
	});

	//open popup on placeholder double click
	velocity_editor.on('dblclick',function(e) {
		var cls  = e.target.className.indexOf('wp-velocity');
		if ( e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-velocity') > -1 ) {
			velocity_editor.execCommand('velocity_mcebutton');
		}
	});
});
