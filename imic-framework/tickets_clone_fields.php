<?php
add_action( 'admin_init', 'nativechurch_add_event_fields_clone' );
add_action( 'save_post', 'nativechurch_update_event_fields_data', 10, 2 );
/**
 * Add custom Meta Box to Posts post type
 */
function nativechurch_add_event_fields_clone() 
{
    add_meta_box('nativechurch_event_schedule',__('Event Tickets Type','framework'),'nativechurch_event_feilds_output','event','normal','core');
}
/**
 * Print the Meta Box content
 */
function nativechurch_event_feilds_output() 
{
    global $post, $line_icons;
	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'nativechurch_event_schedule_meta_box', 'nativechurch_event_schedule_meta_box_nonce' );
		$ticket_status = get_post_meta($post->ID, 'nativechurch_ticket_status', true);
    $tickets_type1 = get_post_meta( $post->ID, 'nativechurch_event_type1', true );
		$nativechurch_event_ticket1 = get_post_meta( $post->ID, 'nativechurch_event_ticket1', true );
		$nativechurch_event_booked1 = get_post_meta( $post->ID, 'nativechurch_event_booked1', true );
		$nativechurch_event_amount1 = get_post_meta( $post->ID, 'nativechurch_event_amount1', true );
		$tickets_type2 = get_post_meta( $post->ID, 'nativechurch_event_type2', true );
		$nativechurch_event_ticket2 = get_post_meta( $post->ID, 'nativechurch_event_ticket2', true );
		$nativechurch_event_booked2 = get_post_meta( $post->ID, 'nativechurch_event_booked2', true );
		$nativechurch_event_amount2 = get_post_meta( $post->ID, 'nativechurch_event_amount2', true );
		$tickets_type3 = get_post_meta( $post->ID, 'nativechurch_event_type3', true );
		$nativechurch_event_ticket3 = get_post_meta( $post->ID, 'nativechurch_event_ticket3', true );
		$nativechurch_event_booked3 = get_post_meta( $post->ID, 'nativechurch_event_booked3', true );
		$nativechurch_event_amount3 = get_post_meta( $post->ID, 'nativechurch_event_amount3', true );
	
?>
<div id="field_group">
<label><?php esc_html_e('Multiple Tickets', 'framework'); ?></label>
<select name="nativechurch_ticket_status">
<option value="1" <?php echo ($ticket_status==1)?'selected':''; ?>><?php esc_html_e('Enable', 'framework'); ?></option>
<option value="0" <?php echo ($ticket_status==0)?'selected':''; ?>><?php esc_html_e('Disable', 'framework'); ?></option>
</select>
    <div id="field_wrap">
        <div class="field_row">
        <div class="field_left" style="width:100%">
        	<strong style="width:43%; float:left;"><?php esc_html_e('Ticket Type', 'framework'); ?></strong>
            <strong style="width:15%; float:left;"><?php esc_html_e('No of Tickets', 'framework'); ?></strong>
            <strong style="width:16%; float:left;"><?php esc_html_e('Booked Tickets', 'framework'); ?></strong>
            <strong style="width:15%; float:left;"><?php esc_html_e('Price', 'framework'); ?></strong>
        </div>
          <div class="clear" /></div>
        </div>
        <div class="field_row">
        <div class="field_left">
              	<input type="text" class="meta_feat_title" name="nativechurch_event_type1" value="<?php echo esc_attr($tickets_type1); ?>" placeholder="<?php esc_html_e('Name your Ticket', 'framework'); ?>">
              	<input type="text" class="meta_feat_title" name="nativechurch_event_ticket1" value="<?php echo esc_attr($nativechurch_event_ticket1); ?>" placeholder="<?php esc_html_e('No of Tickets', 'framework'); ?>" style="width:15%">
                <input class="meta_sch_title" value="<?php echo esc_attr($nativechurch_event_booked1); ?>" type="text" name="nativechurch_event_booked1" placeholder="<?php esc_html_e('Booked Tickets', 'framework'); ?>" style="width:15%">
                <input class="meta_sch_title" value="<?php echo esc_attr($nativechurch_event_amount1); ?>" type="text" name="nativechurch_event_amount1" placeholder="<?php esc_html_e('Price', 'framework'); ?>" style="width:15%">
        </div>
          <div class="clear" /></div> 
        </div>
        <!--Second Ticket Field-->
        <div class="field_row">
        <div class="field_left">
        
              <input type="text" class="meta_feat_title" name="nativechurch_event_type2" value="<?php echo esc_attr($tickets_type2); ?>" placeholder="<?php esc_html_e('Name your Ticket', 'framework'); ?>">
              <input type="text" class="meta_feat_title" name="nativechurch_event_ticket2" value="<?php echo esc_attr($nativechurch_event_ticket2); ?>" placeholder="<?php esc_html_e('No of Tickets', 'framework'); ?>" style="width:15%">
                <input class="meta_sch_title" value="<?php echo esc_attr($nativechurch_event_booked2); ?>" type="text" name="nativechurch_event_booked2" placeholder="<?php esc_html_e('Booked Tickets', 'framework'); ?>" style="width:15%">
                <input class="meta_sch_title" value="<?php echo esc_attr($nativechurch_event_amount2); ?>" type="text" name="nativechurch_event_amount2" placeholder="<?php esc_html_e('Price', 'framework'); ?>" style="width:15%">
        </div>
          <div class="clear" /></div> 
        </div>
        <!--Second Ticket Field End-->
        <!--Third Ticket Field-->
        <div class="field_row">
        <div class="field_left">
        
              <input type="text" class="meta_feat_title" name="nativechurch_event_type3" value="<?php echo esc_attr($tickets_type3); ?>" placeholder="<?php esc_html_e('Name your Ticket', 'framework'); ?>">
              <input type="text" class="meta_feat_title" name="nativechurch_event_ticket3" value="<?php echo esc_attr($nativechurch_event_ticket3); ?>" placeholder="<?php esc_html_e('No of Tickets', 'framework'); ?>" style="width:15%">
                <input class="meta_sch_title" value="<?php echo esc_attr($nativechurch_event_booked3); ?>" type="text" name="nativechurch_event_booked3" placeholder="<?php esc_html_e('Booked Tickets', 'framework'); ?>" style="width:15%">
                <input class="meta_sch_title" value="<?php echo esc_attr($nativechurch_event_amount3); ?>" type="text" name="nativechurch_event_amount3" placeholder="<?php esc_html_e('Price', 'framework'); ?>" style="width:15%">
        </div>
          <div class="clear" /></div> 
        </div>
        <!--Third Ticket Field End-->
    </div>
    <div id="add_field_row">
      <p><?php echo esc_attr_e('Booked Ticket field will be updated by own as per the number of registrations.', 'framework'); ?></p>
      <p><?php echo esc_attr_e('Do not add currency in price field, currency should be selected from Appearance &gt; Payment Options', 'framework'); ?></p>
    </div>
