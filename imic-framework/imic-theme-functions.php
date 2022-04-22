<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/*
 *
 * 	imic Framework Theme Functions
 * 	------------------------------------------------
 * 	imic Framework v2.0
 * 	Copyright imic  2014 - http://www.imicreation.com/
 * 	imic_theme_activation()
 * 	imic_maintenance_mode()
 * 	imic_custom_login_logo()
 * 	imic_add_nofollow_cat()
 * 	imic_admin_bar_menu()
 * 	imic_admin_css()
 * 	imic_analytics()
 * 	imic_custom_styles()
 * 	imic_custom_script()
 *      imic_content_filter()
 *      imic_video_embed()
 *      imic_video_youtube()
 *      imic_video_vimeo()
 *      imic_audio_soundcloud()
 * 	imic_register_sidebars()
 *      imic_custom_taxonomies_terms_links()    
 * 	event_time_columns()
 * 	event_time_column_content()
 * 	sortable_event_column()
 *      event_time_orderby()
 *      imic_register_meta_box()
 *      class IMIC_Custom_Nav
 *      imic_get_all_types()
 *      imic_day_diff()
  *     imic_get_recursive_event_data()
 *      imic_get_cat_list()
 *      imic_widget_titles()
 *      widget_text filter
 *      imic_month_translate()
 *      imic_short_month_translate()
 *      imic_day_translate()
 *      imic_global_month_name()
 *      RevSliderShortCode()
 *      imic_gallery_flexslider()
 *      imic_sermon_attach_full_audio()
 *      imic_sermon_attach_full_pdf()
 *      imic_query_arg()
 *      imicAddQueryVarsFilter()
 *      ImicConvertDate()
 *      imic_cat_count_flag()
 *		imic_page_design()
 *      imic_get_home_recursive_event_data()
 *      imicBlogTemplateRedirect()
 *      imicGetThumbAndLargeSize()
 *      imic_disable_admin_bar()
 * 		imic_sidebar_position_module()
 *		imic_share_buttons
 *		 SAVE EVENTS MODULE
 */
/* THEME ACTIVATION
  ================================================== */
if (!function_exists('imic_theme_activation')) {
    function imic_theme_activation() {
        global $pagenow;
        if (is_admin() && 'themes.php' == $pagenow && isset($_GET['activated'])) {
            #provide hook so themes can execute theme specific functions on activation
            do_action('imic_theme_activation');
          }
    }
    add_action('admin_init', 'imic_theme_activation');
}
/* MAINTENANCE MODE
  ================================================== */
if (!function_exists('imic_maintenance_mode')) {
    function imic_maintenance_mode() {
        $options = get_option('imic_options');
        $custom_logo = $custom_logo_output = $maintenance_mode = "";
        if (isset($options['custom_admin_login_logo']['url'])) {
            $custom_logo = $options['custom_admin_login_logo'];
        	$custom_logo_output = '<img src="' . $custom_logo['url'] . '" alt="maintenance" style="height: 62px!important;margin: 0 auto; display: block;" />';
		}
        if (isset($options['enable_maintenance'])) {
            $maintenance_mode = $options['enable_maintenance'];
        } else {
            $maintenance_mode = false;
        }
        if ($maintenance_mode) {
            if (!current_user_can('edit_themes') || !is_user_logged_in()) {
                wp_die($custom_logo_output . '<p style="text-align:center">' . __('We are currently in maintenance mode, please check back shortly.', 'framework') . '</p>', __('Maintenance Mode', 'framework'));
            }
        }
    }
    add_action('get_header', 'imic_maintenance_mode');
}
/* CUSTOM LOGIN LOGO
  ================================================== */
if (!function_exists('imic_custom_login_logo')) {
    function imic_custom_login_logo() {
        $options = get_option('imic_options');
        $custom_logo = "";
        if (isset($options['custom_admin_login_logo'])) {
            $custom_logo = $options['custom_admin_login_logo'];
        }
        echo '<style type="text/css">
			    .login h1 a { background-image:url(' . $custom_logo['url'] . ') !important; background-size: auto !important; width: auto !important; height: 95px !important; }
			</style>';
    }
    add_action('login_head', 'imic_custom_login_logo');
}
/* CATEGORY REL FIX
  ================================================== */
if (!function_exists('imic_add_nofollow_cat')) {
    function imic_add_nofollow_cat($text) {
        $text = str_replace('rel="category tag"', "", $text);
        return $text;
    }
    add_filter('the_category', 'imic_add_nofollow_cat');
}
/* CUSTOM ADMIN MENU ITEMS
  ================================================== */
if (!function_exists('imic_admin_bar_menu')) {
    function imic_admin_bar_menu() {
        global $wp_admin_bar;
        if (current_user_can('manage_options')) {
            $theme_customizer = array(
                'id' => '2',
                'title' => __('Color Customizer', 'framework'),
                'href' => admin_url('/customize.php'),
                'meta' => array('target' => 'blank')
            );
            $wp_admin_bar->add_menu($theme_customizer);
        }
    }
    add_action('admin_bar_menu', 'imic_admin_bar_menu', 99);
}
/* ADMIN CUSTOM POST TYPE ICONS
  ================================================== */
if (!function_exists('imic_admin_css')) {
    function imic_admin_css() {
        $mywp_version = get_bloginfo('version');
        if ($mywp_version < 3.8) {
            ?>
            <style type="text/css" media="screen">
                #menu-posts-event .wp-menu-image {
                    background: url(<?php echo get_template_directory_uri(); ?>/images/wp/portfolio.png) no-repeat 6px 7px!important;
                    background-size: 17px 15px;
                }
                #menu-posts-events:hover .wp-menu-image, #menu-posts-events.wp-has-current-submenu .wp-menu-image {
                    background: url(<?php echo get_template_directory_uri(); ?>/images/wp/portfolio_rollover.png) no-repeat 6px 7px!important;
                }
                #menu-posts-staff .wp-menu-image {
                    background: url(<?php echo get_template_directory_uri(); ?>/images/wp/team.png) no-repeat 6px 11px!important;
                    background-size: 18px 9px;
                }
                #menu-posts-staff:hover .wp-menu-image, #menu-posts-staff.wp-has-current-submenu .wp-menu-image {
                    background: url(<?php echo get_template_directory_uri(); ?>/images/wp/team_rollover.png) no-repeat 6px 11px!important;
                }
                #menu-posts-sermons .wp-menu-image {
                    background: url(<?php echo get_template_directory_uri(); ?>/images/wp/jobs.png) no-repeat 6px 11px!important;
                    background-size: 18px 9px;
                }
                #menu-posts-sermons:hover .wp-menu-image, #menu-posts-sermons.wp-has-current-submenu .wp-menu-image {
                    background: url(<?php echo get_template_directory_uri(); ?>/images/wp/jobs_rollover.png) no-repeat 6px 11px!important;
                }
                #menu-posts-gallery .wp-menu-image {
                    background: url(<?php echo get_template_directory_uri(); ?>/images/wp/showcase.png) no-repeat 6px 11px!important;
                    background-size: 18px 9px;
                }
                #menu-posts-gallery:hover .wp-menu-image, #menu-posts-gallery.wp-has-current-submenu .wp-menu-image {
                    background: url(<?php echo get_template_directory_uri(); ?>/images/wp/showcase_rollover.png) no-repeat 6px 11px!important;
                }
                #menu-posts-slide .wp-menu-image img {
                    width: 16px;
                }
                #toplevel_page_imic_theme_options .wp-menu-image img {
                    width: 11px;
                    margin-top: -2px;
                    margin-left: 3px;
                }
                .toplevel_page_imic_theme_options #adminmenu li#toplevel_page_imic_theme_options.wp-has-current-submenu a.wp-has-current-submenu, .toplevel_page_imic_theme_options #adminmenu #toplevel_page_imic_theme_options .wp-menu-arrow div, .toplevel_page_imic_theme_options #adminmenu #toplevel_page_imic_theme_options .wp-menu-arrow {
                    background: #222;
                    border-color: #222;
                }
                #wpbody-content {
                    min-height: 815px;
                }
                .wp-list-table th#thumbnail, .wp-list-table td.thumbnail {
                    width: 80px;
                }
                .wp-list-table td.thumbnail img {
                    max-width: 100%;
                    height: auto;
                }
            </style>
            <?php
        } else {
            ?>
            <style type="text/css" media="screen">
                .icon16.icon-post:before, #adminmenu #menu-posts-event div.wp-menu-image:before
                {
                    content: "\f337";
                }
                .icon16.icon-post:before, #adminmenu #menu-posts-sermons div.wp-menu-image:before
                {
                    content: "\f110";
                }
                .icon16.icon-post:before, #adminmenu #menu-posts-staff div.wp-menu-image:before
                {
                    content: "\f307";
                }
                .icon16.icon-post:before, #adminmenu #menu-posts-gallery div.wp-menu-image:before
                {
                    content: "\f161";
                }
                #menu-posts-slide .wp-menu-image img {
                    width: 16px;
                }
                #toplevel_page_imic_theme_options .wp-menu-image img {
                    width: 11px;
                    margin-top: -2px;
                    margin-left: 3px;
                }
                .toplevel_page_imic_theme_options #adminmenu li#toplevel_page_imic_theme_options.wp-has-current-submenu a.wp-has-current-submenu, .toplevel_page_imic_theme_options #adminmenu #toplevel_page_imic_theme_options .wp-menu-arrow div, .toplevel_page_imic_theme_options #adminmenu #toplevel_page_imic_theme_options .wp-menu-arrow {
                    background: #222;
                    border-color: #222;
                }
                #wpbody-content {
                    min-height: 815px;
                }
                .wp-list-table th#thumbnail, .wp-list-table td.thumbnail {
                    width: 80px;
                }
                .wp-list-table td.thumbnail img {
                    max-width: 100%;
                    height: auto;
                }
            </style>	
            <?php
        }
    }
    add_action('admin_head', 'imic_admin_css');
}
/* ----------------------------------------------------------------------------------- */
/* Show analytics code in footer */
/* ----------------------------------------------------------------------------------- */
if (!function_exists('imic_analytics')) {
    function imic_analytics() {
        $options = get_option('imic_options');
        if (isset($options['tracking-code'])&&$options['tracking-code'] != "") {
            echo '<script>';
            echo $options['tracking-code'];
            echo '</script>';
        }
    }
    add_action('wp_head', 'imic_analytics');
}
/* CUSTOM CSS OUTPUT
  ================================================== */
