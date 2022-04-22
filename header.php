<!DOCTYPE html>
<!--// OPEN HTML //-->
<html <?php language_attributes(); ?> class="no-js">
<head>
<?php
    $front_page_id = get_option( 'page_on_front' );
    
    $additional_head_code = get_post_meta($front_page_id,'imic_additional_head_code', true);
    if (!empty($additional_head_code)) {
        echo $additional_head_code;
    }
	
    $options = get_option('imic_options');
   	/** Theme layout design * */
  	$bodyClass = ($options['site_layout'] == 'boxed') ? ' boxed' : '';
   	$style='';
    if($options['site_layout'] == 'boxed'){
    	if (!empty($options['upload-repeatable-bg-image']['id'])) {
       		$style = ' style="background-image:url(' . $options['upload-repeatable-bg-image']['url'] . '); background-repeat:repeat; background-size:auto;"';
     	} else if (!empty($options['full-screen-bg-image']['id'])) {
            $style = ' style="background-image:url(' . $options['full-screen-bg-image']['url'] . '); background-repeat: no-repeat; background-size:cover;"';
        }
       	else if(!empty($options['repeatable-bg-image'])) {
            $style = ' style="background-image:url(' . get_template_directory_uri() . '/images/patterns/' . $options['repeatable-bg-image'] . '); background-repeat:repeat; background-size:auto;"';
        }
  	}
?>
<!--// SITE META //-->
<meta charset="<?php bloginfo('charset'); ?>" />
<!-- Mobile Specific Metas
================================================== -->
<?php if (isset($options['switch-responsive'])&&$options['switch-responsive'] == 1){
	$switch_zoom_pinch = (isset($options['switch-zoom-pinch']))?$options['switch-zoom-pinch']:''; ?>
	<?php if ($switch_zoom_pinch == 1){ ?>
    	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0">
   	<?php } else { ?>
    	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
   	<?php } ?>
   	<meta name="format-detection" content="telephone=no">
<?php } ?>
<!--// PINGBACK & FAVICON //-->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<?php if (function_exists( 'wp_site_icon') && has_site_icon()) {
	echo '<link rel="shortcut icon" href="'.get_site_icon_url().'" />';
}
else
{
	if (isset($options['custom_favicon']) && $options['custom_favicon'] != "") { ?>
    	<link rel="shortcut icon" href="<?php echo esc_url($options['custom_favicon']['url']); ?>" />
<?php }
}
if (isset($options['iphone_icon']) && $options['iphone_icon'] != "")
{ ?>
 	<link rel="apple-touch-icon-precomposed" href="<?php echo $options['iphone_icon']['url']; ?>"><?php
}
if (isset($options['iphone_icon_retina']) && $options['iphone_icon_retina'] != "")
{ ?>
  	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $options['iphone_icon_retina']['url']; ?>"><?php
}
if (isset($options['ipad_icon']) && $options['ipad_icon'] != "")
{ ?>
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $options['ipad_icon']['url']; ?>"><?php
}
if (isset($options['ipad_icon_retina']) && $options['ipad_icon_retina'] != "")
{ ?>
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $options['ipad_icon_retina']['url']; ?>"><?php
}
$offset = get_option('timezone_string');
if($offset=='') {
	$offset = "Australia/Melbourne";
}
date_default_timezone_set($offset);

$online_giving_method  = get_post_meta($front_page_id,'imic_online_giving_method', true);
if ($online_giving_method == 'gci') {
    $online_giving_url  = 'https://www.gci.org/online-giving/?churchnumber='. get_post_meta($front_page_id,'imic_online_giving_gci_church_id', true);
	$online_giving_button = '<a class="btn btn-primary" href="'.$online_giving_url.'" target="_online_giving">ONLINE GIVING</a>';
}
elseif ($online_giving_method == 'other') {
    $online_giving_url  = get_post_meta($front_page_id,'imic_online_giving_other_url', true);    
	$online_giving_button = '<a class="btn btn-primary" href="'.$online_giving_url.'" target="_online_giving">ONLINE GIVING</a>';
}
elseif ($online_giving_method == 'modal') {
    $online_giving_url  = get_post_meta($front_page_id,'imic_online_giving_other_url', true);    
	if ( !class_exists('wx_form_management') ) {
		$online_giving_button = '<a class="btn btn-primary" href="'.$online_giving_url.'" target="_online_giving">ONLINE GIVING</a>';
	} else {
		$online_giving_shortcode='[wxform url="'.$online_giving_url.'" type="modal" bg="#d3ab39" fg="#FFFFFF"]ONLINE GIVING[/wxform]';
		$online_giving_button = do_shortcode($online_giving_shortcode);
		//echo "\n<!-- ================================================================================\n".$online_giving_shortcode."\n".$online_giving_button."\n================================================================================\n -->";
		$online_giving_button = str_replace('wx-button','wx-button btn btn-primary',$online_giving_button);
		//echo "\n<!-- ================================================================================\n".$online_giving_shortcode."\n".$online_giving_button."\n================================================================================\n -->";
	}
}