</div>
  <?php
}
/**
 * Save post action, process fields
 */
function nativechurch_update_event_fields_data( $post_id, $post_object ) 
{
    if ( ! isset( $_POST['nativechurch_event_schedule_meta_box_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['nativechurch_event_schedule_meta_box_nonce'], 'nativechurch_event_schedule_meta_box' ) ) {
		return;
	}
    // Doing revision, exit earlier **can be removed**
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  
        return;
    // Doing revision, exit earlier
    if ( 'revision' == $post_object->post_type )
        return;
    // Verify authenticity
	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'event' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	} 
	update_post_meta($post_id, 'nativechurch_ticket_status', $_POST['nativechurch_ticket_status']);
	update_post_meta($post_id, 'nativechurch_event_type1', $_POST['nativechurch_event_type1']);
	update_post_meta($post_id, 'nativechurch_event_ticket1', $_POST['nativechurch_event_ticket1']);
	update_post_meta($post_id, 'nativechurch_event_booked1', $_POST['nativechurch_event_booked1']);
	update_post_meta($post_id, 'nativechurch_event_amount1', $_POST['nativechurch_event_amount1']);
	update_post_meta($post_id, 'nativechurch_event_type2', $_POST['nativechurch_event_type2']);
	update_post_meta($post_id, 'nativechurch_event_ticket2', $_POST['nativechurch_event_ticket2']);
	update_post_meta($post_id, 'nativechurch_event_booked2', $_POST['nativechurch_event_booked2']);
	update_post_meta($post_id, 'nativechurch_event_amount2', $_POST['nativechurch_event_amount2']);
	update_post_meta($post_id, 'nativechurch_event_type3', $_POST['nativechurch_event_type3']);
	update_post_meta($post_id, 'nativechurch_event_ticket3', $_POST['nativechurch_event_ticket3']);
	update_post_meta($post_id, 'nativechurch_event_booked3', $_POST['nativechurch_event_booked3']);
	update_post_meta($post_id, 'nativechurch_event_amount3', $_POST['nativechurch_event_amount3']);
}

function nativechurch_add_admin_scripts_event( $hook ) {
    global $post;
    if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
        if ( 'event' === $post->post_type ) {     
					wp_enqueue_style(  'event_clone_tickets_style', IMIC_THEME_PATH.'/css/clone_fields.css' );
        }
    }
}
add_action( 'admin_enqueue_scripts', 'nativechurch_add_admin_scripts_event', 10, 1 );