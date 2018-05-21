jQuery(document).ready(function(){
	jQuery('.owl-carousel').each(function(){
		var carouselInstance = jQuery(this); 
		var carouselColumns = carouselInstance.attr("data-columns") ? carouselInstance.attr("data-columns") : "1"
		var carouselitemsDesktop = carouselInstance.attr("data-items-desktop") ? carouselInstance.attr("data-items-desktop") : "4"
		var carouselitemsDesktopSmall = carouselInstance.attr("data-items-desktop-small") ? carouselInstance.attr("data-items-desktop-small") : "3"
		var carouselitemsTablet = carouselInstance.attr("data-items-tablet") ? carouselInstance.attr("data-items-tablet") : "2"
		var carouselitemsMobile = carouselInstance.attr("data-items-mobile") ? carouselInstance.attr("data-items-mobile") : "1"
		var carouselAutoplay = carouselInstance.attr("data-autoplay") ? carouselInstance.attr("data-autoplay") : false
		var carouselPagination = carouselInstance.attr("data-pagination") == 'yes' ? true : false
		var carouselArrows = carouselInstance.attr("data-arrows") == 'yes' ? true : false
		var carouselSingle = carouselInstance.attr("data-single-item") == 'yes' ? true : false
		var carouselStyle = carouselInstance.attr("data-style") ? carouselInstance.attr("data-style") : "fade"
		
		carouselInstance.owlCarousel({
			items: carouselColumns,
			autoPlay : carouselAutoplay,
			navigation : carouselArrows,
			pagination : carouselPagination,
			itemsDesktop:[1199,carouselitemsDesktop],
			itemsDesktopSmall:[979,carouselitemsDesktopSmall],
			itemsTablet:[768,carouselitemsTablet],
			itemsMobile:[479,carouselitemsMobile],
			singleItem:carouselSingle,
			navigationText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
			stopOnHover: true,
			lazyLoad: true,
			transitionStyle: 'carouselStyle'
		});
	});
});
jQuery(function() {
	// apply matchHeight to each item container's items
	jQuery('.partner-logos').each(function() {
		jQuery(this).find('li').matchHeight({
			//property: 'min-height'
		});
	});
});