if (!function_exists('imic_custom_styles')) {
    function imic_custom_styles() {
        $options = get_option('imic_options');
       
        // OPEN STYLE TAG
        echo '<style type="text/css">' . "\n";
        // Custom CSS
        $custom_css = $options['custom_css'];
		$content_height = $options['content_min_height'];
		$slider_behind_header = (isset($options['slider_behind_header']))?$options['slider_behind_header']:1;
        $content_height=!empty($content_height)?$content_height:400;
		$site_width = $options['site_width'];
        $site_width=!empty($site_width)?$site_width:1040;
		$site_width_spaced=!empty($site_width)?intval($site_width)+40:1080;
		$site_width_nav=!empty($site_width)?intval($site_width)-30:1010;
		$header_height = $options['header_area_height'];
        $header_height=!empty($header_height)?$header_height:80;
		$logo_height=!empty($header_height)?intval($header_height)-15:65;
        $slider_height=!empty($header_height)?intval($header_height)+1:81;
		$slider_margin=!empty($header_height)?intval($header_height)+1:81;
		$header_style3a=!empty($header_height)?intval($header_height)+39:119;
		$header_style3b=!empty($header_height)?intval($header_height)+79:159;
        if ($options['theme_color_type'][0] == 1) {
            $customColor = $options['custom_theme_color'];
            echo '.text-primary, .btn-primary .badge, .btn-link,a.list-group-item.active > .badge,.nav-pills > .active > a > .badge, p.drop-caps:first-child:first-letter, .accent-color, .events-listing .event-detail h4 a, .featured-sermon h4 a, .page-header h1, .post-more, ul.nav-list-primary > li a:hover, .widget_recent_comments a, .navigation .megamenu-container .megamenu-sub-title, .woocommerce div.product span.price, .woocommerce div.product p.price, .woocommerce #content div.product span.price, .woocommerce #content div.product p.price, .woocommerce-page div.product span.price, .woocommerce-page div.product p.price, .woocommerce-page #content div.product span.price, .woocommerce-page #content div.product p.price, .woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price, .cause-item .progress-label, .payment-to-cause a, .event-ticket h4, .event-ticket .ticket-ico{color:' . $customColor . ';}a:hover{color:' . $customColor . ';}.events-listing .event-detail h4 a:hover, .featured-sermon h4 a:hover, .featured-gallery p, .post-more:hover, .widget_recent_comments a:hover{opacity:.9}p.drop-caps.secondary:first-child:first-letter, .accent-bg, .fa.accent-color, .btn-primary,.btn-primary.disabled,.btn-primary[disabled],fieldset[disabled] .btn-primary,.btn-primary.disabled:hover,.btn-primary[disabled]:hover,fieldset[disabled] .btn-primary:hover,.btn-primary.disabled:focus,.btn-primary[disabled]:focus,fieldset[disabled] .btn-primary:focus,.btn-primary.disabled:active,.btn-primary[disabled]:active,fieldset[disabled] .btn-primary:active,.btn-primary.disabled.active,.btn-primary[disabled].active,fieldset[disabled] .btn-primary.active,.dropdown-menu > .active > a,.dropdown-menu > .active > a:hover,.dropdown-menu > .active > a:focus,.nav-pills > li.active > a,.nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus,.pagination > .active > a,.pagination > .active > span,.pagination > .active > a:hover,.pagination > .active > span:hover,.pagination > .active > a:focus,.pagination > .active > span:focus,.label-primary,.progress-bar,a.list-group-item.active,a.list-group-item.active:hover,a.list-group-item.active:focus,.panel-primary > .panel-heading, .carousel-indicators .active, .owl-theme .owl-controls .owl-page.active span, .owl-theme .owl-controls.clickable .owl-page:hover span, hr.sm, .flex-control-nav a:hover, .flex-control-nav a.flex-active, .title-note, .timer-col #days, .featured-block strong, .featured-gallery, .nav-backed-header, .next-prev-nav a, .event-description .panel-heading, .media-box .media-box-wrapper, .staff-item .social-icons a, .accordion-heading .accordion-toggle.active, .accordion-heading:hover .accordion-toggle, .accordion-heading:hover .accordion-toggle.inactive, .nav-tabs li a:hover, .nav-tabs li a:active, .nav-tabs li.active a, .site-header .social-icons a, .timeline > li > .timeline-badge, .header-style3 .toprow, .featured-star, .featured-event-time,.goingon-events-floater-inner, .ticket-cost, .bbp-search-form input[type="submit"]:hover{background-color: ' . $customColor . ';}.fc-event{background-color: ' . $customColor . ';}.mejs-controls .mejs-time-rail .mejs-time-loaded, p.demo_store, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce span.onsale, .woocommerce-page span.onsale, .wpcf7-form .wpcf7-submit, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce .widget_layered_nav ul li.chosen a, .woocommerce-page .widget_layered_nav ul li.chosen a{background: ' . $customColor . ';}.share-buttons.share-buttons-tc > li > a{background: . $customColor . !important;}.btn-primary:hover,.btn-primary:focus,.btn-primary:active,.btn-primary.active,.open .dropdown-toggle.btn-primary, .next-prev-nav a:hover, .staff-item .social-icons a:hover, .site-header .social-icons a:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover, .woocommerce a.button.alt:active, .woocommerce button.button.alt:active, .woocommerce input.button.alt:active, .woocommerce #respond input#submit.alt:active, .woocommerce #content input.button.alt:active, .woocommerce-page a.button.alt:active, .woocommerce-page button.button.alt:active, .woocommerce-page input.button.alt:active, .woocommerce-page #respond input#submit.alt:active, .woocommerce-page #content input.button.alt:active, .wpcf7-form .wpcf7-submit{background: ' . $customColor . ';opacity:.9}.woocommerce .woocommerce-info, .woocommerce-page .woocommerce-info, .woocommerce .woocommerce-message, .woocommerce-page .woocommerce-message{border-top-color: ' .$customColor . ';}.nav .open > a,.nav .open > a:hover,.nav .open > a:focus,.pagination > .active > a,.pagination > .active > span,.pagination > .active > a:hover,.pagination > .active > span:hover,.pagination > .active > a:focus,.pagination > .active > span:focus,a.thumbnail:hover,a.thumbnail:focus,a.thumbnail.active,a.list-group-item.active,a.list-group-item.active:hover,a.list-group-item.active:focus,.panel-primary,.panel-primary > .panel-heading, .fc-events, .event-ticket-left .ticket-handle{border-color:' . $customColor . ';}.fc-event{border-color:' . $customColor . ';}.panel-primary > .panel-heading + .panel-collapse .panel-body{border-top-color:' . $customColor . ';}.panel-primary > .panel-footer + .panel-collapse .panel-body{border-bottom-color:' . $customColor . ';}blockquote{border-left-color:' . $customColor . ';}';
        }
		echo '@media (min-width:1200px){.container{width:'.$site_width.'px;} .navigation{width:'.$site_width_nav.'px}}
		body.boxed .body{max-width:'.$site_width_spaced.'px}
		@media (min-width: 1200px) {body.boxed .body .site-header, body.boxed .body .main-menu-wrapper{width:'.$site_width_spaced.'px;}}';
		if ($options['header_wide_width'] == 1) {
			echo '.topbar > .container, .toprow > .container{width:100%;}';
		}
		if ($options['footer_wide_width'] == 1) {
			echo '.site-footer > .container, .site-footer-bottom > .container{width:100%;}';
		}
		if ($options['recurring_icon'] == 1) {
			echo '.recurring-info-icon{display:inline-block;}';
		} else {
			echo '.recurring-info-icon{display:none;}';
		}
		if($slider_behind_header == 0){
			echo '@media only screen and (max-width: 767px) {.home .hero-slider, .home .slider-revolution-new{top:0!important; margin-bottom:0!important;}}';
		}
		if ($options['sidebar_position'] == 2) {
			echo ' #content-col, #sidebar-col{float:right!important;}';
		}
		if (isset($options['content_wide_width'])&&$options['content_wide_width'] == 1)
		{
			echo '.content .container{width:100%;}';
		}
		if ($options['event_google_icon'] == 1) {
			echo '.event-detail h4 a[href^="https://www.google"]:before, .events-grid .grid-content h3 a[href^="https://www.google"]:before, h3.timeline-title a[href^="https://www.google"]:before{display:inline-block;}';
		} else {
			echo '.event-detail h4 a[href^="https://www.google"]:before, .events-grid .grid-content h3 a[href^="https://www.google"]:before, h3.timeline-title a[href^="https://www.google"]:before{display:none;}';
		}
		
		echo '
			.content{min-height:'.$content_height.'px;}.site-header .topbar{height:'.$header_height.'px;}.site-header h1.logo{height:'.$logo_height.'px;}.home .hero-slider{top:-'.$slider_height.'px;margin-bottom:-'.$slider_margin.'px;}.home .slider-revolution-new{top:-'.$slider_height.'px;margin-bottom:-'.$slider_margin.'px;}.header-style4 .top-navigation > li ul{top:'.$header_height.'px;}.header-style4 .top-navigation > li > a{line-height:'.$header_height.'px;}@media only screen and (max-width: 992px) {.main-menu-wrapper{top:'.$header_height.'px;}}@media only screen and (max-width: 992px) {.header-style3 .main-menu-wrapper{top:'.$header_style3a.'px;}.header-style4 #top-nav-clone{top:'.$header_height.'px;}}@media only screen and (max-width: 767px) {.header-style3 .main-menu-wrapper{top:'.$header_style3b.'px;}}';

		
        // USER STYLES
        if ($custom_css) {
            echo "\n" . '/*========== User Custom CSS Styles ==========*/' . "\n";
            echo $custom_css;
        }
        // CLOSE STYLE TAG
        echo "</style>" . "\n";
    }
    add_action('wp_head', 'imic_custom_styles');
}
/* CUSTOM JS OUTPUT
  ================================================== */
if (!function_exists('imic_custom_script')) {
    function imic_custom_script() {
        $options = get_option('imic_options');
        $custom_js = $options['custom_js'];
        if ($custom_js) {
            echo'<script type ="text/javascript">';
            echo $custom_js;
            echo '</script>';
        }
    }
    add_action('wp_footer', 'imic_custom_script');
}
/* SHORTCODE FIX
  ================================================== */
if (!function_exists('imic_content_filter')) {
    function imic_content_filter($content) {
        // array of custom shortcodes requiring the fix 
        $block = join("|", array("imic_button", "icon", "iconbox", "imic_image", "anchor", "paragraph", "divider", "heading", "alert", "blockquote", "dropcap", "code", "label", "container", "spacer", "span", "one_full", "one_half", "one_third", "one_fourth", "one_sixth","two_third", "progress_bar", "imic_count", "imic_tooltip", "imic_video", "htable", "thead", "tbody", "trow", "thcol", "tcol", "pricing_table", "pt_column", "pt_package", "pt_button", "pt_details", "pt_price", "list", "list_item", "list_item_dt", "list_item_dd", "accordions", "accgroup", "acchead", "accbody", "toggles", "togglegroup", "togglehead", "togglebody", "tabs", "tabh", "tab", "tabc", "tabrow", "section", "page_first", "page_last", "page", "modal_box", "imic_form", "fullcalendar", "staff","fullscreenvideo","event_calender"));
        // opening tag
        $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content);
        // closing tag
        $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", "[/$2]", $rep);
        return $rep;
    }
    add_filter("the_content", "imic_content_filter");
}
/* VIDEO EMBED FUNCTIONS
  ================================================== */
if (!function_exists('imic_video_embed')) {
    function imic_video_embed($url, $width = 'auto', $height = 650,$autopaly=0) {
        if (strpos($url, 'youtube') || strpos($url, 'youtu.be')) {
            return imic_video_youtube($url, $width, $height,$autopaly);
        } 
	elseif (strpos($url, 'facebook') || strpos($url, 'fb.me')) {
            return embed_video_facebook($url, $width, $height,$autopaly);
        }
	else {
            return imic_video_vimeo($url, $width, $height,$autopaly);
        }
    }
}
/* Video Youtube
  ================================================== */
if (!function_exists('imic_video_youtube')) {
    function imic_video_youtube($url, $width = 200, $height = 150,$autopaly) {
        if (stristr($url,'youtu.be/'))
        { preg_match('/(https:|http:|)(\/\/www\.|\/\/|)(.*?)\/(.{11})/i', $url, $video_id); return '<iframe itemprop="video" src="https://youtube.com/embed/' . $video_id[4] . '?autoplay='.$autopaly.'&rel=0" width="' . $width . '" height="' . $height . '" allowfullscreen="allowfullscreen"></iframe>'; }
    else 
        { preg_match('/(https:|http:|)(\/\/www\.|\/\/|)(.*?)\/(embed\/|watch\?v=|(.*?)&v=|v\/|e\/|.+\/|watch.*v=|)([a-zA-Z0-9_-]{11})/i', $url, $video_id); return '<iframe itemprop="video" src="https://youtube.com/embed/' . $video_id[6] . '?autoplay='.$autopaly.'&rel=0" width="' . $width . '" height="' . $height . '" allowfullscreen="allowfullscreen"></iframe>';
		}
    }
}
/* Video Vimeo
  ================================================== */
if (!function_exists('imic_video_vimeo')) {
   function imic_video_vimeo($url, $width = 200, $height = 150,$autopaly) {
        preg_match('/https?:\/\/vimeo.com\/(\d+)$/', $url, $video_id);
        return '<iframe src="https://player.vimeo.com/video/' . $video_id[1] . '" width="' . $width . '" height="' . $height . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
    }
}

/* Video facebook
  ================================================== */
if (!function_exists('embed_video_facebook')) {
   function embed_video_facebook($url, $width = 'auto', $height = 600,$autopaly) {
	$video_url_encoded = urlencode($url); 
	$html =<<<HTML
<iframe style="border: none; overflow: hidden;" src="https://www.facebook.com/plugins/video.php?href={$video_url_encoded}" width="{$width}" height="{$height}" style="min-height:600px; width:auto;"  frameborder="0" scrolling="no" allowfullscreen="allowfullscreen"></iframe>
HTML;
	return $html; 
  }
}
/* Soundcloud Audio
====================================================== */
//Get the SoundCloud URL
if (!function_exists('imic_audio_soundcloud')) {
   function imic_audio_soundcloud($url, $width = "100%", $height = 250) {
$getValues=file_get_contents('http://soundcloud.com/oembed?format=js&url='.$url.'&iframe=true');
$decodeiFrame=substr($getValues, 1, -2);
$jsonObj = json_decode($decodeiFrame);
return str_replace('height="200"', 'height="250"', $jsonObj->html);
   }
}
/* REGISTER SIDEBARS
  ================================================== */
if (!function_exists('imic_register_sidebars')) {
    function imic_register_sidebars() {
        if (function_exists('register_sidebar')) {
			$options = get_option('imic_options');
			$footer_class = $options["footer_layout"];
            register_sidebar(array(
                'name' => __('Home Page Sidebar', 'framework'),
                'id' => 'main-sidebar',
                'description' => '',
                'class' => '',
                'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widgettitle">',
                'after_title' => '</h3>'
            ));
            register_sidebar(array(
                'name' => __('Contact Sidebar', 'framework'),
                'id' => 'contact-sidebar',
                'description' => '',
                'class' => '',
                'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="sidebar-widget-title"><h3 class="widgettitle">',
                'after_title' => '</h3></div>'
            ));
            register_sidebar(array(
                'name' => __('Inner Page Sidebar', 'framework'),
                'id' => 'inner-sidebar',
                'description' => '',
                'class' => '',
                'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="sidebar-widget-title"><h3 class="widgettitle">',
                'after_title' => '</h3></div>'
            ));
            register_sidebar(array(
                'name' => __('Sermons Sidebar', 'framework'),
                'id' => 'sermons-sidebar',
                'description' => '',
                'class' => '',
                'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="sidebar-widget-title"><h3 class="widgettitle">',
                'after_title' => '</h3></div>'
            ));
            register_sidebar(array(
                'name' => __('Event Page Sidebar', 'framework'),
                'id' => 'event-sidebar',
                'description' => '',
                'class' => '',
                'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="sidebar-widget-title"><h3 class="widgettitle">',
                'after_title' => '</h3></div>'
            ));
            register_sidebar(array(
                'name' => __('Single Event Page Sidebar', 'framework'),
                'id' => 'single-event-sidebar',
                'description' => '',
                'class' => '',
                'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="sidebar-widget-title"><h3 class="widgettitle">',
                'after_title' => '</h3></div>'
            ));
            register_sidebar(array(
                'name' => __('Post Sidebar', 'framework'),
                'id' => 'post-sidebar',
                'description' => '',
                'class' => '',
                'before_widget' => '<div class="widget sidebar-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="sidebar-widget-title"><h3>',
                'after_title' => '</h3></div>'
            ));
            register_sidebar(array(
                'name' => __('Footer Sidebar', 'framework'),
                'id' => 'footer-sidebar',
                'description' => '',
                'class' => '',
                'before_widget' => '<div class="col-md-'.$footer_class.' col-sm-'.$footer_class.' widget footer-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h4 class="footer-widget-title">',
                'after_title' => '</h4>'
            ));
        }
    }
    add_action('init', 'imic_register_sidebars', 35);
}
if (!function_exists('imic_dateDiff')) {
function imic_dateDiff($start, $end) {
  $start_ts = strtotime($start);
  $end_ts = strtotime($end);
  $diff = $end_ts - $start_ts;
  return round($diff / 86400);
}
}
// get taxonomies terms links
if (!function_exists('imic_custom_taxonomies_terms_links')) {
    function imic_custom_taxonomies_terms_links() {
        global $post;
        // get post by post id
        $post = get_post($post->ID);
        // get post type by post
        $post_type = $post->post_type;
        // get post type taxonomies
        $taxonomies = get_object_taxonomies($post_type, 'objects');
        $out = array();
        foreach ($taxonomies as $taxonomy_slug => $taxonomy) {
            // get the terms related to post
            $terms = get_the_terms($post->ID, $taxonomy_slug);
            if (!empty($terms)) {
                $i = 1;
                foreach ($terms as $term) {
                    if ($i == 1) {
                        $out[] =
                                ' <a href="'
                                . get_term_link($term->slug, $taxonomy_slug) . '">'
                                . $term->name
                                . "</a>";
                    }
                    $i++;
                }
            }
        }
        return implode('', $out);
    }
}
/**
 * Sidebar Meta Box.
 * @since NativeChurch 1.2
 */
