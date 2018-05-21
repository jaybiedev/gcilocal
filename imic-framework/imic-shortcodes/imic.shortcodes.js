/* ==================================================
IMIC Framework Shortcode Panel jQuery Function
================================================== */
/////////////////////////////////////////////
// jQuery Show/Hide Option Functions
/////////////////////////////////////////////
// jQuery No Conflict
var $j = jQuery.noConflict();
// Shortcodes Function
(function($j) {
$j(document).ready(function() {
  
	// Setup the array of shortcode options
	$j.shortcode_select = {
		'0' : $j([]),
		'shortcode-buttons' : $j('#shortcode-buttons'),
		'shortcode-icons' : $j('#shortcode-icons'),
		'shortcode-imagebanner' : $j('#shortcode-imagebanner'),
		'shortcode-typography' : $j('#shortcode-typography'),
		'shortcode-tooltip' : $j('#shortcode-tooltip'),
		'shortcode-columns' : $j('#shortcode-columns'),
		'shortcode-progressbar' : $j('#shortcode-progressbar'),
		'shortcode-chart' : $j('#shortcode-chart'),
		'shortcode-counters' : $j('#shortcode-counters'),
		'shortcode-tables' : $j('#shortcode-tables'),
		'shortcode-lists' : $j('#shortcode-lists'),
		'shortcode-tabs' : $j('#shortcode-tabs'),
		'shortcode-accordion' : $j('#shortcode-accordion'),
		'shortcode-toggle' : $j('#shortcode-toggle'),
		'shortcode-modal' : $j('#shortcode-modal'),
		'shortcode-form' : $j('#shortcode-form'),
        'shortcode-calendar' : $j('#shortcode-calendar'),
		'shortcode-staff' : $j('#shortcode-staff'),
        'shortcode-sermon' : $j('#shortcode-sermon'),
		'shortcode-sidebar' : $j('#shortcode-sidebar'),
        'shortcode-event' : $j('#shortcode-event'),
        'shortcode-fwvideo' : $j('#shortcode-fwvideo')
	};
	
	$j.typo_select = {
		'0' : $j([]),
		'typo-anchor' : $j('#typo-anchor'),
		'typo-heading' : $j('#typo-heading'),
		'typo-alert' : $j('#typo-alert'),
		'typo-blockquote' : $j('#typo-blockquote'),
		'typo-dropcap' : $j('#typo-dropcap'),
		'typo-code' : $j('#typo-code'),
		'typo-label' : $j('#typo-label'),
		'typo-paragraph' : $j('#typo-paragraph'),
		'typo-divider' : $j('#typo-divider'),
		'typo-container' : $j('#typo-container'),
		'typo-spacer' : $j('#typo-spacer'),
		'typo-span' : $j('#typo-span'),
		'typo-section' : $j('#typo-section')
	};
	// Hide each option section
	$j.each($j.shortcode_select, function() {
		this.css({ display: 'none' });
	});
	
	// Show the selected option section
	$j('#shortcode-select').change(function() {
		$j.each($j.shortcode_select, function() {
			this.css({ display: 'none' });
		});
		$j.shortcode_select[$j(this).val()].css({
			display: 'block'
		});
	});
	
	// Hide each typography option section
	$j.each($j.typo_select, function() {
		this.css({ display: 'none' });
	});
	
	// Show the selected typography option section
	$j('#typography-type').change(function() {
		$j.each($j.typo_select, function() {
			this.css({ display: 'none' });
		});
		$j.typo_select[$j(this).val()].css({
			display: 'block'
		});
	});
	
    $j('.font-icon-grid').on('click', 'li', function() {
    	var selection = $j(this),
    		iconName = selection.find('i').attr('class');
    	    		
    	$j('.font-icon-grid li').removeClass('selected');
    	selection.addClass('selected');
    	selection.parent().parent().find('input').val(iconName);
    });
    
});
})($j);
/////////////////////////////////////////////
// Embed Shortcode Function
/////////////////////////////////////////////
