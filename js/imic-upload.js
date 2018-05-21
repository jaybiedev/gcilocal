 jQuery(function($){
      if(jQuery('#upload_image_preview').attr('src')==''){
       jQuery('#upload_image_preview').hide();
       jQuery('#upload_category_button_remove').hide();
    }
    jQuery('#upload_category_button_remove').live('click', function() {
        jQuery('#upload_image_preview').attr('src','');
        jQuery('#category_url').val('');
        jQuery('#upload_image_preview').hide();
    })
    jQuery('#upload_category_button').live('click', function() {
        var fileFrame = wp.media.frames.file_frame = wp.media({
            multiple: false
        });
        fileFrame.on('select', function() {
            var attachment = fileFrame.state().get('selection').first().toJSON();
	    jQuery('#category_url').val(attachment.url);
            jQuery('#upload_image_preview').show();
            jQuery('#upload_category_button_remove').show();
            jQuery('img#upload_image_preview').attr('src',attachment.url);
});
fileFrame.open(); 
});
});