?>
<!-- CSS
================================================== -->
<!--[if lte IE 8]><link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri() ?>/css/ie8.css" media="screen" /><![endif]-->
<?php 
	$space_before_head = (isset($options['space-before-head']))?$options['space-before-head']:'';
   	$SpaceBeforeHead = $space_before_head;
    echo $SpaceBeforeHead;
?>
<?php //  WORDPRESS HEAD HOOK
 wp_head(); ?>
 <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
</head>
<!--// CLOSE HEAD //-->
<body <?php body_class($bodyClass); echo $style;  ?>>
	<div class="body container-fluid header-style<?php echo $options['header_layout']; ?>">
		<?php
        $menu_locations = get_nav_menu_locations();
        ?>
     	<!-- Start Site Header -->
       	<header class="site-header header top">
            <div class="inner">
                <nav class="navbar">
                    <div class="container-fluid">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="fa fa-bars fa-2x"></span>
                            </button>
                            <a class="navbar-brand" href="<?php echo esc_url( home_url() );?>" title="<?php echo get_bloginfo('title'); ?>">
                                <img class="img-responsive" src="<?php echo $options['logo_upload']['url'];?>" alt="<?php echo get_bloginfo('title'); ?>" />
                            </a>
                        </div>
                        <div class="collapse navbar-collapse pull-right" id="bs-navbar-collapse">
                            <?php if ($online_giving_method != 'disable') {?>
                            <!-- has online-giving page-->
                            <div class="pull-right online-giving-top">
								<?php echo $online_giving_button; ?>
                            </div>
                            <?php }?>
                            <div class="pull-right font-color-theme search-top" title="Search...">
                                <i class="fa fa-search font-color-white"></i>
                                <div class="search-box">
                                    <form action="/">
                                        <input type="text" name="s" value="" placeholder="Search" />
                                    </form>
                                </div>
                            </div>
                            <?php
                            wp_nav_menu( array(
                                    'theme_location' => 'primary-menu',
                                    'menu_class'     => 'menu-header-menu nav navbar-nav pull-right hidden',
                                    'container'		 => 'container-fluid'
                                )
                            );
                            ?>
                        </div>
                    </div>
                </nav><!--end .main-nav-->
            </div><!--end .inner-->
        </header><!--end .header-->


            <!-- End Site Header -->
            <?php
            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
           	$flag=imic_cat_count_flag();
            $page_for_posts = get_option('page_for_posts');
			$show_on_front = get_option('show_on_front');
			if (is_home()) {
                $id = $page_for_posts;
            } elseif (is_404() || is_search()) {
                $id = '';
            }
     		elseif(function_exists( 'is_shop' )&& is_shop()) {
             	$id= get_option('woocommerce_shop_page_id');
           	} 
           	elseif ($flag==0) {
            	$id=''; 
           	}
 			else {
           		$id = get_the_ID();
            }

            if ((!is_front_page()) || $show_on_front == 'posts'||(!is_page_template('template-home.php')&&!is_page_template('template-h-second.php')&&!is_page_template('template-h-third.php')&&!is_page_template('template-home-pb.php'))) 
            {
                
                if (is_404() || is_search()||$flag==0) {
                    $custom = array();
                } else {
                    $custom = get_post_custom($id);
                }
				$header_image = get_post_meta($id,'imic_header_image', true);

				if (is_category() || !empty($term->term_id)) {
               		global $cat;
                    if(!empty($cat)){
                    	$term_taxonomy='category';
                       	$t_id = $cat; // Get the ID of the term we're editing
                  	}else{
                      	$term_taxonomy=get_query_var('taxonomy');
                      	$t_id = $term->term_id; // Get the ID of the term we're editing
              		}
                   	$header_image  = get_option($term_taxonomy . $t_id . "_image_term_id"); // Do the check
           		}
                $default_header_image = $options['header_image']['url'];
                if (!empty($header_image)) {
                   if (is_category() || !empty($term->term_id)) {
                        $src[0] = $header_image;
                    }
                  	else {
                    	$src = wp_get_attachment_image_src($header_image, 'Full');
                	}
                } elseif (!empty($default_header_image)) {
		  			$src[0] = $default_header_image;
				}
				else {
				    $src[0] = get_template_directory_uri() . "/images/default-banner.png";
				}
				
				?>
                <!-- Start Nav Backed Header -->
                <?php
                $header_options = get_post_meta($id,'imic_pages_Choose_slider_display',true);
                $height = get_post_meta($id,'imic_pages_slider_height',true);
                $height = ($height == '') ? '480' : $height;
                $breadpad = $height - 60;

                $bgcolor = get_post_meta($id, 'imic_pages_banner_color', true);
                $bgcolor = ($bgcolor!='') ? $bgcolor: '';

                $css = "<style>\n";
                $css .= ".body ol.breadcrumb  {padding-top: " .$breadpad . "px;}\n";
                if (!empty($bgcolor))
                    $css .= ".nav-backed-header.parallax: background-color  {$bgcolor};\n";
                
                if($header_options==0||$header_options=='') {
                    $css .= ".nav-backed-header.parallax  {background-image:url(" . $src[0] . ");}";
                }
                elseif($header_options==3||$header_options=='') {
                    //
                }
                else {
			        include(locate_template('pages_slider.php'));
                }
                $css .= "</style>";
                echo $css;
                ?>

                <div class="nav-backed-header parallax">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <ol class="breadcrumb">
                                    <?php
                                    if (function_exists('bcn_display_list')) {
                                        bcn_display_list();
                                    }
                                    ?>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- End Nav Backed Header -->
                    <!-- Start Page Header -->
                    <div class="page-header" style="text-align:center;">
                    <div class="container">
                        <div class="row">
                            <?php
                            if (is_search()) {
                                echo '<div class="col-md-12 col-sm-12"><h1>';
                                printf(__('Search Results for :  %s', 'framework'), get_search_query());
                                echo'</h1>
                                  </div>';
                            } else if (is_404()) {
                                echo '<div class="col-md-12 col-sm-12"><h1>';
                                _e('Error 404 - Not Found', 'framework');
                                echo'</h1>
                                  </div>';
                            } elseif (is_author()) {
                                global $author;
                                $userdata = get_userdata($author);
                                echo '<div class="col-md-8 col-sm-8">
                                    <h1>' . __('All Posts For - ', 'framework') . $userdata->display_name . '</h1>
                                  </div>';
                            } else if (get_post_type($id) == 'page') {
                                if(is_page_template('template-gallery-filter.php')){
                                    $colmd_class = "col-md-6";
                                } else {
                                    $colmd_class = "col-md-12";
                                }
                                 $imic_post_custom_title = !empty($custom['imic_post_page_custom_title'][0]) ? $custom['imic_post_page_custom_title'][0] : get_the_title($id);
                                     $event_cat= get_query_var('event_cat');
                                     $event_cat= !empty($event_cat)?$event_cat:'';
                                         echo '<div class="' . $colmd_class . '">';
                                        if (!empty($event_cat)) {
                                            echo'<h1>' . __('All Posts For ', 'framework') . strtoupper($event_cat) . '</h1>';
                                        } else {
                                            echo'<h1 class="page-title">' . $imic_post_custom_title . '</h1>';
                                        }
                                        echo '</div>';
                                if(is_page_template('template-gallery-filter.php')){
                                    ?>
                                    <?php echo '<div class="' . $colmd_class . '">'; ?>
                                    <div class="gallery-filter">
                                        <ul class="nav nav-pills sort-source" data-sort-id="gallery" data-option-key="filter">
                                        <?php 
										 $gallery_category = imic_get_term_category(get_the_ID(),
										                          'imic_advanced_gallery_taxonomy','gallery-category'
										                      );

                                            $gallery_cats = explode(',',$gallery_category); 
											if(count($gallery_cats)==1 && !empty( $gallery_category))
											{
										  ?>
											<li data-option-value="*" class="active"><a href="#">
                                            <i class="fa fa-th"></i> <span><?php $term = get_term_by('slug', $gallery_cats[0], 'gallery-category'); $name = $term->name;  echo esc_html( $name ); ?></span></a></li>
									  <?php 
                                            $current_term = get_term_by('slug',$gallery_cats[0],'gallery-category');
											$args = array(
											'child_of' => $current_term->term_id,
											'taxonomy' => $current_term->taxonomy,
											'hide_empty' => 0,
											'hierarchical' => true,
											'depth'  => 1,
											'title_li' => ''
											);
										    $categories = get_categories( $args );
											foreach($categories as $gallery_cat) { 
											?>
                                              <li data-option-value=".format-<?php echo $gallery_cat->slug; ?>">
                                              <a href="#"><i class="fa <?php echo $gallery_cat->slug; ?>"></i>
                                              <span><?php echo $gallery_cat->name ?></span></a></li>
                                    	<?php  }  
											}?>
                                        <?php if(!empty($gallery_cats) && count($gallery_cats)>1 ) {  ?>
                                        <li data-option-value="*" class="active"><a href="#">
                                            <i class="fa fa-th"></i> <span><?php _e('All','framework') ?></span></a></li>
                                     	<?php foreach($gallery_cats as $gallery_cat) { ?>
                                            <li data-option-value=".format-<?php echo $gallery_cat; ?>">
                                            <a href="#"><i class="fa <?php echo $gallery_cat; ?>"></i>
                                             <span><?php $term = get_term_by('slug', $gallery_cat, 'gallery-category'); $name = $term->name;  echo esc_html( $name ); ?></span></a></li>
                                    	<?php } } ?>
										 <?php if(empty($gallery_category))
											{
										  ?>
											<?php $gallery_cats_default = get_terms("gallery-category");?>
                                            <li data-option-value="*" class="active"><a href="#"><i class="fa fa-th"></i> <span><?php _e('Show All', 'framework'); ?></span></a></li>
                                     	<?php foreach($gallery_cats_default as $gallery_cat_default) { ?>
                                            <li data-option-value=".format-<?php echo $gallery_cat_default->slug; ?>"><a href="#"><i class="fa <?php echo $gallery_cat_default->description; ?>"></i> <span><?php echo $gallery_cat_default->name; ?></span></a></li>
                                    	<?php } ?>
										<?php } ?>
                                        </ul>
                                    </div>
                                    <?php echo '</div>'; ?>
                                    <?php
                            } }
                             else if (get_post_type($id) == 'post') {
                                if (is_category() || is_tag()) {
                                    echo '<div class="col-md-8 col-sm-8">
                                        <h1>' . __('All Posts For - ', 'framework') . single_term_title("", false) . '</h1>
                                    </div>';
                                } else {
                                    if ($page_for_posts == 0 && !is_single()) {
                                        $imic_post_custom_title = __('Article', 'framework');
                                    } else if ($page_for_posts > 0 && !is_single()) {
                                        $imic_post_custom_title = get_the_title($page_for_posts);
                                    } else {
                                        $imic_post_custom_title = !empty($custom['imic_post_page_custom_title'][0]) ? $custom['imic_post_page_custom_title'][0] : 'Article';
                                    }
                                    echo '<div class="col-md-8 col-sm-8">
                                            <h1>' . $imic_post_custom_title . '</h1>
                                        </div>';
                                    if (!empty($custom['imic_post_custom_description'][0])) {
                                        echo'<div class="col-md-4 col-sm-4">
                                            <p>' . $custom['imic_post_custom_description'][0] . '</p>
                                            </div>';
                                    }   
                            } }
                           else if(get_post_type($id) == 'sermons') {
                                $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                                if (!empty($term->term_id)) {
                                    echo '<div class="col-md-12">
                                   <h1>' . __('All Sermons For ', 'framework') . $term->name . '</h1>
                                      </div>';
                                } else {
                                    $imic_post_custom_title = !empty($custom['imic_post_page_custom_title'][0]) ? $custom['imic_post_page_custom_title'][0] : __('Sermons','framework');
                                    $sterm = wp_get_object_terms(get_the_ID(), 'sermons-category');
                                    echo '<div class="col-md-10 col-sm-10 col-xs-8">
                                            <h1>' . $imic_post_custom_title . '</h1>
                                          </div>';
                                    if (!empty($sterm)) {
                                        $i = 1;
                                        foreach ($sterm as $terms) {
                                            if ($i == 1) {
                                           $term_link = get_term_link($terms, 'sermons-category');
                                           echo'<div class="col-md-2 col-sm-2 col-xs-4">
                                                <a href="' . $term_link . '" class="pull-right btn btn-primary">' . __('All sermons', 'framework') . '</a>
                                                </div>';
                                                }
                                            $i++;
                                        } }} }
										else if(get_post_type($id) == 'causes') {
                                $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                                if (!empty($term->term_id)) {
                                    echo '<div class="col-md-12">
                                        <h1>' . __('All Causes For - ', 'framework') . $term->name . '</h1>
                                      </div>';
                                } else {
                                    $imic_post_custom_title = !empty($custom['imic_post_page_custom_title'][0]) ? $custom['imic_post_page_custom_title'][0] : get_the_title();
                                    $sterm = wp_get_object_terms(get_the_ID(), 'causes-category');
                                    echo '<div class="col-md-10 col-sm-10 col-xs-8">
                                            <h1>' . $imic_post_custom_title . '</h1>
                                          </div>';
                                    if (!empty($sterm)) {
                                        $i = 1;
                                        foreach ($sterm as $terms) {
                                            if ($i == 1) {
                                           $term_link = get_term_link($terms, 'causes-category');
                                           echo'<div class="col-md-2 col-sm-2 col-xs-4">
                                                <a href="' . $term_link . '" class="pull-right btn btn-primary">' . __('All causes', 'framework') . '</a>
                                                </div>';
                                                }
                                            $i++;
                                        } }} }
                                else if(get_post_type($id) == 'staff') {
                                $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                                if (!empty($term->term_id)) {
                                    echo '<div class="col-md-12">
                                        <h1>' . __('All Staff For - ', 'framework') . $term->name . '</h1>
                                      </div>';
                                } else {
                                    $imic_post_custom_title = !empty($custom['imic_post_page_custom_title'][0]) ? $custom['imic_post_page_custom_title'][0] :__('Team','framework');
                                    $sterm = wp_get_object_terms(get_the_ID(), 'staff-category');
                                    echo '<div class="col-md-10 col-sm-10 col-xs-8">
                                            <h1>' . $imic_post_custom_title . '</h1>
                                          </div>';
                                    if (!empty($sterm)) {
                                        $i = 1;
                                        foreach ($sterm as $terms) {
                                            if ($i == 1) {
                                           $term_link = get_term_link($terms, 'staff-category');
                                           echo'<div class="col-md-2 col-sm-2 col-xs-4">
                                                <a href="' . $term_link . '" class="pull-right btn btn-primary">' . __('All staff', 'framework') . '</a>
                                                </div>';
                                                }
                                            $i++;
                                        }}} }
                                       else if(get_post_type($id) == 'event') {
				       $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                                if (!empty($term->term_id)) {
                                    echo '<div class="col-md-12">
                                        <h1>' . __('All Events For - ', 'framework') . $term->name . '</h1>
                             
                                      </div>';
                                } else {
                                $imic_post_custom_title = !empty($custom['imic_post_page_custom_title'][0]) ? $custom['imic_post_page_custom_title'][0] :__('Events','framework');
                                $eterm = wp_get_object_terms(get_the_ID(), 'event-category');
                                  echo '<div class="col-md-10 col-sm-10 col-xs-8">
                                    <h1>' . $imic_post_custom_title . '</h1>
                                  </div>';
								}
                                if (!empty($eterm)) {
                                    $i = 1;
                                    foreach ($eterm as $terms) {
                                        if ($i == 1) {
                                            $term_link = get_term_link($terms, 'event-category');
                                       echo'<div class="col-md-2 col-sm-2 col-xs-4">
                                     <a href="' . $term_link . '" class="pull-right btn btn-primary">' . __('All events', 'framework') . '</a></div>';
                                            }
                                        $i++;
                                    } } }
                                  else if (get_post_type($id) == 'product') {
                                $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                                if (!empty($term->term_id)) {
                                    echo '<div class="col-md-12">
                                        <h1>' . __('All Products For - ', 'framework') . $term->name . '</h1>
                                      </div>';
                                } else {
                                    $variable_post_id ='';
                                    if(is_single()):
                                        $variable_post_id= get_the_ID();
                                        else:
                                        $variable_post_id= get_option('woocommerce_shop_page_id');
                                    endif;
                                    $imic_post_page_custom_title= get_post_meta($variable_post_id,'imic_post_page_custom_title',true);
                                    $imic_post_custom_title = !empty($imic_post_page_custom_title) ? $imic_post_page_custom_title : get_the_title($variable_post_id);
                                    echo '<div class="col-md-12">
                                            <h1>' . $imic_post_custom_title . '</h1>
                                          </div>';
                                   }
                            }
                            else {
                               if ($flag==1) {
                                            $imic_post_page_custom_title = get_post_meta(get_the_ID(), 'imic_post_page_custom_title', true);
                                            $imic_post_custom_title = !empty($imic_post_page_custom_title) ? $imic_post_page_custom_title : get_the_title(get_the_ID());
                                                echo '<div class="col-md-12">
                                            <h1>' . $imic_post_custom_title . '</h1>
                                          </div>'; 
                                }} ?>
                            </div>
                        </div>
                    </div>
                    <!-- End Page Header -->
                </div>
                <?php
                /**   Start Content* */
                echo'<div class="main" role="main">
                 <div id="content" class="content full">';
            } ?>