if (!function_exists('imic_get_all_sidebars')) {
    function imic_get_all_sidebars() {
        $all_sidebars = array();
        global $wp_registered_sidebars;
        $all_sidebars = array('' => '');
        foreach ($wp_registered_sidebars as $sidebar) {
            $all_sidebars[$sidebar['id']] = $sidebar['name'];
        }
        return $all_sidebars;
    }
}
if (!function_exists('imic_register_meta_box')) {
    add_action('admin_init', 'imic_register_meta_box');
    function imic_register_meta_box() {
        // Check if plugin is activated or included in theme
        if (!class_exists('RW_Meta_Box'))
            return;
        $prefix = 'imic_';
        $meta_box = array(
            'id' => 'select_sidebar',
            'title' => __("Select Sidebar", 'framework'),
            'pages' => array('post', 'page', 'event', 'sermons', 'staff', 'product', 'causes'),
            'context' => 'normal',
            'fields' => array(
                array(
                    'name' => 'Select Sidebar from list',
                    'id' => $prefix . 'select_sidebar_from_list',
                    'desc' => __("Select Sidebar from list", 'framework'),
                    'type' => 'select',
                    'options' => imic_get_all_sidebars(),
                ),
                array(
                    'name' => 'Select Sidebar Position',
                    'id' => $prefix . 'select_sidebar_position',
                    'desc' => __("Select Sidebar Postion", 'framework'),
                    'type' => 'radio',
                    'options' => array(
						'1' => 'Right',
						'2' => 'Left'
					),
					'default' => '1'
                ),
            ),
            
        );
        new RW_Meta_Box($meta_box);
    }
}
//Manage Staff Post Type Menu Order Column
function add_new_staff_column($header_text_columns) {
  $header_text_columns['menu_order'] = "Order";
  return $header_text_columns;
}
add_action('manage_edit-staff_columns', 'add_new_staff_column');
function show_order_column($name){
  global $post;
  switch ($name) {
    case 'menu_order':
      $order = $post->menu_order;
      echo $order;
      break;
   default:
      break;
   }
}
add_action('manage_staff_posts_custom_column','show_order_column');
function order_column_register_sortable($columns){
  $columns['menu_order'] = 'menu_order';
  return $columns;
}
add_filter('manage_edit-staff_sortable_columns','order_column_register_sortable');
function afterSavePost()
{
	if(isset($_GET['post']))
	 { 
	 $postId = $_GET['post'];
	$post_type = get_post_type($postId);
	if($post_type=='event')
	{
		/////////////////////////////////////////////////////////////////
		$sdate = get_post_meta($postId,'imic_event_start_dt',true);
		$start_time = get_post_meta($postId,'imic_event_start_tm',true);
		$end_time = get_post_meta($postId,'imic_event_end_tm',true);
		$all_day = get_post_meta($postId,'imic_event_all_day',true);
		$all_day = ($all_day == null||$all_day == 0)?0:$all_day;
		////////////////////////////////////////////////////////////////
		$sdate_unix = strtotime($sdate);
		$sdate_ymd = date('Y-m-d',$sdate_unix);
		$end_event_date = get_post_meta($postId,'imic_event_end_dt',true);
		$edate_unix = strtotime($end_event_date);
		$edate_ymd = date('Y-m-d',$edate_unix);
		if($end_event_date=='') { update_post_meta($postId,'imic_event_end_dt',$sdate); }
		$frequency = get_post_meta($postId,'imic_event_frequency',true);
		$frequency_count = get_post_meta($postId,'imic_event_frequency_count',true);
		$value = strtotime($sdate);
		if($frequency==32) { $frequency_count = 20; }
		if($frequency==30) {
		$svalue = strtotime("+".$frequency_count." month", $value);
		$suvalue = date('Y-m-d',$svalue);
		}
		else {
		$svalue = intval($frequency)*intval($frequency_count)*86400;
		$suvalue = intval($svalue)+intval($value);
		$suvalue = date('Y-m-d',$suvalue);
		}
		$count_days = imic_dateDiff($sdate_ymd,$edate_ymd);
		if($count_days>0) { $suvalue = $edate_ymd; }
		update_post_meta($postId,'imic_event_frequency_end',$suvalue); 
		#if user not check all day checkbox as well as empty start,end time than update time here.
		if($all_day == 0 && empty($start_time))
		{
			update_post_meta($postId,'imic_event_start_tm','23:59');
		}
		if($all_day == 0 && empty($end_time))
		{
			update_post_meta($postId,'imic_event_end_tm','23:59');
		}
	} }
}
afterSavePost();
//Add New Custom Menu Option
if ( !class_exists('IMIC_Custom_Nav')) {
class IMIC_Custom_Nav {
public function add_nav_menu_meta_boxes() {
   
add_meta_box(
'mega_nav_link',
__('Mega Menu','framework'),
array( $this, 'nav_menu_link'),
'nav-menus',
'side',
'low'
);
}
public function nav_menu_link() {
    
     global $_nav_menu_placeholder, $nav_menu_selected_id;
	$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;
    
        ?>
<div id="posttype-wl-login" class="posttypediv">
<div id="tabs-panel-wishlist-login" class="tabs-panel tabs-panel-active">
<ul id ="wishlist-login-checklist" class="categorychecklist form-no-clear">
<li>
<label class="menu-item-title">
<input type="checkbox" class="menu-item-object-id" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-object-id]" value="<?php echo $_nav_menu_placeholder; ?>"> <?php _e('Create Column','framework'); ?>
</label>
    <input type="hidden" class="menu-item-db-id" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-db-id]" value="0">
    <input type="hidden" class="menu-item-object" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-object]" value="page">
<input type="hidden" class="menu-item-parent-id" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-parent-id]" value="0">
   <input type="hidden" class="menu-item-type" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" value="">
<input type="hidden" class="menu-item-title" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]" value="<?php _e('Column','framework'); ?>">
<input type="hidden" class="menu-item-classes" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-classes]" value="custom_mega_menu">
</li>
</ul>
</div>
<p class="button-controls">
<span class="add-to-menu">
<input type="submit" class="button-secondary submit-add-to-menu right" value="<?php _e('Add to Menu','framework'); ?>" name="add-post-type-menu-item" id="submit-posttype-wl-login">
<span class="spinner"></span>
</span>
</p>
</div>
<?php }
}
}
$custom_nav = new IMIC_Custom_Nav;
add_action('admin_init', array($custom_nav, 'add_nav_menu_meta_boxes'));
//Get All Post Types
if(!function_exists('imic_get_all_types')){
add_action( 'wp_loaded', 'imic_get_all_types');
function imic_get_all_types(){
   $args = array(
   'public'   => true,
   );
$output = 'names'; // names or objects, note names is the default
return $post_types = get_post_types($args, $output); 
}
}
//Get Days Diff
if(!function_exists('imic_day_diff')){
function imic_day_diff($value) {
    $endEventTemp = get_post_meta($value, 'imic_event_end_dt', true);
    $startEventTemp = get_post_meta($value, 'imic_event_start_dt', true);
    $timeTemp = get_post_meta($value, 'imic_event_start_tm', true);
	$timeTemp = strtotime($timeTemp);
	$timeTemp = date(get_option('time_format'),$timeTemp);
    $endEventTemp = $endEventTemp . ' ' . $timeTemp;
    $startEventTemp = $startEventTemp . ' ' . $timeTemp;
    $endEventTemp = strtotime($endEventTemp);
    $startEventTemp = strtotime($startEventTemp);
    $daysTemp = $endEventTemp - $startEventTemp;
    $daysTemp = $daysTemp / 86400;
    return $daysTemp = floor($daysTemp);
}
}
//Attachment Meta Box
if(!function_exists('imic_attachment_url')){
function imic_attachment_url( $fields, $post ) {
$meta = get_post_meta($post->ID, 'meta_link', true);
$fields['meta_link'] = array(
'label' => __('Image URL','framework'),
'input' => 'text',
'value' => $meta,
'show_in_edit' => true,
);
return $fields;
}
add_filter( 'attachment_fields_to_edit', 'imic_attachment_url', 10, 2 );
}
/**
* Update custom field on save
*/
if(!function_exists('imic_update_attachment_url')){
function imic_update_attachment_url($attachment){
global $post;
update_post_meta($post->ID, 'meta_link', $attachment['attachments'][$post->ID]['meta_link']);
return $attachment;
}
add_filter( 'attachment_fields_to_save', 'imic_update_attachment_url', 4);
}
/**
* Update custom field via ajax
*/
if(!function_exists('imic_save_attachment_url')){
function imic_save_attachment_url() {
$post_id = $_POST['id'];
$meta = $_POST['attachments'][$post_id ]['meta_link'];
update_post_meta($post_id , 'meta_link', $meta);
clean_post_cache($post_id);
}
add_action('wp_ajax_save-attachment-compat', 'imic_save_attachment_url', 0, 1);
}
//Get Attachment Fields
if(!function_exists('imic_wp_get_attachment')){
function imic_wp_get_attachment( $attachment_id ) {
	$attachment = get_post( $attachment_id );
    if(!$attachment || !is_numeric($attachment_id)) return;
	return array(
		'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'caption' => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		'href' => get_permalink( $attachment->ID ),
		'src' => $attachment->guid,
		'title' => $attachment->post_title,
		'url' => $attachment->meta_link
	);
}
}
/* -------------------------------------------------------------------------------------
  Get Recursive Event Data.
  @since NativeChurch 1.4
  ----------------------------------------------------------------------------------- */
if (!function_exists('imic_get_recursive_event_data')) {
    function imic_get_recursive_event_data($menuposttype, $menupost, $p = '') {
        $event_add_menu = array();
        $sinc = 1;
        $item_output = '';
		$event_add_menu = imic_recur_events("future","","","");
            $nos_event_menu = 1;
            ksort($event_add_menu);
            foreach ($event_add_menu as $key => $value) {
                 $date_converted=date('Y-m-d',$key );
                $custom_event_url= imic_query_arg($date_converted,$value);
            $recurrence = get_post_meta($value, 'imic_event_frequency', true);
                if ($recurrence > 0) {
                    $icon = ' <i class="fa fa-refresh" style="font-size:80%;" title="'.__('Recurring','framework').'"></i>';
                } else {
                    $icon = '';
                }
                $eventDataTitle = get_the_title($value);
                $eventDataURL = $custom_event_url;
                
                $day = date_i18n('l', $key). ' |';
                $eventStartTime = get_post_meta($value, 'imic_event_start_tm', true);
                $eventTime = get_post_meta($value, 'imic_event_start_tm', true);
                $eventTime = strtotime($eventTime);
                $stime = '';
                if ($eventTime != '') {
                    $stime = date_i18n(get_option('time_format'), $eventTime);
                }
                $item_output.='<li>';
                $item_output.='<a href="' . $eventDataURL . '">' . $eventDataTitle . $icon . '</a>';
                $item_output.= '<span class="meta-data">' . $day . '  ' . $stime . '</span>';
                $item_output.='</li>';
                if (++$nos_event_menu > $menupost)
                    break;
            }
        return $item_output;
    }
}
/* -------------------------------------------------------------------------------------
  Get Cat List.
  @since NativeChurch 1.4
  ----------------------------------------------------------------------------------- */
if (!function_exists('imic_get_cat_list')) {
    function imic_get_cat_list() {
        $amp_categories_obj = get_categories('exclude=1');
         
        $amp_categories = array();
        if(count($amp_categories_obj)>0){
        foreach ($amp_categories_obj as $amp_cat) {
            $amp_categories[$amp_cat->cat_ID] = $amp_cat->name;
        }}
        return $amp_categories;
    }
}
/* -------------------------------------------------------------------------------------
  Filter the Widget Title.
  @since NativeChurch 1.4
  ----------------------------------------------------------------------------------- */
if (!function_exists('imic_widget_titles')) {
    add_filter('dynamic_sidebar_params', 'imic_widget_titles', 20);
    function imic_widget_titles(array $params) {
        // $params will ordinarily be an array of 2 elements, we're only interested in the first element
        $widget = & $params[0];
        $id = $params[0]['id'];
        if ($id == 'footer-sidebar') {
            $widget['before_title'] = '<h4 class="widgettitle">';
            $widget['after_title'] = '</h4>';
        } else {
            $widget['before_title'] = '<div class="sidebar-widget-title"><h3 class="widgettitle">';
            $widget['after_title'] = '</h3></div>';
        }
        return $params;
    }
}
/* -------------------------------------------------------------------------------------
  Filter the Widget Text.
  @since NativeChurch 1.4
  ----------------------------------------------------------------------------------- */
add_filter('widget_text', 'do_shortcode');
/* -------------------------------------------------------------------------------------
Month Translate in Default.
  @since NativeChurch 1.4
  ----------------------------------------------------------------------------------- */
    if(!function_exists('imic_month_translate')){
function imic_month_translate( $str ) {
  
	$options = get_option('imic_options');
       $months = $options["calendar_month_name"];
    $months = explode(',',$months);
  if(count($months)<=1){
  $months = array("January","February","March","April","May","June","July","August","September","October","November","December");
}
$sb = array();
foreach($months as $month) { $sb[] = $month; } 
    $engMonth = array("January","February","March","April","May","June","July","August","September","October","November","December");
    $trMonth = $sb;
    $converted = str_replace($engMonth, $trMonth, $str);
    return $converted;
    }
    /* -------------------------------------------------------------------------------------
  Filter the  Month name of Post.
  @since NativeChurch 1.4
  ----------------------------------------------------------------------------------- */
add_filter( 'get_the_time', 'imic_month_translate' );
add_filter( 'the_date', 'imic_month_translate' );
add_filter( 'get_the_date', 'imic_month_translate' );
add_filter( 'comments_number', 'imic_month_translate' );
add_filter( 'get_comment_date', 'imic_month_translate' );
add_filter( 'get_comment_time', 'imic_month_translate' );
add_filter( 'date_i18n', 'imic_month_translate' );
}
/* -------------------------------------------------------------------------------------
  Short Month Translate in Default.
  @since NativeChurch 1.4
  ----------------------------------------------------------------------------------- */
if(!function_exists('imic_short_month_translate')){
function imic_short_month_translate( $str ) {
    
       $options = get_option('imic_options');
       $months = $options["calendar_month_name_short"];
    $months = explode(',',$months);
  if(count($months)<=1){
  $months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
}
$sb = array();
foreach($months as $month) { $sb[] = $month; } 
    $engMonth = array("/\bJan\b/","/\bFeb\b/","/\bMar\b/","/\bApr\b/","/\bMay\b/","/\bJun\b/","/\bJul\b/","/\bAug\b/","/\bSep\b/","/\bOct\b/","/\bNov\b/","/\bDec\b/");
    $trMonth = $sb;
    $converted = preg_replace($engMonth, $trMonth, $str);
    return $converted;
}
/* -------------------------------------------------------------------------------------
  Filter the  Sort Month name of Post.
  @since NativeChurch 1.4
  ----------------------------------------------------------------------------------- */
add_filter( 'get_the_time', 'imic_short_month_translate' );
add_filter( 'the_date', 'imic_short_month_translate' );
add_filter( 'get_the_date', 'imic_short_month_translate' );
add_filter( 'comments_number', 'imic_short_month_translate' );
add_filter( 'get_comment_date', 'imic_short_month_translate' );
add_filter( 'get_comment_time', 'imic_short_month_translate' );
add_filter( 'date_i18n', 'imic_short_month_translate' );
}
 if(!function_exists('imic_day_translate')){
function imic_day_translate( $str ) {
	$options = get_option('imic_options');
       $days = $options["calendar_day_name"];
    $days = explode(',',$days);
  if(count($days)<=1){
  $days = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
}
$sb = array();
foreach($days as $month) { $sb[] = $month; } 
    $engDay = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
    $trDay = $sb;
    $converted = str_replace($engDay, $trDay, $str);
    return $converted;
    }
    /* -------------------------------------------------------------------------------------
  Filter the  Day name of Post.
  @since NativeChurch 1.4
  ----------------------------------------------------------------------------------- */
add_filter('date_i18n', 'imic_day_translate');
}
 /* -------------------------------------------------------------------------------------
  Global Month Name.
  @since NativeChurch 1.4
  ----------------------------------------------------------------------------------- */
if(!function_exists('imic_global_month_name')){
function imic_global_month_name($key){
    return date_i18n("M",$key);
}}
/* -------------------------------------------------------------------------------------
  RevSlider ShortCode
  @since NativeChurch 1.4
  ----------------------------------------------------------------------------------- */
if(!function_exists('RevSliderShortCode')){
function RevSliderShortCode(){
     $slidernames = array();
    if(class_exists('RevSlider')){
     $sld = new RevSlider();
                $sliders = $sld->getArrSliders();
        if(!empty($sliders)){
           
        foreach($sliders as $slider){
          $title=$slider->getParam('title','false');
           $shortcode=$slider->getParam('shortcode','false');
            $slidernames[esc_attr($shortcode)]=$title;
        }}
           
}
return $slidernames;
        }}
