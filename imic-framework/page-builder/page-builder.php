<?php
function add_my_awesome_widgets_collection($folders){
    $folders[] = ImicFrameworkPath . '/page-builder/widgets/';
    return $folders;
}
add_filter('siteorigin_widgets_widget_folders', 'add_my_awesome_widgets_collection');

function nativechurch_add_widget_tabs($tabs) {
    $tabs[] = array(
        'title' => __('Native Theme Widgets', 'framework'),
        'filter' => array(
            'groups' => array('framework')
        )
    );

    return $tabs;
}
add_filter('siteorigin_panels_widget_dialog_tabs', 'nativechurch_add_widget_tabs', 30);


if(!function_exists('imic_prebuilt_pages')) {
function imic_prebuilt_pages($layouts){
    $layouts['home-page'] = array(
        // We'll add a title field
        'name' => __('Home version 1', 'vantage'),
        'widgets' => array(0 => 
    array (
      'featured_image_fallback' => '',
      'featured_image' => 425,
      'featured_size' => 'full',
      'featured_title' => 'Our Pastors',
      'featured_alt' => '',
      'featured_link_text' => 'read more',
      'featured_url' => 'post: 129',
      'featured_bound' => true,
      'featured_new_window' => false,
      'featured_full_width' => false,
      'panels_info' => 
      array (
        'class' => 'Featured_Block_Widget',
        'raw' => false,
        'grid' => 0,
        'cell' => 0,
        'id' => 0,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
    1 => 
    array (
      'featured_image_fallback' => '',
      'featured_image' => 425,
      'featured_size' => 'full',
      'featured_title' => 'New Here',
      'featured_alt' => '',
      'featured_link_text' => 'read more',
      'featured_url' => 'post: 13',
      'featured_bound' => true,
      'featured_new_window' => false,
      'featured_full_width' => false,
      'panels_info' => 
      array (
        'class' => 'Featured_Block_Widget',
        'raw' => false,
        'grid' => 0,
        'cell' => 1,
        'id' => 1,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
    2 => 
    array (
      'featured_image_fallback' => '',
      'featured_image' => 425,
      'featured_size' => 'full',
      'featured_title' => 'Sermons Archive',
      'featured_alt' => '',
      'featured_link_text' => 'read more',
      'featured_url' => 'post: 19',
      'featured_bound' => true,
      'featured_new_window' => false,
      'featured_full_width' => false,
      'panels_info' => 
      array (
        'class' => 'Featured_Block_Widget',
        'raw' => false,
        'grid' => 0,
        'cell' => 2,
        'id' => 2,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
    3 => 
    array (
      'widget_title' => 'More Coming Events',
      'number_of_events' => 4,
      'show_month_sorter' => false,
      'panels_info' => 
      array (
        'class' => 'Events_Listing_Widget',
        'raw' => false,
        'grid' => 1,
        'cell' => 0,
        'id' => 3,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
    4 => 
    array (
      'title' => 'Latest News',
      'categories' => '',
      'number_of_posts' => 2,
      'show_post_meta' => true,
      'excerpt_length' => '50',
      'read_more_text' => 'Continue reading',
      'listing_layout' => 
      array (
        'layout_type' => 'list',
        'grid_column' => false,
      ),
      'panels_info' => 
      array (
        'class' => 'Posts_List_Widget',
        'raw' => false,
        'grid' => 1,
        'cell' => 0,
        'id' => 4,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
    5 => 
    array (
      'title' => 'Recent Sermons',
      'number' => '3',
      'panels_info' => 
      array (
        'class' => 'recent_sermons',
        'raw' => false,
        'grid' => 1,
        'cell' => 1,
        'id' => 5,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
    6 => 
    array (
      'type' => 'visual',
      'title' => '',
      'text' => '<h3><span style="color: #ffffff;">UPDATES FROM OUR GALLERY<br /></span></h3><p>[imic_button colour="btn-default" type="enabled" link="#" target="_self" extraclass="" size="btn-lg"]More Galleries[/imic_button]</p>',
      'filter' => '1',
      'panels_info' => 
      array (
        'class' => 'WP_Widget_Black_Studio_TinyMCE',
        'raw' => false,
        'grid' => 2,
        'cell' => 0,
        'id' => 6,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
    7 => 
    array (
      'title' => '',
      'categories' => '',
      'number_of_posts' => 4,
      'grid_column' => '4',
      'show_post_meta' => false,
      'filters' => false,
      'panels_info' => 
      array (
        'class' => 'Gallery_Grid_Widget',
        'raw' => false,
        'grid' => 2,
        'cell' => 1,
        'id' => 7,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
      
  ),
  'grids' => 
  array (0 => 
    array (
      'cells' => 3,
      'style' => 
      array (
        'background_display' => 'tile',
      ),
    ),
    1 => 
    array (
      'cells' => 2,
      'style' => 
      array (
        'background_display' => 'cover',
      ),
    ),
    2 => 
    array (
      'cells' => 2,
      'style' => 
      array (
        'class' => 'accent-bg',
        'padding' => '40px',
        'row_stretch' => 'full',
        'background_display' => 'tile',
      ),
    ),
    ),
    'grid_cells' => array(
    0 => 
    array (
      'grid' => 0,
      'weight' => 0.333333333333333314829616256247390992939472198486328125,
    ),
    1 => 
    array (
      'grid' => 0,
      'weight' => 0.333333333333333314829616256247390992939472198486328125,
    ),
    2 => 
    array (
      'grid' => 0,
      'weight' => 0.333333333333333314829616256247390992939472198486328125,
    ),
    3 => 
    array (
      'grid' => 1,
      'weight' => 0.6666666666670000296335274470038712024688720703125,
    ),
    4 => 
    array (
      'grid' => 1,
      'weight' => 0.333333333333000025877623784253955818712711334228515625,
    ),
    5 => 
    array (
      'grid' => 2,
      'weight' => 0.333056017747999977274275806848891079425811767578125,
    ),
    6 => 
    array (
      'grid' => 2,
      'weight' => 0.666943982252000022725724193151108920574188232421875,
    ),
	),
	);
	
	
	$layouts['home-page4'] = array(
        // We'll add a title field
        'name' => __('Home version 4', 'vantage'),
        'widgets' => array(0 => 
    array (
      'title' => 'A powerful theme for churches, trusted by 3000+ Users',
      'sub_title' => 'You will get free page builder, revolution slider, events manager, sermons manager',
      'design' => 
      array (
        'background_color' => '#f8f7f3',
        'border_color' => '#f8f7f3',
        'button_align' => 'right',
      ),
      'button' => 
      array (
        'text' => 'Purchase now',
        'url' => 'http://themeforest.net/item/nativechurch-multi-purpose-wordpress-theme/7082446',
        'new_window' => true,
        'button_icon' => 
        array (
          'icon_selected' => 'icomoon-thumbs-up',
          'icon_color' => false,
          'icon' => 0,
        ),
        'design' => 
        array (
          'theme' => 'wire',
          'button_color' => '#007f7b',
          'text_color' => '#ffffff',
          'hover' => true,
          'font_size' => '1',
          'rounding' => '0.25',
          'padding' => '1',
          'align' => 'center',
        ),
        'attributes' => 
        array (
          'id' => '',
          'title' => '',
          'onclick' => '',
        ),
      ),
      'panels_info' => 
      array (
        'class' => 'SiteOrigin_Widget_Cta_Widget',
        'raw' => false,
        'grid' => 0,
        'cell' => 0,
        'id' => 0,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
    1 => 
    array (
      'headline' => 
      array (
        'text' => 'Introducing version 2.4',
        'font' => 'default',
        'color' => '#000000',
        'align' => 'center',
      ),
      'sub_headline' => 
      array (
        'text' => '',
        'font' => 'default',
        'color' => '#000000',
        'align' => 'center',
      ),
      'divider' => 
      array (
        'style' => 'dashed',
        'weight' => 'thin',
        'color' => '#EEEEEE',
      ),
      'panels_info' => 
      array (
        'class' => 'SiteOrigin_Widget_Headline_Widget',
        'raw' => false,
        'grid' => 1,
        'cell' => 0,
        'id' => 1,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
    2 => 
    array (
      'features' => 
      array (
        0 => 
        array (
          'container_color' => '#0cc9e8',
          'icon' => 'typicons-infinity',
          'icon_color' => '#FFFFFF',
          'icon_image' => 0,
          'title' => 'Intuitive Page Builder',
          'text' => 'Native Church version 2.4 got a new addition of page builder, now make endless homepage designs',
          'more_text' => '',
          'more_url' => '',
        ),
        1 => 
        array (
          'container_color' => '#c1c923',
          'icon' => 'typicons-zoom-outline',
          'icon_color' => '#FFFFFF',
          'icon_image' => 0,
          'title' => 'Sermon Filters',
          'text' => 'Now filtering your sermons is easy with newly added feature of filters for sermon category, tags and speakers',
          'more_text' => '',
          'more_url' => '',
        ),
        2 => 
        array (
          'container_color' => '#dea154',
          'icon' => 'typicons-calender',
          'icon_color' => '#FFFFFF',
          'icon_image' => 0,
          'title' => 'Multiple Calendars',
          'text' => 'Now you can sync multiple Google Calendars with your theme\'s calendar shortcode',
          'more_text' => '',
          'more_url' => '',
        ),
      ),
      'container_shape' => 'round',
      'container_size' => 84,
      'icon_size' => 39,
      'per_row' => 3,
      'responsive' => true,
      'title_link' => false,
      'icon_link' => false,
      'new_window' => false,
      'panels_info' => 
      array (
        'class' => 'SiteOrigin_Widget_Features_Widget',
        'raw' => false,
        'grid' => 2,
        'cell' => 0,
        'id' => 2,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
    3 => 
    array (
      'widget_title' => 'Upcoming events',
      'allpostsbtn' => 'All Events',
      'allpostsurl' => '',
      'number_of_events' => 5,
      'show_month_sorter' => false,
      'panels_info' => 
      array (
        'class' => 'Upcoming_Events_Listing_Widget',
        'raw' => false,
        'grid' => 3,
        'cell' => 0,
        'id' => 3,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
    4 => 
    array (
      'title' => 'Featured Event',
      'event' => '151',
      'panels_info' => 
      array (
        'class' => 'featured_event',
        'raw' => false,
        'grid' => 3,
        'cell' => 1,
        'id' => 4,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
    5 => 
    array (
      'type' => 'visual',
      'title' => '',
      'text' => '<h4><span style="color: #ffffff;">UPDATES FROM OUR GALLERY</span></h4><p>[imic_button colour="btn-default" type="enabled" link="http://preview.imithemes.com/native-church-wp/gallery-3-columns-pagination/" target="_self" extraclass="" size="btn-lg"]More Galleries[/imic_button]</p>',
      'filter' => '1',
      'panels_info' => 
      array (
        'class' => 'WP_Widget_Black_Studio_TinyMCE',
        'raw' => false,
        'grid' => 4,
        'cell' => 0,
        'id' => 5,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
    6 => 
    array (
      'title' => '',
      'allpostsbtn' => 'All Galleries',
      'allpostsurl' => '',
      'categories' => '',
      'number_of_posts' => 3,
      'grid_column' => '4',
      'show_post_meta' => false,
      'filters' => false,
      'panels_info' => 
      array (
        'class' => 'Gallery_Grid_Widget',
        'raw' => false,
        'grid' => 4,
        'cell' => 1,
        'id' => 6,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
    7 => 
    array (
      'headline' => 
      array (
        'text' => 'Latest from our blog',
        'font' => 'default',
        'color' => '#333333',
        'align' => 'center',
      ),
      'sub_headline' => 
      array (
        'text' => '',
        'font' => 'default',
        'color' => '#000000',
        'align' => 'center',
      ),
      'divider' => 
      array (
        'style' => 'none',
        'weight' => 'thin',
        'color' => '#EEEEEE',
      ),
      'panels_info' => 
      array (
        'class' => 'SiteOrigin_Widget_Headline_Widget',
        'raw' => false,
        'grid' => 5,
        'cell' => 0,
        'id' => 7,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
    8 => 
    array (
      'title' => '',
      'allpostsbtn' => '',
      'allpostsurl' => '',
      'categories' => '',
      'number_of_posts' => 3,
      'show_post_meta' => true,
      'excerpt_length' => '20',
      'read_more_text' => 'Continue reading',
      'listing_layout' => 
      array (
        'layout_type' => 'grid',
        'grid_column' => '4',
      ),
      'panels_info' => 
      array (
        'class' => 'Posts_List_Widget',
        'raw' => false,
        'grid' => 5,
        'cell' => 0,
        'id' => 8,
        'style' => 
        array (
          'background_display' => 'tile',
        ),
      ),
    ),
  ),
  'grids' => 
  array (
    0 => 
    array (
      'cells' => 1,
      'style' => 
      array (
        'class' => 'no-top-content-padding',
        'bottom_margin' => '60px',
        'row_stretch' => 'full',
        'background' => '#f8f7f3',
        'background_display' => 'tile',
        'border_color' => '#eceae4',
      ),
    ),
    1 => 
    array (
      'cells' => 1,
      'style' => 
      array (
        'bottom_margin' => '60px',
        'background_display' => 'tile',
      ),
    ),
    2 => 
    array (
      'cells' => 1,
      'style' => 
      array (
        'bottom_margin' => '60px',
        'background_display' => 'tile',
      ),
    ),
    3 => 
    array (
      'cells' => 2,
      'style' => 
      array (
        'bottom_margin' => '0px',
        'padding' => '60px',
        'row_stretch' => 'full',
        'background' => '#fcfcfc',
        'background_display' => 'tile',
      ),
    ),
    4 => 
    array (
      'cells' => 2,
      'style' => 
      array (
        'padding' => '60px',
        'row_stretch' => 'full',
        'background' => '#007f7b',
        'background_display' => 'tile',
      ),
    ),
    5 => 
    array (
      'cells' => 1,
      'style' => 
      array (
        'bottom_margin' => '0px',
        'background_image_attachment' => false,
        'background_display' => 'tile',
      ),
    ),
  ),
  'grid_cells' => 
  array (
    0 => 
    array (
      'grid' => 0,
      'weight' => 1,
    ),
    1 => 
    array (
      'grid' => 1,
      'weight' => 1,
    ),
    2 => 
    array (
      'grid' => 2,
      'weight' => 1,
    ),
    3 => 
    array (
      'grid' => 3,
      'weight' => 0.66722129783700001581792093929834663867950439453125,
    ),
    4 => 
    array (
      'grid' => 3,
      'weight' => 0.33277870216299998418207906070165336132049560546875,
    ),
    5 => 
    array (
      'grid' => 4,
      'weight' => 0.33277870216299998418207906070165336132049560546875,
    ),
    6 => 
    array (
      'grid' => 4,
      'weight' => 0.66722129783700001581792093929834663867950439453125,
    ),
    7 => 
    array (
      'grid' => 5,
      'weight' => 1,
    ),
  ),
	
    );
    return $layouts;

}
add_filter('siteorigin_panels_prebuilt_layouts','imic_prebuilt_pages');
}
?>