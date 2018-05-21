<?php
if (!defined('ABSPATH')){
   exit; }// Exit if accessed directly
   /*
    * Add Image Field to category
    */
if (isset($_REQUEST['taxonomy'])):
$taxonomy = $_REQUEST['taxonomy'];
if(!function_exists('image_category_tax_custom_fields')):
add_action($taxonomy . '_add_form_fields', 'image_category_tax_custom_fields', 10, 2);
add_action($taxonomy . '_edit_form_fields', 'image_category_tax_custom_fields', 10, 2);
function image_category_tax_custom_fields($tag) {
       global $taxonomy;
       if (is_object($tag)) {
           $t_id = $tag->term_id; // Get the ID of the term we're editing
          $term_meta = get_option($taxonomy . $t_id . "_image_term_id"); // Do the check
       } else {
           $term_meta = '';
       }
       ?>
       <table class="form-table">
           <tbody><tr class="form-field form-required">
                   <th scope="row"><label for="image"><?php _e('Taxonomy Image', 'framework') ?></label></th>
                   <td><?php
                       echo '<div><img id ="upload_image_preview" src ="' . $term_meta . '" width ="150px" height ="150px"/></div>';
                       echo '<input id="upload_category_button" type="button" class="button button-primary" value="'.__('Upload Image', 'framework').'" /> ';
                      if(isset($_REQUEST['tag_ID'])){
                       echo '<input id="upload_category_button_remove" type="button" class="button button-primary" value="'.__('Remove Image', 'framework').'" />';
                      }
                       ?>
                   <p class="description"><?php _e('Upload an image for the taxonomy .', 'framework'); ?></p></td>
                 </tr><input type="hidden" id="category_url" name="image_term_id" value="<?php echo esc_url($term_meta); ?>" />
           </tbody>
       </table>              
   <?php
} endif;
if(!function_exists('image_category_save_taxonomy_custom_fields')):
add_action('created_' . $taxonomy, 'image_category_save_taxonomy_custom_fields');
add_action('edited_' . $taxonomy, 'image_category_save_taxonomy_custom_fields', 10, 2);
function image_category_save_taxonomy_custom_fields($term_id) {
       global $taxonomy;
       $t_id = $term_id;
       if (isset($_POST['image_term_id'])) {
           $term_meta = $_POST['image_term_id'];
           update_option($taxonomy . $t_id . "_image_term_id", $term_meta);
         }
       }
       endif;
endif;
?>