/** -------------------------------------------------------------------------------------
 * Gallery Flexslider
 * @since NativeChurch 1.5
 * @param ID of current Post.
 * @return Div with flexslider parameter.
  ----------------------------------------------------------------------------------- */
if (!function_exists('imic_gallery_flexslider')) {
    function imic_gallery_flexslider($id) {
		$speed = (get_post_meta(get_the_ID(), 'imic_gallery_slider_speed', true)!='')?get_post_meta(get_the_ID(), 'imic_gallery_slider_speed', true):5000;
        $pagination = get_post_meta(get_the_ID(), 'imic_gallery_slider_pagination', true);
        $auto_slide = get_post_meta(get_the_ID(), 'imic_gallery_slider_auto_slide', true);
        $direction = get_post_meta(get_the_ID(), 'imic_gallery_slider_direction_arrows', true);
        $effect = get_post_meta(get_the_ID(), 'imic_gallery_slider_effects', true);
        $pagination = !empty($pagination) ? $pagination : 'yes';
        $auto_slide = !empty($auto_slide) ? $auto_slide : 'yes';
        $direction = !empty($direction) ? $direction : 'yes';
        $effect = !empty($effect) ? $effect : 'slide';
        echo '<div class="flexslider" data-autoplay="' . $auto_slide . '" data-pagination="' . $pagination . '" data-arrows="' . $direction . '" data-style="' . $effect . '" data-pause="yes" data-speed='.$speed.'>';
    }
}
/** -------------------------------------------------------------------------------------
 * Sermons Audio
 * @since NativeChurch 1.6
 * @param ID of current Post.
 * @return Attach Full Audio Url.
  ----------------------------------------------------------------------------------- */
if (!function_exists('imic_sermon_attach_full_audio')) {
    function imic_sermon_attach_full_audio($id) {
                        $imic_sermons_audio_upload = get_post_meta($id, 'imic_sermons_audio_upload', true);
                        if($imic_sermons_audio_upload==1){
                          $imic_sermons_audio = get_post_meta($id, 'imic_sermons_audio', true);  
                          $attach_full_audio = wp_get_attachment_url($imic_sermons_audio);
                         }
                         else{
                            $imic_sermons_audio = get_post_meta($id, 'imic_sermons_url_audio', true);  
                          $attach_full_audio = $imic_sermons_audio;  
                         }
                         return $attach_full_audio;
    }
}
/** -------------------------------------------------------------------------------------
 * Sermons Pdf
 * @since NativeChurch 1.6
 * @param ID of current Post.
 * @return Attach Full Pdf Url.
  ----------------------------------------------------------------------------------- */
if (!function_exists('imic_sermon_attach_full_pdf')) {
    function imic_sermon_attach_full_pdf($id) {
                        $imic_sermons_pdf_upload_option = get_post_meta($id, 'imic_sermons_pdf_upload_option', true);
                        if($imic_sermons_pdf_upload_option==1){
                          $imic_sermons_pdf = get_post_meta($id, 'imic_sermons_Pdf', true);  
                         $attach_pdf = wp_get_attachment_url($imic_sermons_pdf);
                         }
                         else{
                          $attach_pdf = get_post_meta($id,'imic_sermons_pdf_by_url', true);  
                         }
                         return $attach_pdf;
    }
}
/** -------------------------------------------------------------------------------------
 * Add Query Arg
 * @since NativeChurch 1.6
 * @param  ID,param1,param2 of current Post.
 * @return  Url with Query arg which is passed default is event_date.
  ----------------------------------------------------------------------------------- */
if(!function_exists('imic_query_arg')){
 function imic_query_arg($date_converted,$id){
        $custom_event_url=esc_url_raw(add_query_arg('event_date',$date_converted,get_permalink($id)));
    return $custom_event_url;
  }
}
/** -------------------------------------------------------------------------------------
 * Add Query Arg For Event Cat
 * @since NativeChurch 1.6
 * @param  ID,param1 of current Post.
 * @return  Url with Query arg which is passed.
  ----------------------------------------------------------------------------------- */
if(!function_exists('imic_query_arg_event_cat')){
 function imic_query_arg_event_cat($string,$url){
        $imic_event_category_page_url=esc_url(add_query_arg('event_cat',$string,$url));
    return $imic_event_category_page_url;
        
    }
}
/** -------------------------------------------------------------------------------------
 * Query Var Filter
 * @since NativeChurch 1.6
 * @description event_date parameter is added to query_vars filter
----------------------------------------------------------------------------------- */
if(!function_exists('imicAddQueryVarsFilter')){
function imicAddQueryVarsFilter( $vars ){
  $vars[] = "event_date";
  $vars[] = "event_cat";
  $vars[] = "pg";
  $vars[] = "login";
	$vars[] = "calendar";
  return $vars;
}
add_filter('query_vars','imicAddQueryVarsFilter');
}
/** -------------------------------------------------------------------------------------
 * Convert the Format String from php to fullcalender
 * @see http://arshaw.com/fullcalendar/docs/utilities/formatDate/
 * @since NativeChurch 1.6
 * @param $format
----------------------------------------------------------------------------------- */
if(!function_exists('ImicConvertDate')){
	 function ImicConvertDate($format) {
	 	$format_rules = array('a'=>'t',
			 'A'=>'T',
			 'B'=>'',
			 'c'=>'u',
			 'd'=>'dd',
			 'D'=>'ddd',
			 'F'=>'MMMM',
			 'g'=>'h',
			 'G'=>'H',
			 'h'=>'hh',
			 'H'=>'HH',
			 'i'=>'mm',
			 'I'=>'',
			 'j'=>'d',
			 'l'=>'dddd',
			 'L'=>'',
			 'm'=>'MM',
			 'M'=>'MMM',
			 'n'=>'M',
			 'O'=>'',
			 'r'=>'',
			 's'=>'ss',
			 'S'=>'S',
			 't'=>'',
			 'T'=>'',
			 'U'=>'',
			 'w'=>'',
			 'W'=>'',
			 'y'=>'yy',
			 'Y'=>'yyyy',
			 'z'=>'',
			 'Z'=>'');
	 	  $ret = '';
	 	for ($i=0; $i<strlen($format); $i++) {
	 		if (isset($format_rules[$format[$i]])) {
	 			$ret .= $format_rules[$format[$i]];
	 		} else {
	 			$ret .= $format[$i];
	 		}
	 	}
	 	return $ret;
}}
/** -------------------------------------------------------------------------------------
 * Return 0 if category have any post
 * @since NativeChurch 1.6
 ----------------------------------------------------------------------------------- */
if(!function_exists('imic_cat_count_flag')){
function imic_cat_count_flag(){
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
             $flag=1;
              if(!empty($term)){
                 $flag= $output=($term->count==0)?0:1;
              }
             global $cat;
             if(!empty($cat)){
               	$cat_data= get_category($cat);
                $flag=($cat_data->count==0)?0:1;
             }
             return $flag;
}}
/** -------------------------------------------------------------------------------------
 * Return sidebar and set page design 
 * @since NativeChurch 1.7
 ----------------------------------------------------------------------------------- */
if(!function_exists('imic_page_design')){
	function imic_page_design($id = '', $block=9){
		//Make page design according sidebar conditions
		$options = get_option('imic_options');
		$ID = (!empty($id) &&  $id != '')? $id : get_the_ID();
		if(is_home()) { $ID = get_option('page_for_posts'); }
		$pageSidebar = get_post_meta($ID,'imic_select_sidebar_from_list', true);
		$post_type = get_post_type($ID);
		$sidebar_value = $sidebar = '';
		switch ($post_type) {
			case 'post':
				$sidebar_value = $options['post_sidebar'];
				break;
			case 'page':
				$sidebar_value = $options['page_sidebar'];
				break;
			case 'event':
				$sidebar_value = $options['event_sidebar'];
				break;
			case 'causes':
				$sidebar_value = $options['cause_sidebar'];
				break;
			case 'sermons':
				$sidebar_value = $options['sermon_sidebar'];
				break;
			case 'staff':
				$sidebar_value = $options['staff_sidebar'];
				break;
		}
		$classMain = 'col-md-'. $block;
		if(!empty($pageSidebar)){
			$sidebar = $pageSidebar;
		}else if(!empty($sidebar_value)){
			$sidebar = $sidebar_value;	
		}else{
			$classMain = 'col-md-12';	
		}		
		$pageDesign = array('class' => $classMain, 'sidebar' => $sidebar);
		return $pageDesign;		
	}
}
/** -------------------------------------------------------------------------------------
 * Return Recursive Event data 
 * @since NativeChurch 1.7
 ----------------------------------------------------------------------------------- */
if (!function_exists('imic_get_home_recursive_event_data')) {
function imic_get_home_recursive_event_data($specific_event_cat) {
$item_output = '';
$event_add_menu = array();
if(!empty($specific_event_cat)){
$event_cat_data= get_term_by('id',$specific_event_cat,'event-category');
$event_cat= $event_cat_data->slug;
$sinc = 1;
$today_specific = date('Y-m-d');
$posts_event = get_posts(array('post_type' => 'event', 'event-category' => $event_cat,'post_status' => 'publish', 'meta_key' => 'imic_event_start_dt', 'suppress_filters' => false, 'meta_query' => array(array('key' => 'imic_event_frequency_end', 'value' => $today_specific, 'compare' => '>=')), 'orderby' => 'meta_value', 'order' => 'ASC', 'posts_per_page' => -1));
if (!empty($posts_event)) {
foreach ($posts_event as $event_post_data) {
$eventDate = strtotime(get_post_meta($event_post_data->ID, 'imic_event_start_dt', true));
$eventTime = get_post_meta($event_post_data->ID, 'imic_event_start_tm', true);
$frequency = get_post_meta($event_post_data->ID, 'imic_event_frequency', true);
$frequency_count = '';
$frequency_count = get_post_meta($event_post_data->ID, 'imic_event_frequency_count', true);
if ($frequency > 0) {
    $frequency_count = $frequency_count;
} 
else { $frequency_count = 0; }
$seconds = $frequency * 86400;
$fr_repeat = 0;
while ($fr_repeat <= $frequency_count) {
$eventDate = get_post_meta($event_post_data->ID, 'imic_event_start_dt', true);
$event_Start_time = get_post_meta($event_post_data->ID,'imic_event_start_tm',true);
$eventDate = strtotime($eventDate.' '.$event_Start_time);
if($frequency==30) {
$eventDate = strtotime("+".$fr_repeat." month", $eventDate);
}
else {
$new_date = $seconds * $fr_repeat;
$eventDate = $eventDate + $new_date;
}
$date_sec = date('Y-m-d', $eventDate);
$exact_time = strtotime($date_sec . ' ' .$eventTime);
if ($exact_time >= date('U')) {
$event_add_menu[$eventDate + $sinc] = $event_post_data->ID;
$sinc++;
} $fr_repeat++;
}
}
$nos_event_menu = 1;
ksort($event_add_menu);
foreach ($event_add_menu as $key => $value) {
/*$date_converted=date('Y-m-d',$key );
$custom_event_url= imic_query_arg($date_converted,$value);
$eventDataTitle = get_the_title($value);
$eventDataURL = $custom_event_url;
$item_output.='<h5>';
$item_output.='<a href="' . $eventDataURL . '">' . $eventDataTitle.'</a></h5>';
$item_output.= '<span class="meta-data">' .date_i18n( get_option( 'date_format' ),$key). '</span>';
$item_output.='</div>';
$eventTime = get_post_meta($value, 'imic_event_start_tm', true);
$eventTime = strtotime($eventTime);
$counter_time = date('h:i A',$eventTime);
$firstEventDateData = date('F j, Y', $key) . ' ' . $counter_time;
$item_output.='<div id="counter" class="col-md-4 col-sm-6 col-xs-12 counter" data-date="'.strtotime($firstEventDateData).'">';
if (++$nos_event_menu > 1)
    break;
}*/
$options = get_option('imic_options');
$eventTime = get_post_meta($value, 'imic_event_start_tm', true);
$event_End_time = get_post_meta($value, 'imic_event_end_tm', true);
$event_End_time = strtotime($event_End_time);
$eventTime = strtotime($eventTime);
$count_from = (isset($options['countdown_timer']))?$options['countdown_timer']:'';
if($count_from==1) { $counter_time = date('G:i',$event_End_time); }
else { $counter_time = date('G:i',$eventTime); }
$firstEventDateData = date('Y-m-d', $key) . ' ' . $counter_time;
$firstEventTitle = get_the_title($value);
$firstEventDate = date_i18n( get_option( 'date_format' ),$key);
$date_converted=date('Y-m-d',$key );
$firstEventURL = imic_query_arg($date_converted,$value);
$item_output.='<h5><a href="'.$firstEventURL.'">'.$firstEventTitle.'</a></h5>';
$item_output.='<span class="meta-data">'.$firstEventDate.'</span></div>';
$item_output.='<div id="counter" class="col-md-4 col-sm-6 col-xs-12 counter" data-date="'.strtotime($firstEventDate).'">';
$item_output.='<div class="timer-col"> <span id="days"></span> <span class="timer-type">'. __('days', 'framework');
$item_output.='</span></div>';
$item_output.='<div class="timer-col"> <span id="hours"></span> <span class="timer-type">'. __('hrs', 'framework');
$item_output.='</span></div>';
$item_output.='<div class="timer-col"> <span id="minutes"></span> <span class="timer-type">'.__('mins', 'framework');
$item_output.='</span></div>';
$item_output.='<div class="timer-col"> <span id="seconds"></span> <span class="timer-type">'.__('secs', 'framework');
$item_output.='</span></div></div>';
break;
}
}}
return $item_output;
}
}
/** -------------------------------------------------------------------------------------
 * Blog Template Redirect
 * @since NativeChurch 1.7
 ----------------------------------------------------------------------------------- */
if(!function_exists('imicBlogTemplateRedirect')){
function imicBlogTemplateRedirect()
{   
$page_for_posts= get_option('page_for_posts');  
//check by Blog
if(is_home()&&!empty($page_for_posts)){
$page_for_posts= get_option('page_for_posts');
$page_template= get_post_meta(get_option('page_for_posts'),'_wp_page_template',true);
if($page_template!='default' && !empty($page_template)){
include (TEMPLATEPATH . '/'.$page_template);
exit;
}}
 }
// add our function to template_redirect hook
add_action('template_redirect', 'imicBlogTemplateRedirect');
}
/** -------------------------------------------------------------------------------------
 * 600x400 image for Thumbnail enable
 * 600x1000 image for Large image
 * @since NativeChurch 1.7
 ----------------------------------------------------------------------------------- */
add_image_size('600x400',600,400,true);
add_image_size('1000x800',1000,800,true);
/** -------------------------------------------------------------------------------------
 * Thumb And Large Size if Thumbnail enable
 * @since NativeChurch 1.7
 ----------------------------------------------------------------------------------- */
if(!function_exists('imicGetThumbAndLargeSize')){
function imicGetThumbAndLargeSize(){
global $imic_options;
if(isset($imic_options['switch-thumbnail'])&&($imic_options['switch-thumbnail']==1)){
$size_thumb ='600x400';
$size_large ='1000x800';
}else{
$size_thumb=$size_large='full';
}
return array($size_thumb,$size_large);
}}
/*AJAX LOGIN FORM FUNCTION
  ================================================================*/
