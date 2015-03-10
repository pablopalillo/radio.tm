jQuery(document).ready(function(){

	/*
	* Here is an example of how to use Backstretch as a slideshow.
	* Just pass in an array of images, and optionally a duration and fade value.
	*/
	// Duration is the amount of time in between slides,
	// and fade is value that determines how quickly the next image will fade in
	jQuery.backstretch([
	"/wp-content/themes/va_tmradio/images/background/1.jpg"
	, "/wp-content/themes/va_tmradio/images/background/2.jpg"
	, "/wp-content/themes/va_tmradio/images/background/3.jpg"
	], {duration: 8000, fade: 750}); 


	jQuery('.menu-movil nav').prepend('<div id="menu-icon">Menu</div>');
	//jQuery("#main-menu #main_nav").hide();
	jQuery(".menu-movil #menu-icon").on("click", function(){
		jQuery(".menu-movil #main_nav").slideToggle();
	});
	
	jQuery('.senal-en-vivo').on('click', function(event){
		event.preventDefault();
		window.name = "winpadre";
    	window.open("/senal-en-vivo.php", "TelemedellinRadio","menubar=0,resizable=0,width=640,height=180,status=0,scrollbars=0");
	});

	jQuery('#programacion').easyResponsiveTabs();
	
	jQuery('#mes').change(function(){
		var t 		= jQuery(this),
			p 		= jQuery("#programa"), 
			cat_id 	= t.attr("data-catid"), 
			nonce 	= t.attr("data-nonce"),
			moy 	= t.val();

		if(moy != '')
		{
			jQuery.ajax({
				url: 		myAjax.ajaxurl,
				type: 		"post",
				dataType: 	"json",
				data: {
					action: "get_posts_by_moy", 
					cat_id: cat_id, 
					moy: moy, 
					nonce: nonce
				},
				beforeSend: function(){
					p.html(new Option('Cargando podcasts...', '')).attr('disabled', 'disabled'); 
				}, 
				success: function(response) {
					p.html('');

					if(response.type == "success") {
						p.append(new Option('Selecciona el podcast', ''));
						jQuery.each(response.posts, function(i, e){
							p.append(new Option(e.post_title, e.ID))
						});
						p.removeAttr('disabled');
					}
					else {
					   alert("Error");
					}
					p.append(new Option('Ver mes', 'nah'));
				}
			}); 
		}
		  
	});

	jQuery('#programa').change(function(){
		var t 		= jQuery(this),
			de 		= jQuery('#emision'), 
			html	= '', 
			j		= jQuery("#mes"),
			cat_id 	= j.attr("data-catid"), 
			nonce 	= j.attr("data-nonce"),
			moy 	= j.val();
			post_id = t.val();
		if(post_id != '')
		{
			if(post_id == "nah" )
			{
				dataparameter =  {
					"action": "get_posts_by_moy", 
					"cat_id": cat_id, 
					"moy": moy, 
					"nonce": nonce
				};
			}
			else
			{
				dataparameter =  {
					"action": "get_podcast", 
					"post_id": post_id,
				};
			}
			console.log(dataparameter);

			jQuery.ajax({
				url: 		myAjax.ajaxurl,
				type: 		"post",
				dataType: 	"json",
				data:dataparameter,

				beforeSend: function(){
					de.html('Cargando podcast...'); 
				}, 
				success: function(response) {
					if(response.type == "success") {
						jQuery.each(response.posts, function(i, e){
							html += '<article id="post-' + e.ID + '" ' + 'class="post"'/*post_class()/**/ + '>';
							html += '	<header class="entry-header">';
							html += '<h2 class="entry-title"><a href="'+e.guid+'">';
							html += e.post_title;
							html += '		</h2></a>';
							html += '	</header>';
							html += '	<div class="entry-content">';
							html += e.post_content;
							html += '	</div>';
							html += '</article>';
						});
						de.html(html);
					}
					else {
					   alert("Error");
					}
				}
			});
		}
		
	});
});