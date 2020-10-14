$=jQuery;
jQuery(function($){
	$('.gci-loadmore a').click(function() {
		var cptType = $(this).data('type');
		var button = $(this),
		    data = {
			'action': 'loadmore',
			'query': posts, // that's how we get params from wp_localize_script() function			
			'page' : current_page,
			'cptType' : cptType
		};
 
		$.ajax({
			url : gci_loadmore.ajaxurl, // AJAX handler
			data : data,
			type : 'POST',
			beforeSend : function ( xhr ) {
				button.text('Loading...'); // change the button text, you can also add a preloader image
			},
			success : function( data ){
				if( data ) {
					button.text( 'More' );
					$('div.loadmore-posts').append(data); // insert new posts
					current_page ++;					
					
					if ( current_page == max_page ) {
						button.parent().remove();
					}
				} else {
					button.parent().remove(); // if no data, remove the button
				}
			}
		});
	});
});