if(!function_exists('ajax_login_init')) {
function ajax_login_init(){
    wp_register_script('ajax-login-script', get_template_directory_uri() . '/js/ajax-login-script.js', array('jquery') ); 
    wp_enqueue_script('ajax-login-script');
    wp_localize_script( 'ajax-login-script', 'ajax_login_object', array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'loadingmessage' => __('Sending user info, please wait...','framework')
    ));
    add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
}
if (!is_user_logged_in()) {
    add_action('init', 'ajax_login_init');
} }
if(!function_exists('ajax_login')) {
function ajax_login(){
    check_ajax_referer( 'ajax-login-nonce', 'security' );
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
	if($_POST['rememberme']=='true') {
    $info['remember'] = true; }
	else{
	$info['remember'] = false; }
    $user_signon = wp_signon( $info, false );
    if ( is_wp_error($user_signon) ){
        echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.','framework')));
    } else {
        echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...','framework')));
    }
    die();
} }
//Add role for event registrants
function nativechurch_add_registrant_role()
{
    add_role( 'registrant', 'Event Registrant', array( 'read' => false, 'level_0' => true ) );
}
add_action('init', 'nativechurch_add_registrant_role');
/* Agent Register Funtion
  ================================================== */
function imic_agent_register() {
	if(!$_POST) exit;
	// Email address verification, do not edit.
	function isEmail($email) {
		return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
	}
	
	if (!defined("PHP_EOL")) define("PHP_EOL", "\r\n");
	
	$username     = $_POST['username'];
	$email    = $_POST['email'];
	$pwd1  = $_POST['pwd1'];
	$pwd2 = $_POST['pwd2'];
	
	if(trim($username) == '') {
		echo '<div class="alert alert-error">You must enter your username.</div>';
		exit();
	} else if(trim($email) == '') {
		echo '<div class="alert alert-error">You must enter email address.</div>';
		exit();
	} else if(!isEmail($email)) {
		echo '<div class="alert alert-error">You must enter a valid email address.</div>';
		exit();
	}else if(trim($pwd1) == '') {
		echo '<div class="alert alert-error">You must enter password.</div>';
		exit();
	}else if(trim($pwd2) == '') {
		echo '<div class="alert alert-error">You must enter repeat password.</div>';
		exit();
	}else if(trim($pwd1) != trim($pwd2)) {
		echo '<div class="alert alert-error">You must enter a same password.</div>';
		exit();
	}
	
	
	$err = '';
	$success = '';
	
	global $wpdb, $PasswordHash, $current_user, $user_ID;
	
	if (isset($_POST['task']) && $_POST['task'] == 'register') {
		$username = esc_sql(trim($_POST['username']));
		$pwd1 = esc_sql(trim($_POST['pwd1']));
		$pwd2 = esc_sql(trim($_POST['pwd2']));
		$email = esc_sql(trim($_POST['email']));
	   
		if ($email == "" || $pwd1 == "" || $pwd2 == "" || $username == "") {
			$err = 'Please don\'t leave the required fields.';
		} else if ($pwd1 <> $pwd2) {
			$err = 'Password do not match.';
		} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$err = 'Invalid email address.';
		} else if (email_exists($email)) {
			$err = 'Email already exist.';
		} else {
	
			$user_id = wp_insert_user(
							array(
								'user_pass' => apply_filters('pre_user_user_pass', $pwd1), 
								'user_login' => apply_filters('pre_user_user_login', $username), 
								'user_email' => apply_filters('pre_user_user_email', $email), 
								'role' => 'registrant')
							);
							
			if (is_wp_error($user_id)) {
				$err = 'Error on user creation.';
			} else {
				do_action('user_register', $user_id);
				$success = 'You\'re successfully register';
                                $info_register = array();
                                $info_register['user_login'] = $username;
                                $info_register['user_password'] = $pwd1;
                                wp_signon( $info_register, false );
                               }
		}
	}
	
	if (!empty($err)) :
		echo '<div class="alert alert-error">' . $err . '</div>';
	endif;
	
	if (!empty($success)) :
		echo '<div class="alert alert-success">' . $success . '</div>';
	endif;
    die();
}
add_action('wp_ajax_nopriv_imic_agent_register', 'imic_agent_register');
add_action('wp_ajax_imic_agent_register', 'imic_agent_register');
/**
 * Disable admin bar on the frontend of your website
 * for subscribers.
 */
if(!function_exists('imic_disable_admin_bar')){
function imic_disable_admin_bar() { 
	if( ! current_user_can('edit_posts') )
        add_filter('show_admin_bar', '__return_false');	
}
add_action( 'after_setup_theme', 'imic_disable_admin_bar' );
}
 /**
 * Redirect back to homepage and not allow access to 
 * WP admin for Subscribers.
 */
if(!function_exists('imic_redirect_admin')){
function imic_redirect_admin(){
    $user = wp_get_current_user();
    if ( in_array( 'registrant', (array) $user->roles ) ) {
        wp_redirect( site_url() );
        exit;
    }
}
add_action( 'admin_init', 'imic_redirect_admin' );   
}
function imic_regenerate_calender_index($index,$google_event_array)
{
    $index = ($index+1);
    if(array_key_exists($index,$google_event_array))
    {
        return imic_regenerate_calender_index($index,$google_event_array);
    }
    return $index;
}
function getGoogleEvent($month_last='') {
    global $imic_options;
    $google_event_array=array();
   if(isset($imic_options['google_feed_id'])&&!empty($imic_options['google_feed_id'])){
   $items_to_show=999; ///999 = unlimited
   $items_shown=0;
    if(empty($month_last)){
        $futureevents ='true';
    }
    else{
      $futureevents ='false';
    }
		$google_event_address = '';
$currentEventTime = (isset($_POST['date'])?date(DATE_ATOM, strtotime($_POST['date'])):date(DateTime::ATOM));
require_once('google_api/google_api.php');
$calender_id = $imic_options['google_feed_id'];
$api_key = $imic_options['google_feed_key'];
$items = GetCalendarEvents($calender_id,$api_key,$currentEventTime,$items_to_show);
 foreach ($items as $entry)
 {
    $title=$entry['title'];
    $link=$entry['url'];
    $event_start_time = $entry['start_time'];
    $google_event_end_time   = $entry['end_time'];
    $index = strtotime($event_start_time);
    if(array_key_exists($index,$google_event_array))
    {
       $index = imic_regenerate_calender_index($index,$google_event_array);
    }
    if(empty($month_last))
    {
       $google_event_array[$index] = $title.'!'.$link.'!'.$google_event_end_time.'!'.$google_event_address;
       $items_shown++;
    }
    else
    {
        if($month_last=='goingEvent')
        {
              $today = date('Y-m-d');
              $currentGoingTime=date('U');
           
             if((date('Y-m-d',$event_start_time) == $today)&&
             ($event_start_time<=$currentGoingTime)&&($currentGoingTime<=strtotime($google_event_end_time))
             &&!empty($event_start_time))
             {
                $google_event_array[$index] = $title.'!'.$link.'!'.$google_event_end_time.'!'.$google_event_address;
               $items_shown++;
             }
        }
        elseif($month_last=='past')
        {
           $today = date('Y-m-d');
           if((date('Y-m-d',$event_start_time) <$today))
           {
             $google_event_array[$index] = $title.'!'.$link.'!'.$google_event_end_time.'!'.$google_event_address;
            $items_shown++;
           }
        }
         elseif(date('y m',$index)==date('y m',$month_last))
         {
           $google_event_array[$index] = $title.'!'.$link.'!'.$google_event_end_time.'!'.$google_event_address;
           $items_shown++;
        }
    }
  }
 }
 return $google_event_array;
} 
function imicRecurrenceIcon($value){
      $frequency = get_post_meta($value,'imic_event_frequency', true);
    switch($frequency) {
			case 1:
			$recur =__('Every Day','framework');
			break;
			case 2:
			$recur = __('Every Second Day','framework');
			break;
			case 3:
			$recur = __('Every Third Day','framework');
			break;
			case 4:
			$recur = __('Every Fourth Day','framework');
			break;
			case 5:
			$recur = __('Every Fifth Day','framework');
			break;
			case 6:
			$recur = __('Every Sixth Day','framework');;
			break;
			case 7:
			$recur = __('Every Week','framework');
			break;
			case 30:
			$recur = __('Every Month','framework');
			break;
                        default:
                        $recur='';  
                         break;
		}
                $frequency_count = get_post_meta($value, 'imic_event_frequency_count', true);
                $icon = ' <a data-placement="bottom" data-toggle="tooltip-live" data-original-title="Recurring '.$recur.', for '.$frequency_count.' Times" rel="tooltip" class="recurring-info-icon"><i class="fa fa-refresh"></i></a>';
				  $recurrence = get_post_meta($value,'imic_event_frequency',true);
				  if($recurrence>0&&$recur!='') { $icon = $icon; } else { $icon = ''; }
                                  return $icon;
}
 /**
 *  SIDEBAR POSITION
 */
if(!function_exists('imic_sidebar_position_module')){
	function imic_sidebar_position_module(){
		$sidebar_position = get_post_meta(get_the_ID(),'imic_select_sidebar_position',true);
		if(is_home())
		{
			$id = get_option('page_for_posts');
			$sidebar_position = get_post_meta($id,'imic_select_sidebar_position',true);
		}
		if($sidebar_position == 2){
		echo ' <style type="text/css">#content-col, #sidebar-col{float:right!important;}</style>';	
		} elseif($sidebar_position == 1){
		echo ' <style type="text/css">#content-col, #sidebar-col{float:left!important;}</style>';	
		}
	}
}

 /**
 *  SHARE BUTTONS
 */
if(!function_exists('imic_share_buttons')){
function imic_share_buttons(){
$posttitle = get_the_title();
$postpermalink = get_permalink();
$postexcerpt = get_the_excerpt();
global $imic_options;
$facebook_share_alt = $imic_options['facebook_share_alt'];
$twitter_share_alt = $imic_options['twitter_share_alt'];
$google_share_alt = $imic_options['google_share_alt'];
$tumblr_share_alt = $imic_options['tumblr_share_alt'];
$pinterest_share_alt = $imic_options['pinterest_share_alt'];
$reddit_share_alt = $imic_options['reddit_share_alt'];
$linkedin_share_alt = $imic_options['linkedin_share_alt'];
$email_share_alt = $imic_options['email_share_alt'];
$vk_share_alt = $imic_options['vk_share_alt'];
			
			
            echo '<div class="share-bar">';
			if($imic_options['sharing_style'] == '0'){
				if($imic_options['sharing_color'] == '0'){
            		echo '<ul class="share-buttons">';
				}elseif($imic_options['sharing_color'] == '1'){
            		echo '<ul class="share-buttons share-buttons-tc">';
				}elseif($imic_options['sharing_color'] == '2'){
            		echo '<ul class="share-buttons share-buttons-gs">';
				}
			} elseif($imic_options['sharing_style'] == '1'){
				if($imic_options['sharing_color'] == '0'){
            		echo '<ul class="share-buttons share-buttons-squared">';
				}elseif($imic_options['sharing_color'] == '1'){
            		echo '<ul class="share-buttons share-buttons-tc share-buttons-squared">';
				}elseif($imic_options['sharing_color'] == '2'){
            		echo '<ul class="share-buttons share-buttons-gs share-buttons-squared">';
				}
			};
					if($imic_options['share_icon']['1'] == '1'){
                   		echo '<li class="facebook-share"><a href="https://www.facebook.com/sharer/sharer.php?u=' . $postpermalink . '&amp;t=' . esc_attr($posttitle) . '" target="_blank" title="' . esc_attr($facebook_share_alt) . '"><i class="fa fa-facebook"></i></a></li>';
					}
					if($imic_options['share_icon']['2'] == '1'){
                     	echo '<li class="twitter-share"><a href="https://twitter.com/intent/tweet?source=' . $postpermalink . '&amp;text=' . esc_attr($posttitle) . ':' . $postpermalink . '" target="_blank" title="' . esc_attr($twitter_share_alt) . '"><i class="fa fa-twitter"></i></a></li>';
					}
					if($imic_options['share_icon']['3'] == '1'){
                    echo '<li class="google-share"><a href="https://plus.google.com/share?url=' . $postpermalink . '" target="_blank" title="' . esc_attr($google_share_alt) . '"><i class="fa fa-google-plus"></i></a></li>';
					}
					if($imic_options['share_icon']['4'] == '1'){
                    	echo '<li class="tumblr-share"><a href="http://www.tumblr.com/share?v=3&amp;u=' . $postpermalink . '&amp;t=' . esc_attr($posttitle) . '&amp;s=" target="_blank" title="' . esc_attr($tumblr_share_alt) . '"><i class="fa fa-tumblr"></i></a></li>';
					}
					if($imic_options['share_icon']['5'] == '1'){
                    	echo '<li class="pinterest-share"><a href="http://pinterest.com/pin/create/button/?url=' . $postpermalink . '&amp;description=' . esc_attr($postexcerpt) . '" target="_blank" title="' . esc_attr($pinterest_share_alt) . '"><i class="fa fa-pinterest"></i></a></li>';
					}
					if($imic_options['share_icon']['6'] == '1'){
                    	echo '<li class="reddit-share"><a href="http://www.reddit.com/submit?url=' . $postpermalink . '&amp;title=' . esc_attr($posttitle) . '" target="_blank" title="' . esc_attr($reddit_share_alt) . '"><i class="fa fa-reddit"></i></a></li>';
					}
					if($imic_options['share_icon']['7'] == '1'){
                    	echo '<li class="linkedin-share"><a href="http://www.linkedin.com/shareArticle?mini=true&url=' . $postpermalink . '&amp;title=' . esc_attr($posttitle) . '&amp;summary=' . esc_attr($postexcerpt) . '&amp;source=' . $postpermalink . '" target="_blank" title="' . esc_attr($linkedin_share_alt) . '"><i class="fa fa-linkedin"></i></a></li>';
					}
					if($imic_options['share_icon']['8'] == '1'){
                    	echo '<li class="email-share"><a href="mailto:?subject=' . esc_attr($posttitle) . '&amp;body=' . esc_attr($postexcerpt) . ':' . $postpermalink . '" target="_blank" title="' . esc_attr($email_share_alt) . '"><i class="fa fa-envelope"></i></a></li>';
					}
					if((isset($imic_options['share_icon']['9']))&&($imic_options['share_icon']['9'] == '1')){
                    	echo '<li class="vk-share"><a href="http://vk.com/share.php?url=' . $postpermalink . '" target="_blank" title="' . esc_attr($vk_share_alt) . '"><i class="fa fa-vk"></i></a></li>';
					}
                echo '</ul>
            </div>';
	}
}
/* EVENT GRID FUNCTION
  ================================================== */
function imic_event_grid() {
	$EventTerm = '';
	$offset = get_option('timezone_string');
		if($offset=='') { $offset = "Australia/Melbourne"; }
	date_default_timezone_set($offset);
    echo '<div class="listing events-listing">
	<header class="listing-header">
		<div class="row">
			<div class="col-md-6 col-sm-6">
				<h3>' . __('All events', 'framework') . '</h3>
		  </div>
		  <div class="listing-header-sub col-md-6 col-sm-6">';
    $currentEventTime = $_POST['date'];
	$EventTerm = $_POST['term'];
    $prev_month = date('Y-m', strtotime('-1 month', strtotime($currentEventTime)));
    $next_month = date('Y-m', strtotime('+1 month', strtotime($currentEventTime)));
    echo '<h5>' . date_i18n('F', strtotime($currentEventTime)) . '</h5>
				<nav class="next-prev-nav">
					<a href="javascript:" class="upcomingEvents" rel="'.$EventTerm.'" id="' . $prev_month . '"><i class="fa fa-angle-left"></i></a>
					<a href="javascript:" class="upcomingEvents" rel="'.$EventTerm.'" id="' . $next_month . '"><i class="fa fa-angle-right"></i></a>
				</nav>
		  </div>
	  </div>
	</header>
	<section class="listing-cont">
	  <ul>';
    $today = date('Y-m');
	$curr_month = date('Y-m-t', strtotime('-1 month', strtotime($currentEventTime)));
    $currentTime = date(get_option('time_format'));
    $sp = imic_recur_events('future','nos',$EventTerm,$currentEventTime);
	$this_month_last = strtotime(date('Y-m-t 23:59', strtotime($currentEventTime)));
	$google_events = getGoogleEvent($this_month_last);
	if(!empty($google_events)) 
       $new_events = $google_events+$sp;
	   else  $new_events = $sp;
	   
        ksort($new_events);
        if(!empty($new_events)){
	foreach($new_events as $key =>$value) {
          if(preg_match('/^[0-9]+$/',$value)){
                $frequency = get_post_meta($value, 'imic_event_frequency', true);
       	        $frequency_count = get_post_meta($value, 'imic_event_frequency_count', true);
				$satime = get_post_meta($value,'imic_event_start_tm',true);
				$satime = strtotime($satime);
                                $date_converted=date('Y-m-d',$key );
                                $custom_event_url =  imic_query_arg($date_converted,$value);
                               $event_title= get_the_title($value);
							   /* event time date formate */
							  $eventStartTime =  strtotime(get_post_meta($value, 'imic_event_start_tm', true));
							  $eventStartDate =  strtotime(get_post_meta($value, 'imic_event_start_dt', true));
							  $eventEndTime   =  strtotime(get_post_meta($value, 'imic_event_end_tm', true));
							  $eventEndDate   =  strtotime(get_post_meta($value, 'imic_event_end_dt', true));
							  $evstendtime = $eventStartTime.'|'.$eventEndTime;
							  $evstenddate = $eventStartDate.'|'.$eventEndDate;
							  $event_dt_out = imic_get_event_timeformate( $evstendtime,$evstenddate,$value,$key);
			                  $event_dt_out = explode('BR',$event_dt_out);
							  /* event time date formate end */
                                }
                                else{
                                   $google_data =(explode('!',$value)); 
                                   $event_title=$google_data[0];
                                   $custom_event_url=$google_data[1];
								   $options = get_option('imic_options');
                                   $satime =$key;
								   /* event time date formate */
								  $event_dt_out = imic_get_event_timeformate($key.'|'.strtotime($google_data[2]),
								  $key.'|'.$key,$value,$key);
						          $event_dt_out = explode('BR',$event_dt_out);
							 /* event time date formate end */
                                }
							  
                                echo '<li class="item event-item">	
				  <div class="event-date"> <span class="date">' . date_i18n('d', $key) . '</span> <span class="month">' .imic_global_month_name($key). '</span> </div>
				  <div class="event-detail">
                                      <h4><a href="'.$custom_event_url.'">' . $event_title .'</a>'.imicRecurrenceIcon($value).'</h4>'; 
            
                echo '<span class="event-dayntime meta-data">' . $event_dt_out[1].',&nbsp;&nbsp;'.$event_dt_out[0] . '</span> </div>
				  <div class="to-event-url">
					<div><a href="' .$custom_event_url.'" class="btn btn-default btn-sm">' . __('Details', 'framework') . '</a></div>
				  </div>
				</li>';
	}
        }
        else {
        echo '<li class="item event-item">	
			  <div class="event-detail">
				<h4>' . __('Sorry, there are no events for this month.', 'framework') . '</h4>
			  </div>
			</li>';
    }
    echo '</ul>
	</section>
  </div>';
    die();
}
add_action('wp_ajax_nopriv_imic_event_grid', 'imic_event_grid');
add_action('wp_ajax_imic_event_grid', 'imic_event_grid');
//Event Global Function
if (!function_exists('imic_recur_events')) {
function imic_recur_events($status,$featured="nos",$term='',$month='', $show_multiples=true) 
{
  ##################### getset options and defaut value  ###############
  $featured                = ($featured=="yes")?"no":"nos";
  $today                   = date('Y-m-d');
  $imic_options            = get_option('imic_options');
  $offset                  = get_option('timezone_string');
  $offset                  = ($offset == '')?"Australia/Melbourne":$offset;
  $event_add               = array();
  $sinc                    = 1;
  $event_show_until        = (isset($imic_options['countdown_timer']))?$imic_options['countdown_timer']:'0';
  $meta_query              = '';
  date_default_timezone_set($offset);
  #######################################################################
 if($month != "") 
 {
	$stop_date = $month;
	$curr_month = date('Y-m-t 23:59', strtotime('-1 month', strtotime($stop_date)));
	$current_end_date = date('Y-m-d H:i:s', strtotime($stop_date . ' + 1 day'));
	$previous_month_end = strtotime(date('Y-m-d 00:01', strtotime($stop_date)));
	$next_month_start = strtotime(date('Y-m-d 00:01', strtotime('+1 month', strtotime($stop_date))));

	   $meta_query = array(
						  'relation' => 'AND',
						   array(
								 'key' => 'imic_event_frequency_end',
						       'value' => date('Y-m-t 23:59', strtotime($stop_date)),
								 'compare' => '>'
								 ),
						   array(
								 'key' => 'imic_event_start_dt',
						       'value' => $curr_month,
								 'compare' => '<'
								 ),
						);
 }
 else 
 {
	if($status=='future') 
	{
	  $meta_query = array(
					   array(
							  'key' => 'imic_event_frequency_end',
							  'value' => $today,
							  'compare' => '>='
							  ),
						);
	 }
	else 
	{

		$meta_query = array(
						array(
							'key' => 'imic_event_start_dt',
							'value' => $today,
							'compare' => '<'
							),
					);
	}
 }
 
 $post_query = array(
					'post_type' => 'event',
					'event-category' => $term,
					'meta_key' => 'imic_event_start_dt',
					'meta_query' => $meta_query,
					'orderby' => 'meta_value',
					'order' => 'ASC',
					'posts_per_page' => -1
					);
#exicute query
query_posts($post_query);
			 
if (have_posts()):
    while (have_posts()):the_post();
	    ###############################################################################
        $frequency            = get_post_meta(get_the_ID(), 'imic_event_frequency', true);
        $frequency_count      = get_post_meta(get_the_ID(), 'imic_event_frequency_count', true);
		$frequency_month_day  = get_post_meta(get_the_ID(),'imic_event_day_month',true);
		$frequency_week_day   = get_post_meta(get_the_ID(),'imic_event_week_day',true);
		$multiple_dates       = get_post_meta(get_the_ID(),'imic_event_recurring_dt',true);
		$seconds              = $frequency * 86400;
		$fr_repeat            = 0;
		###############################################################################
        if ($frequency != '0' && $frequency!='32') 
		{
            $frequency_count = $frequency_count;
        } 
		elseif($frequency=='32') 
		{
			$frequency_count = count($multiple_dates);
		}
		else 
		{ 
		   $frequency_count = 0;
		}
		
		$event_id = get_the_ID();

        while ($fr_repeat <= $frequency_count) 
		{
		    if ($show_multiples == false && $fr_repeat > 0)
		        break;
		    
            $event_start_dt = $eventDate = get_post_meta(get_the_ID(), 'imic_event_start_dt', true);
			$event_start_tm = $MetaStartTime  = get_post_meta(get_the_ID(),'imic_event_start_tm',true);
			$eventEndDate   = get_post_meta(get_the_ID(),'imic_event_end_dt',true);
			$MetaEndTime    = get_post_meta(get_the_ID(),'imic_event_end_tm',true);
			$inc = $sinc = '';
			$eventEndDate = $event_actual_en_date = strtotime($eventEndDate.' '.$MetaEndTime);
            $eventDate = $event_actual_st_date = strtotime($eventDate.' '.$MetaStartTime);
			$diff_start = date('Y-m-d',$eventDate);
			$diff_end = date('Y-m-d', $eventEndDate);
			$days_extra = imic_dateDiff($diff_start, $diff_end);
			$dt_tm = strtotime($event_start_dt.' '.$event_start_tm);
			
			if($days_extra>0) 
			{
				$start_day = 0;
				while($start_day<=$days_extra) 
				{
					$diff_sec = 86400*$start_day;
					$new_date = $eventDate+$diff_sec;
					$str_only_date = date('Y-m-d',$new_date);
					$en_only_time = date("G:i", $eventEndDate);
					$start_dt_tm = strtotime($str_only_date.' '.$en_only_time);
					if($start_dt_tm > date('U')) 
					{
						$eventDate = $new_date;
						break;
					}
					$start_day++;
				}
			}
			if($days_extra<1) 
				{
					if(($frequency!='35')&&($frequency!='32')) 
					{
						if($frequency==30)
						 {
						   $eventDate = strtotime("+".$fr_repeat." month", $eventDate);
						  $eventEndDate = strtotime("+".$fr_repeat." month", $eventEndDate);
						}
						else 
						{
							$new_date = $seconds * $fr_repeat;
							$eventDate = $eventDate + $new_date;
							$eventEndDate = $eventEndDate + $new_date;
						}
					}
					elseif($frequency=='32') 
					{
						if($fr_repeat!=$frequency_count) 
						{ 
							$eventDate = $multiple_dates[$fr_repeat];
							$eventDate = strtotime($eventDate);
					    }
					}
				 else 
				 {
					$eventTime = date('G:i',$eventDate);
					$eventDate = strtotime( date('Y-m-01',$eventDate) );
					
					if($fr_repeat==0) { 
					    $fr_repeat = $fr_repeat+1; 
					}
					
					$eventDate = strtotime("+".$fr_repeat." month", $eventDate);
					$next_month = date('F',$eventDate);
					$next_event_year = date('Y',$eventDate);
					$freq_strtotime = $frequency_month_day.' '.$frequency_week_day.' of '.$next_month.' '.$next_event_year;
					$eventDate = date('Y-m-d '.$eventTime, strtotime($freq_strtotime));
					$eventDate = strtotime($eventDate);
				}
			 }
			 	if($MetaStartTime!='')
            	{
				if($event_show_until=='1')
				{
					$en_tm = date("G:i",$event_actual_en_date);
				}
				else
				{
					$en_tm = date("G:i",$event_actual_st_date);
				}
				}
				else
            	{
                	$en_tm = "23:59";
            	}
			$st_dt = date('Y-m-d',$eventDate);
			$dt_tm = strtotime($st_dt.' '.$en_tm);
			if($month != '') 
			{
				if(($dt_tm > $previous_month_end) && ($dt_tm < $next_month_start)){
                    $event_add[$dt_tm + intval($sinc) + intval($inc)] = get_the_ID();
                       $sinc++;
                }
			}
			else 
			{
				if($status=="future")
				 {
					if ($dt_tm >= date('U')) 
					{ echo $inc;
						$event_add[$dt_tm + intval($sinc) + intval($inc)] = get_the_ID();
						$sinc++;
					} 
				}
				else 
				{
					if ($dt_tm <= date('U')) 
					{
						$event_add[$dt_tm + intval($sinc) + intval($inc)] = get_the_ID();
						$sinc++;
					} 	
				}
		  } 
		  if($days_extra<1) { 
		      $fr_repeat++; 
		  } 
		  else { 
		      $fr_repeat = 1000000; 
		  }
        } 
    endwhile; 
endif;
	 //global $wp_query;
	 //print_r($wp_query->request);

   wp_reset_query(); 
   return $event_add;
 }
}

//Uncomment below code to use bbpress on localhost. Change the localhost URL as per your need
	/* add_filter( 'bbp_verify_nonce_request_url', 'my_bbp_verify_nonce_request_url', 999, 1 );
	function my_bbp_verify_nonce_request_url( $requested_url )
	{
		return 'http://localhost:8888' . $_SERVER['REQUEST_URI'];
	} */
	

/* GET TEMPLATE URL
  ================================================*/
if(!function_exists('imic_get_template_url')) {
function imic_get_template_url($TEMPLATE_NAME){
 $url;
$pages = query_posts(array(
    'post_type' =>'page',
    'meta_key'  =>'_wp_page_template',
    'meta_value'=> $TEMPLATE_NAME
));
$url = null;
if(isset($pages[0])) {
    $url = get_page_link($pages[0]->ID);
}
wp_reset_query();
return $url;
}
}

/* GET EVENT TIME FORMATE
  ================================================*/
  /*
    @params $time = start time + end time
	$date = start date + end date
	$post_id = post id
	@return time + date
  */ 
if(!function_exists('imic_get_event_timeformate')) {
function imic_get_event_timeformate($time,$date,$post_id = null,$key = null, $single = false){
#check all day event
$time_offset = get_option('gmt_offset');
$allday    = get_post_meta($post_id, 'imic_event_all_day', true);
$time = explode('|',$time);
$date = explode('|',$date);
//get event time and date option  format	
$options = get_option('imic_options');
$event_tm_opt = isset($options['event_tm_opt'])?$options['event_tm_opt']:'0';
$event_dt_opt = isset($options['event_dt_opt'])?$options['event_dt_opt']:'0';
//get time format
$time_format = get_option('time_format');
//get date format
$date_format = get_option('date_format');
$time_opt = $date_opt = '';

$time_from = $time[0];


if (strpos($time_from, '-') === false  && strpos($time_from, '+') === false) {
  $time_from = $time_from . $time_offset;
}

$event_dt_opt = ($single==true)?'2':$event_dt_opt;
	switch($event_tm_opt)
	{
		 case '0':
		    if(!empty($time[0]) && $time[0] != strtotime(date('23:59')))
			{
		      $time_opt = date($time_format, $time[0]);
			}
			else
			{
				if($allday || empty($time[0])|| $time[0] == strtotime(date('23:59')))
				{
					$time_opt = __('All Day','framework');
				}
			}
		 break;
		 case '1':
			 if(!empty($time[1]) && $time[1] != strtotime(date('23:59')))
			 {
			  $time_opt = date($time_format, $time[1]);
			 }
			 else
			 {
				if($allday || empty($time[1]) || $time[1] == strtotime(date('23:59')))
				{
					$time_opt = __('All Day','framework');
				}
			}
		 break;
		 case '2':

		 if((!empty($time_from) && !empty($time[1])) &&
           ($time_from != strtotime(date('23:59')) || $time[1] != strtotime(date('23:59'))))
			{
				 //$time_opt_0 = date_i18n($time_format, $time_from);
				 $time_opt_0 = date($time_format, $time_from);
				 //$time_opt_1 = date_i18n($time_format, $time[1]);
				 $time_opt_1 = date($time_format, $time[1]);
				 if($time_from != $time[1])
				 {
				   $time_opt =  $time_opt_0.' - '.$time_opt_1;
				 }
				 else
				 {
					 $time_opt =  $time_opt_0;
				 }
		   }
		    else
			{
				if($allday || empty($time_from)||$time_from == strtotime(date('23:59'))||$time[1] == strtotime(date('23:59')))
				{
					$time_opt = __('All Day','framework');
				}
			}

		 break;
		 default : 
		  if(!empty($time[0]))
		  {
		    $time_opt = date($time_format, $time[0]);
		  }
		 break;
	}
	switch($event_dt_opt)
	{
		 case '0':
		  if(!empty($date[0]))
		  {
			 $diff_date = imic_dateDiff($date[0], $date[1]);
			 if($diff_date>0)
			 {
		       $date_opt = date_i18n($date_format, $date[0]);
		       $date_opt = '<strong>' . date_i18n('l', $date[0]) . '</strong> | ' . $date_opt;
			 }
			 else
			 {
				 $date_opt = date_i18n($date_format, $key);
		         $date_opt = '<strong>' . date_i18n('l', $key) . '</strong> | ' . $date_opt;
			 }
		  }
		 break;
		 case '1':
		  if(!empty($date[1]))
		  {
			$diff_date = imic_dateDiff($date[0], $date[1]);
			if($diff_date>0)
			 {
		       $date_opt = date_i18n($date_format, $date[1]);
		       $date_opt = '<strong>' . date_i18n('l', $date[1]) . '</strong> | ' . $date_opt;
			 }
			 else
			 {
				 $date_opt = date_i18n($date_format, $key);
		         $date_opt = '<strong>' . date_i18n('l', $key) . '</strong> | ' . $date_opt;
			 }
		  }
		 break;
		 case '2':
		  if(!empty($date[0]) && !empty($date[1]))
		  {
			  $date_opt_0 = date_i18n($date_format, $date[0]);
			  $date_opt_0 = '<strong>' . date_i18n('l', $date[0]) . '</strong> | ' . $date_opt_0;
			  if($date[0] !== $date[1])
			  {
				$date_opt_1 = date_i18n($date_format, $date[1]);
				$date_opt_1 = '<strong>' . date_i18n('l', $date[1]) . '</strong> | ' . $date_opt_1;
				$date_opt =  $date_opt_0.' '.__('to','framework').' '.$date_opt_1;
			  }
			  else
			  {
				  $date_opt = date_i18n($date_format, $key);
			  }
		  }
		 break;
		 default :
		 if(!empty($date[0]))
		  {
			 $diff_date = imic_dateDiff($date[0], $date[1]);
			 if($diff_date>0)
			 {
		       $date_opt = date_i18n($date_format, $date[0]);
		       $date_opt = '<strong>' . date_i18n('l', $date[0]) . '</strong> | ' . $date_opt;
			 }
			 else
			 {
				 $date_opt = date_i18n($date_format, $key);
		         $date_opt = '<strong>' . date_i18n('l', $key) . '</strong> | ' . $date_opt;
			 }
		  }
		 break;
	}


	return  $time_opt .'BR'.$date_opt;
}
}

/* GET TERM CATEGORY 
  ================================================*/
  /*
    @params $post_id = post id of post
	$texonomy = texonomy name of page
	$category = category of texonomy @default = event-category
	@return all texonomy categories slugs
  */ 
if(!function_exists('imic_get_term_category')) {
function imic_get_term_category($post_id,$texonomy,$category ='event-category',$boolean = true ){
	
	$event_category = get_post_meta($post_id,$texonomy,$boolean);
	if($event_category!='')
	{
		$event_category = explode(',',$event_category);
		$event_category_rel = '';
		foreach($event_category as $event_cat_id)
		{
			$event_categories= get_term_by('id',$event_cat_id,$category);
			$event_category_rel.= $event_categories->slug.','; 
		}
		$event_category = rtrim($event_category_rel,',');
	}
	return  $event_category;
	
}
}
////////////////////////////////////////////////////////////////
///////////  CUSTOM ADMIN EVENT LIST DIAPLSY ///////////////////
////////////////////////////////////////////////////////////////
class REARRANGE_ADMIN_EVENT_LIST_COLS
{
	/**
	 * Constructor
	 */
	public function __construct() {
		#WP List table columns. Defined here so they are always available for events such as inline editing.
		add_filter('manage_event_posts_columns', array( $this, 'event_columns' ) );
		add_filter('manage_edit-event_sortable_columns', array( $this, 'sortable_event_column' ));
		add_action('pre_get_posts', array( $this, 'event_sort_by_start_date' ));
		add_action('manage_event_posts_custom_column', array( $this, 'render_event_columns' ), 2 );
	}
	/**
	 * Define custom columns for events
	 * @param  array $existing_columns
	 * @return array
	 */
	public function event_columns( $existing_columns ) {

		if ( empty( $existing_columns ) && ! is_array( $existing_columns ) ) {
			$existing_columns = array();
		}
		 unset( 
				$existing_columns['title'],
				$existing_columns['date'],
				$existing_columns['author'],
				$existing_columns['taxonomy-event-category']
			  );
		$columns                                   = array();
		$columns['title']                          = __('Name', 'framework' );
		$columns['author']                         = __('Owner', 'framework' );
		$columns['taxonomy-event-category']        = __('Categories', 'framework' );
		#$columns['contact']                        = __('Contact Number', 'framework' );
		$columns['address']                        = __('Address', 'framework' );
        $columns['attendees']                      = __('Attendees', 'framework');
        $columns['staff']                          = __('Staff', 'framework');
		$columns['recurring']                      = __('Recurring', 'framework');
		$columns['event_date']                     = __('Date', 'framework');
		return array_merge( $existing_columns,$columns);
	}
	/**
	 * Ouput custom columns for events
	 * @param  string $column
	 */
	public function render_event_columns( $column ) {
		global $post;
		switch ( $column ) {
			case 'contact' :
				echo get_post_meta($post->ID,'imic_event_contact',true);
				break;
			case 'address' :
				echo get_post_meta($post->ID,'imic_event_address',true);
				break;
				case 'event_date':
                $sdate = get_post_meta($post->ID, 'imic_event_start_dt', true);
                $stime = get_post_meta($post->ID, 'imic_event_start_tm', true);
				$edate = get_post_meta($post->ID, 'imic_event_end_dt', true);
                $etime = get_post_meta($post->ID, 'imic_event_end_tm', true);
                /*echo '<abbr title="'.$sdate .' '.$stime.'">'.__('Start:', 'framework').'&nbsp;'.$sdate.
				     '</abbr><br title="'.$edate.' '.$etime.'">'.__('End:', 'framework').'&nbsp;'.$edate;*/
			  echo '<abbr title="'.$sdate .' '.$stime.'">'.$sdate.
				    '</abbr><br title="'.$edate.' '.$etime.'">'.$edate;
                break;
            case 'attendees':
                $attendees = get_post_meta($post->ID, 'imic_event_attendees', true);
                echo $attendees;
                break;
            case 'staff':
                $staff = get_post_meta($post->ID, 'imic_event_staff_members', true);
                echo $staff;
                break;
			case 'recurring':
                $frequency = get_post_meta($post->ID, 'imic_event_frequency', true);
				$frequency_count = get_post_meta($post->ID, 'imic_event_frequency_count', true);
				if($frequency==1){ $sent = __('Every Day', 'framework'); }
				elseif($frequency==2){ $sent = __('Every 2nd Day', 'framework'); }
				elseif($frequency==3){ $sent = __('Every 3rd Day', 'framework'); }
				elseif($frequency==4){ $sent = __('Every 4th Day', 'framework'); }
				elseif($frequency==5){ $sent = __('Every 5th Day', 'framework'); }
				elseif($frequency==6){ $sent = __('Every 6th Day', 'framework'); }
				elseif($frequency==6){ $sent = __('Every week', 'framework'); }
				elseif($frequency==30){ $sent = __('Every Month', 'framework'); }
				else{ $sent = ""; }
				if($frequency>0) {
                echo '<abbr title="'.$sent .' '.$sent.'">'.$sent.'</abbr><br>'.$frequency_count.' time';
				}
                break;
			default :
				break;
		}
	}
    /* make soratable by event start date asc/desc on click this */
    public function sortable_event_column($columns) {
        $columns['event_date'] = 'event';
        return $columns;
    }
	/* sort post event list in admin section by post meta event start date */ 
	public function event_sort_by_start_date( $query )
	{
		global $pagenow;
		if('edit.php' == $pagenow && isset( $_GET['orderby']) && isset( $_GET['order'] )
			&& isset( $_GET['post_type'] ) && $_GET['post_type'] == 'event' && $_GET['order'] == 'asc' )
			{
				$query->set( 'meta_key', 'imic_event_start_dt' );
				$query->set( 'orderby', 'meta_value' );
				$query->set( 'order', 'ASC' );
		   }
		  elseif('edit.php' == $pagenow && isset( $_GET['orderby']) && isset( $_GET['order'] )
			&& isset( $_GET['post_type'] ) && $_GET['post_type'] == 'event' && $_GET['order'] == 'desc' )
			{
				$query->set( 'meta_key', 'imic_event_start_dt' );
				$query->set( 'orderby', 'meta_value' );
				$query->set( 'order', 'DESC' );
		   }
		   elseif('edit.php' == $pagenow && !isset( $_GET['orderby'] )
			&& isset( $_GET['post_type'] ) && $_GET['post_type'] == 'event' )
			{
				$query->set( 'meta_key', 'imic_event_start_dt' );
				$query->set( 'orderby', 'meta_value' );
				$query->set( 'order', 'DESC' );
		   }
     }
}
/* acivate REARRANGE_ADMIN_EVENT_LIST_COLS if user admin */
if(is_admin())
{
  $REARRANGE_EVENT_LIST = new REARRANGE_ADMIN_EVENT_LIST_COLS();
  unset($REARRANGE_EVENT_LIST);
}
if (!function_exists('imic_recur_events_calendar')) 
{
	function imic_recur_events_calendar($status,$featured="nos",$term='',$month='') 
	{
    	$offset = get_option('timezone_string');
    	if($offset=='') 
		{ 
			$offset = "Australia/Melbourne"; 
		}
    	date_default_timezone_set($offset);
    	global $imic_options;
    	$event_show_until = (isset($imic_options['countdown_timer']))?$imic_options['countdown_timer']:'0';
		$featured = ($featured=="yes")?"no":"nos";
		$today = date('Y-m-d');
		if($month!="") 
		{
    		$stop_date = $month;
    		$curr_month = date('Y-m-t 23:59', strtotime('-1 month', strtotime($stop_date)));
    		$current_end_date = date('Y-m-d H:i:s', strtotime($stop_date . ' + 1 day'));
    		$previous_month_end = strtotime(date('Y-m-d 00:01', strtotime($stop_date)));
    		$next_month_start = strtotime(date('Y-m-d 00:01', strtotime('+1 month', strtotime($stop_date))));
    		query_posts(array('post_type' => 'event','event-category' => $term,'meta_key' => 'imic_event_start_dt','meta_query' => array('relation' => 'AND',array('key' => 'imic_event_frequency_end','value' => $curr_month,'compare' => '>'),array('key' => 'imic_event_start_dt','value' => date('Y-m-t 23:59', strtotime($stop_date)),'compare' => '<')),'orderby' => 'meta_value','order' => 'ASC','posts_per_page' => -1));
		}
		else 
		{
			if($status=='future') 
			{
				query_posts(array('post_type' => 'event', 'event-category'=>$term, 'meta_key' => 'imic_event_start_dt', 'meta_query' => array(array('key' => 'imic_event_frequency_end', 'value' => $today, 'compare' => '>=')), 'orderby' => 'meta_value', 'order' => 'ASC', 'posts_per_page' => -1)); }
			else 
			{
    			query_posts(array('post_type' => 'event', 'event-category'=>$term, 'meta_key' => 'imic_event_start_dt', 'meta_query' => array(array('key' => 'imic_event_start_dt', 'value' => $today, 'compare' => '<')), 'orderby' => 'meta_value', 'order' => 'ASC', 'posts_per_page' => -1));
			} 
		}
      	$event_add = array();
		$sinc = 1;
		if (have_posts()):
    		while (have_posts()):the_post();
        		$frequency = get_post_meta(get_the_ID(), 'imic_event_frequency', true);
        		$frequency_count = get_post_meta(get_the_ID(), 'imic_event_frequency_count', true);
        		$frequency_month_day = get_post_meta(get_the_ID(),'imic_event_day_month',true);
        		$frequency_week_day = get_post_meta(get_the_ID(),'imic_event_week_day',true);
        		$multiple_dates = get_post_meta(get_the_ID(),'imic_event_recurring_dt',true);
        		if ($frequency != '0' && $frequency!='32') 
				{
            		$frequency_count = $frequency_count;
        		}
        		elseif($frequency=='32') 
				{
            		$frequency_count = count($multiple_dates);
        		}
        		else 
				{ 
					$frequency_count = 0; 
				}
        		$seconds = $frequency * 86400;
        		$fr_repeat = 0;
        		while ($fr_repeat <= $frequency_count) 
				{
            		$eventDate = get_post_meta(get_the_ID(), 'imic_event_start_dt', true);
            		$MetaStartTime = get_post_meta(get_the_ID(),'imic_event_start_tm',true);
            		$eventEndDate = get_post_meta(get_the_ID(),'imic_event_end_dt',true);
            		$MetaEndTime = get_post_meta(get_the_ID(),'imic_event_end_tm',true);
            		$inc = '';
            		$eventEndDate = strtotime($eventEndDate.' '.$MetaEndTime);
            		$eventDate = strtotime($eventDate.' '.$MetaStartTime);
            		$diff_start = date('Y-m-d',$eventDate);
            		$diff_end = date('Y-m-d', $eventEndDate);
            		$days_extra = imic_dateDiff($diff_start, $diff_end);
					$go_cl = '';
            		//echo "sn";
            		if($days_extra>0) 
					{
						$go_cl = '';
						$en_dt_cl = strtotime(get_post_meta(get_the_ID(),'imic_event_end_dt',true));
            			$st_dt_cl = strtotime(get_post_meta(get_the_ID(),'imic_event_start_dt',true));
						$en_dt_cl_mn = date('m', $en_dt_cl);
						$st_dt_cl_mn = date('m', $st_dt_cl);
						if($en_dt_cl_mn!=$st_dt_cl_mn)
						{
							$go_cl = 1;
						}
                		$start_day = 0;
                		while($start_day<=$days_extra) 
						{
                    		$diff_sec = 86400*$start_day;
                    		$new_date = $eventDate+$diff_sec;
                    		$str_only_date = date('Y-m-d',$new_date);
                    		$en_only_time = date("G:i", $eventEndDate);
                    		$start_dt_tm = strtotime($str_only_date.' '.$en_only_time);
                    		//echo date('U');
                    		if($start_dt_tm>date('U')) 
							{
                        		$eventDate = $new_date;
                        		break;
                    		}
                    		$start_day++;
                		}
            		}
            		if($days_extra<1) 
					{
            			if(($frequency!='35')&&($frequency!='32')) 
						{
            				if($frequency==30) 
							{
            					$eventDate = strtotime("+".$fr_repeat." month", $eventDate);
            					$eventEndDate = strtotime("+".$fr_repeat." month", $eventEndDate);
            				}
            				else 
							{
            					$new_date = $seconds * $fr_repeat;
            					$eventDate = $eventDate + $new_date;
            					$eventEndDate = $eventEndDate + $new_date;
            					//echo $eventEndDate;
            				}
            			}
            			elseif($frequency=='32') 
						{
                		if($fr_repeat!=$frequency_count) 
						{
                			$eventDate = $multiple_dates[$fr_repeat];
                			$eventDate = strtotime($eventDate); }
            			}
            			else 
						{
                			$eventTime = date('G:i',$eventDate);
                			$eventDate = strtotime( date('Y-m-01',$eventDate) );
                			//$eventEndDate = date('G:i',$eventEndDate);
                			//$eventEndDate = strtotime( date('Y-m-01',$eventEndDate) );
                			if($fr_repeat==0) 
							{ 
								$fr_repeat = $fr_repeat+1; 
							}
            				$eventDate = strtotime("+".$fr_repeat." month", $eventDate);
            				//$eventEndDate = strtotime("+".$fr_repeat." month", $eventEndDate);
            				$next_month = date('F',$eventDate);
            				$next_event_year = date('Y',$eventDate);
            				//echo $next_month;
            				$eventDate = date('Y-m-d '.$eventTime, strtotime($frequency_month_day.' '.$frequency_week_day.' of '.$next_month.' '.$next_event_year));
            				//Changed in v1.1
            				//$eventEndDate = date('Y-m-d '.$eventTime, strtotime($frequency_month_day.' '.$frequency_week_day.' of '.$next_month.' '.$next_event_year));
            				//echo $eventDate;
            				$eventDate = strtotime($eventDate);
            				//Changed in 1.1
            				//$eventEndDate = strtotime($eventEndDate);
           				} 
					}
           			$st_dt = date('Y-m-d',$eventDate);
            		if($MetaStartTime!='')
            		{
            			if($event_show_until=='1')
            			{
                			$en_tm = date("G:i",$eventEndDate);
            			}
            			else
            			{
                			$en_tm = date("G:i",$eventDate);
            			}
            		}
            		else
            		{
                		$en_tm = "23:59";
            		}
            		$dt_tm = strtotime($st_dt.' '.$en_tm);
            		if($month!='') 
					{
                		if((($dt_tm > $previous_month_end) && ($dt_tm < $next_month_start))||($go_cl==1))
						{
                    		$event_add[$dt_tm + $sinc + $inc] = get_the_ID();
                       		$sinc++;
                		}
            		}
					else 
					{
            			if($status=="future") 
						{
            				if ($dt_tm >= date('U')) 
							{
                				$event_add[$dt_tm + $sinc + $inc] = get_the_ID();
                				$sinc++;
            				} 
						}
            			else 
						{
            				if ($dt_tm <= date('U')) 
							{
                				$event_add[$dt_tm + $sinc + $inc] = get_the_ID();
                				$sinc++;
            				}    
            			} 
					} 
				if($days_extra<1) 
				{ 
					$fr_repeat++; 
				} 
				else 
				{ 
				$fr_repeat = 1000000; 
			}
        }
    	endwhile;
   endif; 
   wp_reset_query(); 
   return $event_add; 
   }
}

/* save event via outside link */
if(!function_exists('imic_save_event'))
{
	 function imic_save_event()
	 {
		 	//date_default_timezone_set('Antarctica/Troll');
		  $query_string = base64_decode($_SERVER['QUERY_STRING']);
		  parse_str($query_string,$qsa);
		  if (array_key_exists('action',$qsa)) { $action = $qsa['action']; }
		  if (array_key_exists('id',$qsa)) { $id = $qsa['id']; }
		  if (array_key_exists('key',$qsa)) { $key = $qsa['key']; }
		  if (array_key_exists('autoplay',$qsa)) { $autoplay = $qsa['autoplay']; }
		  if(isset($action) && isset($id) && isset($key) && $key == 'imic_save_event')
		  {
			  $custom_post          = get_post($id); 
              $title                = $custom_post->post_title;
			  $content              = $custom_post->post_content;
			  $imic_event_address   = get_post_meta($id, 'imic_event_address', true);
			  $eventStartTime       = get_post_meta($id, 'imic_event_start_tm', true);
			  $eventStartDate       = get_post_meta($id, 'imic_event_start_dt', true);
			  $eventEndTime         = get_post_meta($id, 'imic_event_end_tm', true);
              $eventEndDate         = get_post_meta($id, 'imic_event_end_dt', true);
			  $random_name          = substr(rand().rand().rand().rand(),0,20);
				$user_tz = get_option('timezone_string');

				$schedule_date_start = $edate.' '.date_i18n('H:i', strtotime($eventStartTime));
				//$schedule_date_start->setTimeZone(new DateTimeZone('UTC'));
				$triggerOn_start =  $schedule_date_start;
				$schedule_date_end = $edate.' '.date_i18n('H:i', strtotime($eventEndTime));
				//$schedule_date_end->setTimeZone(new DateTimeZone('UTC'));
				$triggerOn_end =  $schedule_date_end;
			  switch($action)
			  {
				  case 'gcalendar' :
				     $google_save_url  = 'https://www.google.com/calendar/render?action=TEMPLATE';
				     $google_save_url .= '&dates='.date_i18n("Ymd\THis",strtotime("$triggerOn_start"));
					 $google_save_url .= '/'.date_i18n("Ymd\THis",strtotime("$triggerOn_end"));
					 $google_save_url .= '&location='.urlencode($imic_event_address);
					 $google_save_url .= '&text='.urlencode($title);
					//$google_save_url .= '&ctz=Antarctica/Troll';
					 $google_save_url .= '&details='.urlencode($content);
					 wp_redirect($google_save_url); exit;
				  break;
				  case 'icalendar' :
				    ob_start();
				    header("Content-Type: text/calendar; charset=utf-8");
					header("Content-Disposition: inline; filename=addto_calendar_".$random_name.".ics");
					 $title                = addslashes($title);
					 $title                = str_replace(array(",",":",";"),array("\,","\:","\;"),$title);
			         $content              = addslashes($content);
					 $content              = str_replace(array(",",":",";"),array("\,","\:","\;"),$content);
					 $content              = preg_replace('/\s+/',' ', $content);
					 $imic_event_address   = addslashes($imic_event_address);
					 $imic_event_address   = str_replace(array(",",":",";"),array("\,","\:","\;"),$imic_event_address);
					echo "BEGIN:VCALENDAR\n";
					echo "VERSION:2.0\n";
					echo "PRODID:Imitheme.com \n";
					echo "BEGIN:VEVENT\n";
					echo "UID:".date('Ymd').'T'.date('His').rand()."\n"; 
					echo "DTSTAMP;TZID=UTC:".date('Ymd').'T'.date('His')."\n";
					echo "DTSTART;TZID=UTC:".date("Ymd\THis",strtotime("$triggerOn_start"))."\n"; 
					echo "DTEND;TZID=UTC:".date("Ymd\THis",strtotime("$triggerOn_end"))."\n"; 
					echo "SUMMARY:$title\n";
					echo "LOCATION:$imic_event_address\n";
					echo "DESCRIPTION:$content\n";
					echo "END:VEVENT\n";
					echo "END:VCALENDAR\n";
				    ob_flush();
					exit;
				  break;
				  case 'outlook' :
				    ob_start();
				    header("Content-Type: text/calendar; charset=utf-8");
					header("Content-Disposition: inline; filename=addto_calendar_".$random_name.".ics");
					echo "BEGIN:VCALENDAR\n";
					echo "VERSION:2.0\n";
					echo "PRODID:Imitheme.com\n";
					echo "BEGIN:VEVENT\n";
					echo "UID:".date('Ymd').'T'.date('His')."-".rand()."\n"; 
					echo "DTSTAMP:".date('Ymd').'T'.date('His')."\n";
					echo "DTSTART:".date("Ymd\THis",strtotime("$triggerOn_start"))."\n"; 
					echo "DTEND:".date("Ymd\THis",strtotime("$triggerOn_end"))."\n"; 
					echo "SUMMARY:$title\n";
					echo "LOCATION:$imic_event_address\n";
					echo "DESCRIPTION: $content\n";
					echo "END:VEVENT\n";
					echo "END:VCALENDAR\n";
					ob_flush();
					exit;
				  break;
				  case 'outlooklive' :
				     $outlooklive_url  = 'https://bay03.calendar.live.com/calendar/calendar.aspx?rru=addevent';
					 $outlooklive_url .= '&summary='.urlencode($title);
					 $outlooklive_url .= '&location='.urlencode($imic_event_address);
					 $outlooklive_url .= '&description='.urlencode($content);
				     $outlooklive_url .= '&dtstart='.date("Ymd\THis",strtotime("$eventStartDate $eventStartTime"));
					 $outlooklive_url .= '&dtend='.date("Ymd\THis",strtotime("$eventEndDate $eventEndTime"));
					 wp_redirect($outlooklive_url); exit;
				  break;
				  case 'yahoo' :
				     $yahoo_url  = 'https://calendar.yahoo.com/?view=d&v=60&type=20';
					 $yahoo_url .= '&title='.urlencode($title);
					 $yahoo_url .= '&in_loc='.urlencode($imic_event_address);
					 $yahoo_url .= '&desc='.urlencode($content);
				     $yahoo_url .= '&st='.date("Ymd\THis",strtotime("$triggerOn_start"));
					 $yahoo_url .= '&et='.date("Ymd\THis",strtotime("$triggerOn_end"));
					 wp_redirect($yahoo_url); exit;
				  break;
			  }
		  }  
	 }
}
/* add action on init*/
add_action('init','imic_save_event');
//Add Sermons Filter Shortcode
if(!function_exists('imic_sermons_filter_shortcode'))
{
	function imic_sermons_filter_shortcode($atts, $content = null) 
	{
    extract(shortcode_atts(array(
		'categories' => '',
		'tags' => '',
		'speakers' => ''
    	), $atts));
		$output = '';
		$output .= '<form class="sermon-filter-search searchandfilter" method="get" action="'.esc_url(home_url()).'">
		<div>
		<ul>';
		$get_sermon_categories = get_terms('sermons-category');
		if(!empty($get_sermon_categories))
		{
			$output .= '<li>
			<select id="sermons-category" class="postform nativechurch_sermon_filters" name="sermons-category">
			<option selected value="" data-objects="">'.__('Sermons Categories', 'framework').'</option>';
			foreach($get_sermon_categories as $category)
			{
                $objects = json_encode(get_objects_in_term( $category->term_id, 'sermons-category'));
				$selected = ($categories==$category->slug)?'selected':'';
				$output .= "<option class='terms-search' ".$selected." value='".$category->slug."' data-objects='".$objects."'>".$category->name." (".$category->count.")</option>";
			}
			$output .= '</select>
			</li>';
		}
		$get_sermon_tags = get_terms('sermons-tag');
		if(!empty($get_sermon_tags))
		{
			$output .= '<li>
			<select id="sermons-tag" class="postform nativechurch_sermon_filters" name="sermons-tag">
			<option selected value="" data-objects="">'.__('Sermons Tag', 'framework').'</option>';
			foreach($get_sermon_tags as $tag)
			{
                $objects = json_encode(get_objects_in_term( $tag->term_id, 'sermons-tag'));
				$selected = ($tags==$tag->slug)?'selected':'';
				$output .= "<option class='terms-search' ".$selected." value='".$tag->slug."' data-objects='".$objects."'>".$tag->name." (".$tag->count.")</option>";
			}
			$output .= '</select>
			</li>';
		}
		$get_sermon_speakers = get_terms('sermons-speakers');
		if(!empty($get_sermon_speakers))
		{
			$output .= '<li>
			<select id="sermons-speakers" class="postform nativechurch_sermon_filters" name="sermons-speakers">
			<option selected value="" data-objects="">'.__('Sermons Speakers', 'framework').'</option>';
			foreach($get_sermon_speakers as $speaker)
			{
                $objects = json_encode(get_objects_in_term( $speaker->term_id, 'sermons-speakers'));
				$selected = ($speakers==$speaker->slug)?'selected':'';
				$output .= "<option class='terms-search' ".$selected." value='".$speaker->slug."' data-objects='".$objects."'>".$speaker->name." (".$speaker->count.")</option>";
			}
			$output .= '</select>
			</li>';
		}
		$output .= '<li>
		<input class="btn btn-default" type="submit" value="'.__('Filter', 'framework').'">
		</li>
		</ul>
		</div>
		</form>';
		return $output;
	}
	add_shortcode( 'imic-searchandfilter', 'imic_sermons_filter_shortcode' );
}
/* SIDEBAR SHORTCODES
  =================================================*/
function nativechurch_sidebar($atts, $content = null) {
    extract(shortcode_atts(array(
        "id" => "",
		"column" => 4
     ), $atts));
	 ob_start();
dynamic_sidebar($id);
$html = ob_get_contents();
ob_end_clean();
return $html;
}
add_shortcode('sidebar_megamenu', 'nativechurch_sidebar');
function nativechurch_dynamic_category_list()
{
	$cpt = $_POST['cpt'];
	$selected_cat = $_POST['selected_cat'];
	switch($cpt)
	{
		case 'product':
		$cat = 'product_cat';
		break;
		case 'causes':
		$cat = 'causes-category';
		break;
		case 'gallery':
		$cat = 'gallery-category';
		break;
		case 'staff':
		$cat = 'staff-category';
		break;
		case 'sermons':
		$cat = 'sermons-category';
		break;
		case 'event':
		$cat = 'event-category';
		break;
		default:
		$cat = 'category';
	}
	$post_cats = get_terms($cat);
  if(!empty($post_cats))
	{
		echo '<option value="">'.__('Select Post Category','imic-framework-admin').'</option>';
 		foreach ($post_cats as $post_cat) 
		{                        
 			$name = $post_cat->name;
      $id = $post_cat->term_id;
      $activePost = ($id == $selected_cat)? 'selected' : '';
      echo '<option value="'. $id .'"'. $activePost.'>' . $name . '</option>';
    }
 	}
	die();
}
add_action('wp_ajax_nopriv_nativechurch_dynamic_category_list', 'nativechurch_dynamic_category_list');
add_action('wp_ajax_nativechurch_dynamic_category_list', 'nativechurch_dynamic_category_list');

//Functions for Front End Event Listing
if(!function_exists('imic_insert_attachment'))
{
	function imic_insert_attachment($file_handler,$post_id,$setthumb='false') {
	if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK){ return __return_false(); 
	} 
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');
	
	$attach_id = media_handle_upload( $file_handler, $post_id );
	//set post thumbnail if setthumb is 1
	if ($setthumb == 1) update_post_meta($post_id,'_thumbnail_id',$attach_id);
	return $attach_id;
	}
}
//End Front End Event Listing
function nativechurch_get_terms_orderby( $orderby, $args ) {
  if ( isset( $args['orderby'] ) && 'include' == $args['orderby'] ) {
		$include = implode(',', array_map( 'absint', $args['include'] ));
		$orderby = "FIELD( t.term_id, $include )";
	}
	return $orderby;
}
add_filter( 'get_terms_orderby', 'nativechurch_get_terms_orderby', 10, 2 );
function nativechurch_get_terms_taxonomy($taxonomy = 'sermons-category', $get_term=array())
{
    $terms = get_terms($taxonomy);
    if(!is_wp_error($terms) && !empty($terms))
    {
        foreach($terms as $term)
        {
            $get_term[$term->term_id] = $term->name;
        }
    }
    return $get_term;
}
add_filter('nativechurch_get_terms', 'nativechurch_get_terms_taxonomy', 10, 2);
?>
