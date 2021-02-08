<?php
/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */
if (!class_exists('Redux_Framework_sample_config')) {
load_theme_textdomain('imic-framework-admin', IMIC_FILEPATH . '/language');
    class Redux_Framework_sample_config {
        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;
        public function __construct() {
            if (!class_exists('ReduxFramework')) {
                return;
            }
            // This is needed. Bah WordPress bugs.  ;)
            if (  true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }
        }
        public function initSettings() {
            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();
            // Set the default arguments
            $this->setArguments();
            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();
            // Create the sections and fields
            $this->setSections();
            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }
            // If Redux is running as a plugin, this will remove the demo notice and links
            //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));
            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }
        /**
          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.
         * */
        function compiler_action($options, $css) {
            //echo '<h1>The compiler hook has run!</h1>';
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }
              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
        }
        /**
          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.
          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons
         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'imic-framework-admin'),
                'desc' => __('<p>Did you know that the Framework sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$imic_options</strong></p>', 'imic-framework-admin'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );
            return $sections;
        }
        /**
          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;
            return $args;
        }
        /**
          Filter hook for filtering the default value of any given field. Very useful in development mode.
         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = __('Testing filter hook!','framework');
            return $defaults;
        }
        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {
            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);
                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }
        public function setSections() {
            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns        = array();
            if (is_dir($sample_patterns_path)) :
                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();
                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {
                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;
            ob_start();
            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';
            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'imic-framework-admin'), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview','framework'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview','framework'); ?>" />
                <?php endif; ?>
                <h4><?php echo $this->theme->display('Name'); ?></h4>
                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'imic-framework-admin'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'imic-framework-admin'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'imic-framework-admin') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.','framework-admin') . '</p>', __('http://codex.wordpress.org/Child_Themes','framework-admin'), $this->theme->parent()->display('Name'));
            }
            ?>
                </div>
            </div>
            <?php
            $item_info = ob_get_contents();
            ob_end_clean();
            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }
			load_theme_textdomain('imic-framework-admin', IMIC_FILEPATH . '/language');
			$defaultAdminLogo = get_template_directory_uri().'/images/logo@2x.png';
			$defaultBannerImages = get_template_directory_uri().'/images/page-header1.jpg';
			$default_favicon = get_template_directory_uri() . '/images/favicon.ico';
			$default_iphone = get_template_directory_uri() . '/images/apple-iphone.png';
			$default_iphone_retina = get_template_directory_uri() . '/images/apple-iphone-retina.png';
			$default_ipad = get_template_directory_uri() . '/images/apple-ipad.png';
			$default_ipad_retina = get_template_directory_uri() . '/images/apple-ipad-retina.png';
			$default_option_value = get_option('imic_options');
			$old_social_facebook = (isset($default_option_value['social-facebook']))?$default_option_value['social-facebook']:'Facebook';
			$old_social_twitter = (isset($default_option_value['social-twitter']))?$default_option_value['social-twitter']:'Twitter';
			$old_social_pinterest = (isset($default_option_value['social-pinterest']))?$default_option_value['social-pinterest']:'Pinterest';
			$old_social_gplus = (isset($default_option_value['social-googleplus']))?$default_option_value['social-googleplus']:'Plus';
			$old_social_ytube = (isset($default_option_value['social-youtube']))?$default_option_value['social-youtube']:'Youtube';
			$old_social_instagram = (isset($default_option_value['social-instagram']))?$default_option_value['social-instagram']:'Instagram';
			$old_social_vimeo = (isset($default_option_value['social-vimeo']))?$default_option_value['social-vimeo']:'Vimeo';
			$old_social_rss = (isset($default_option_value['site-rss']))?$default_option_value['site-rss']:'Rss';
			$default_logo = get_template_directory_uri() . '/images/logo.png';
			$default_cover = get_template_directory_uri() . '/images/cover.png';
// ACTUAL DECLARATION OF SECTIONS
$this->sections[] = array(
	'icon' => 'el-icon-cogs',
	'icon_class' => 'icon-large',
	'title' => __('General', 'imic-framework-admin'),
	'fields' => array(
        array(
            'id' => 'enable_maintenance',
            'type' => 'switch',
            'title' => __('Enable Maintenance', 'imic-framework-admin'),
            'subtitle' => __('Enable the themes in maintenance mode.', 'imic-framework-admin'),
            "default" => 0,
            'on' => __('Enabled', 'imic-framework-admin'),
            'off' => __('Disabled', 'imic-framework-admin'),
        ),
         array(
            'id' => 'switch-thumbnail',
            'type' => 'switch',
            'title' => __('Enable WP Thumbnail', 'imic-framework-admin'),
            'subtitle' => __('Enable/Disable the wordpress image thumbnail sizes for the website. If its disable then full size images will be used.', 'imic-framework-admin'),
            "default" => 1,
        ),
        array(
            'id' => 'enable_backtotop',
            'type' => 'switch',
            'title' => __('Enable Back To Top', 'imic-framework-admin'),
            'subtitle' => __('Enable the back to top button that appears at the bottom right corner of the screen.', 'imic-framework-admin'),
            "default" => 0,
        ),
        array(
            'id' => 'enable_rtl',
            'type' => 'switch',
            'title' => __('Enable RTL', 'imic-framework-admin'),
            'subtitle' => __('If you are using wordpress for RTL languages then you should enable this option.', 'imic-framework-admin'),
            "default" => 0,
        ),
       array(
            'id' => 'tracking-code',
            'type' => 'ace_editor',
            'title' => __('Tracking Code', 'imic-framework-admin'),
            'subtitle' => __('Paste your Google Analytics (or other) tracking code here. This will be added into the header template of your theme. Please put code without opening and closing script tags.', 'imic-framework-admin'),
        ),
       array(
            'id' => 'space-before-head',
            'type' => 'ace_editor',
            'title' => __('Space before closing head tag', 'imic-framework-demo'),
            'subtitle' => __('Add your code before closing head tag', 'imic-framework-demo'),
			'default' => '',
        ),
       array(
            'id' => 'space-before-body',
            'type' => 'ace_editor',
            'title' => __('Space before closing body tag', 'imic-framework-demo'),
            'subtitle' => __('Add your code before closing body tag', 'imic-framework-demo'),
			'default' => '',
        ),
    )
);
$this->sections[] = array(
    'icon' => 'el-icon-website',
    'title' => esc_html__('Responsive', 'imic-framework-admin'),
    'fields' => array(
        array(
            'id' => 'switch-responsive',
            'type' => 'switch',
            'title' => __('Enable Responsive', 'imic-framework-admin'),
            'subtitle' => __('Enable/Disable the responsive behaviour of the theme', 'imic-framework-admin'),
            "default" => 1,
        ),
        array(
            'id' => 'switch-zoom-pinch',
            'type' => 'switch',
            'title' => __('Enable Zoom on mobile devices', 'imic-framework-admin'),
            'subtitle' => __('Enable/Disable zoom pinch behaviour on touch devices', 'imic-framework-admin'),
            "default" => 0,
        ),
	),
);
$this->sections[] = array(
    'icon' => 'el-icon-screen',
    'title' => __('Layout', 'imic-framework-admin'),
    'fields' => array(
        array(
			'id'=>'site_layout',
			'type' => 'image_select',
			'compiler'=>true,
			'title' => __('Page Layout', 'imic-framework-admin'), 
			'subtitle' => __('Select the page layout type', 'imic-framework-admin'),
			'options' => array(
					'wide' => array('alt' => 'Wide', 'img' => get_template_directory_uri().'/images/wide.png'),
					'boxed' => array('alt' => 'Boxed', 'img' => get_template_directory_uri().'/images/boxed.png')
				),
			'default' => 'wide',
		),
		array(
			'id'=>'repeatable-bg-image',
			'type' => 'image_select',
			'required' => array('site_layout','equals','boxed'),
			'title' => __('Repeatable Background Images', 'imic-framework-admin'), 
			'subtitle' => __('Select image to set in background.', 'imic-framework-admin'),
			'options' => array(
				'pt1.png' => array('alt' => 'pt1', 'img' => get_template_directory_uri().'/images/patterns-t/pt1.png'),
				'pt2.png' => array('alt' => 'pt2', 'img' => get_template_directory_uri().'/images/patterns-t/pt2.png'),
				'pt3.png' => array('alt' => 'pt3', 'img' => get_template_directory_uri().'/images/patterns-t/pt3.png'),
				'pt4.png' => array('alt' => 'pt4', 'img' => get_template_directory_uri().'/images/patterns-t/pt4.png'),
				'pt5.png' => array('alt' => 'pt5', 'img' => get_template_directory_uri().'/images/patterns-t/pt5.png'),
				'pt6.png' => array('alt' => 'pt6', 'img' => get_template_directory_uri().'/images/patterns-t/pt6.png'),
				'pt7.png' => array('alt' => 'pt7', 'img' => get_template_directory_uri().'/images/patterns-t/pt7.png'),
				'pt8.png' => array('alt' => 'pt8', 'img' => get_template_directory_uri().'/images/patterns-t/pt8.png'),
				'pt9.png' => array('alt' => 'pt9', 'img' => get_template_directory_uri().'/images/patterns-t/pt9.png'),
				'pt10.png' => array('alt' => 'pt10', 'img' => get_template_directory_uri().'/images/patterns-t/pt10.png'),
				'pt11.jpg' => array('alt' => 'pt11', 'img' => get_template_directory_uri().'/images/patterns-t/pt11.png'),
				'pt12.jpg' => array('alt' => 'pt12', 'img' => get_template_directory_uri().'/images/patterns-t/pt12.png'),
				'pt13.jpg' => array('alt' => 'pt13', 'img' => get_template_directory_uri().'/images/patterns-t/pt13.png'),
				'pt14.jpg' => array('alt' => 'pt14', 'img' => get_template_directory_uri().'/images/patterns-t/pt14.png'),
				'pt15.jpg' => array('alt' => 'pt15', 'img' => get_template_directory_uri().'/images/patterns-t/pt15.png'),
				'pt16.png' => array('alt' => 'pt16', 'img' => get_template_directory_uri().'/images/patterns-t/pt16.png'),
				'pt17.png' => array('alt' => 'pt17', 'img' => get_template_directory_uri().'/images/patterns-t/pt17.png'),
				'pt18.png' => array('alt' => 'pt18', 'img' => get_template_directory_uri().'/images/patterns-t/pt18.png'),
				'pt19.png' => array('alt' => 'pt19', 'img' => get_template_directory_uri().'/images/patterns-t/pt19.png'),
				'pt20.png' => array('alt' => 'pt20', 'img' => get_template_directory_uri().'/images/patterns-t/pt20.png'),
				'pt21.png' => array('alt' => 'pt21', 'img' => get_template_directory_uri().'/images/patterns-t/pt21.png'),
				'pt22.png' => array('alt' => 'pt22', 'img' => get_template_directory_uri().'/images/patterns-t/pt22.png'),
				'pt23.png' => array('alt' => 'pt23', 'img' => get_template_directory_uri().'/images/patterns-t/pt23.png'),
				'pt24.png' => array('alt' => 'pt24', 'img' => get_template_directory_uri().'/images/patterns-t/pt24.png'),
				'pt25.png' => array('alt' => 'pt25', 'img' => get_template_directory_uri().'/images/patterns-t/pt25.png'),
				'pt26.png' => array('alt' => 'pt26', 'img' => get_template_directory_uri().'/images/patterns-t/pt26.png'),
				'pt27.png' => array('alt' => 'pt27', 'img' => get_template_directory_uri().'/images/patterns-t/pt27.png'),
				'pt28.png' => array('alt' => 'pt28', 'img' => get_template_directory_uri().'/images/patterns-t/pt28.png'),
				'pt29.png' => array('alt' => 'pt29', 'img' => get_template_directory_uri().'/images/patterns-t/pt29.png'),
				'pt30.png' => array('alt' => 'pt30', 'img' => get_template_directory_uri().'/images/patterns-t/pt30.png')
				)
		),	
		array(
			'id'=>'upload-repeatable-bg-image',
			'compiler'=>true,
			'required' => array('site_layout','equals','boxed'),
			'type' => 'media', 
			'url'=> true,
			'title' => __('Upload Repeatable Background Image', 'imic-framework-admin')
		),
		array(
			'id'=>'full-screen-bg-image',
			'compiler'=>true,
			'required' => array('site_layout','equals','boxed'),
			'type' => 'media', 
			'url'=> true,
			'title' => __('Upload Full Screen Background Image', 'imic-framework-admin')
		),
        array(
			'id'=>'site_width',
			'type' => 'text',
			'compiler'=>true,
			'title' => __('Site Width', 'imic-framework-admin'), 
			'subtitle' => __('Controls the overall site width. Without px, ex: 1040(Default). Recommended maximum width is 1170 to maintain the theme structure.', 'imic-framework-admin'),
			'default' => '1040',
		),	
		
    ),
);
$this->sections[] = array(
    'icon' => 'el-icon-ok',
    'title' => esc_html__('Content', 'imic-framework-admin'),
	'subsection' => true,
    'fields' => array(
		array(
			'id'       => 'content_padding_dimensions',
			'type'     => 'spacing',
			'units'    => array('px'),
			'mode'	   => 'padding',
			'left'	   => false,
			'right'	   => false,
			'output'   => array('.content'),
			'title'    => __('Top and Bottom padding for page content', 'imic-framework-demo'),
			'subtitle' => __('Enter top and bottom padding for page content. Default is 50px/50px', 'imic-framework-demo'),
			'default'            => array(
			'padding-top'     => '50px',
			'padding-bottom'  => '50px',
			'units'          => 'px',
			),
		),
		array(
			'id'       => 'content_min_height',
			'type'     => 'text',
			'title'    => __('Minimum Height for Content', 'imic-framework-demo'),
			'subtitle' => __('Enter minimum height for the page content part(Without px). Default is 400', 'imic-framework-demo'),
			'default'  => '400'
		),
        array(
			'id'=>'content_wide_width',
			'type' => 'checkbox',
			'compiler'=>true,
			'title' => __('100% Content Width', 'imic-framework-demo'), 
			'subtitle' => __('Check this box to set the content area to 100% of the browser width. Uncheck to follow site width. Only works with wide layout mode.', 'imic-framework-demo'),
			'default' => '0',
		),
		array(  'id' => 'content_background',
				'type' => 'background',
				'background-color'=> true,
				'output' => array('.content'),
				'title' => __('Content area Background', 'imic-framework-demo'),
    			'subtitle' => __('Background color or image for the content area. This works for both boxed or wide layouts.', 'imic-framework-demo'),
		),
	)
);
$this->sections[] = array(
    'icon' => 'el-icon-chevron-up',
    'title' => __('Header', 'imic-framework-admin'),
    'desc' => __('<p class="description">These are the options for the header.</p>', 'imic-framework-admin'),
    'fields' => array(
        array(
    		'id' => 'header_layout',
    		'type' => 'image_select',
    		'compiler'=>true,
			'title' => __('Header Layout','imic-framework-admin'), 
			'subtitle' => __('Select the Header layout', 'imic-framework-admin'),
    			'options' => array(
					'1' => array('title' => '', 'img' => get_template_directory_uri().'/images/headerLayout/header-1.jpg'),
    				'2' => array('title' => '', 'img' => get_template_directory_uri().'/images/headerLayout/header-2.jpg'),
    				'3' => array('title' => '', 'img' => get_template_directory_uri().'/images/headerLayout/header-3.jpg'),
    				'4' => array('title' => '', 'img' => get_template_directory_uri().'/images/headerLayout/header-4.jpg'),
    				),
    		'default' => '1'
    	),
		array(
            'id' => 'header3_textarea',
            'type' => 'textarea',
			'required' => array('header_layout','equals','3'),
            'title' => __('Header Style 3 right area', 'imic-framework-admin'),
            'subtitle' => __('Enter html or text to show at the right side of the logo in place of default search form in header style 3', 'imic-framework-admin'),
            'default' => ''
        ),
        array(
			'id'=>'header_wide_width',
			'type' => 'checkbox',
			'compiler'=>true,
			'title' => __('100% Header Width', 'imic-framework-admin'), 
			'subtitle' => __('Check this box to set the header to 100% of the browser width. Uncheck to follow site width. Only works with wide layout mode.', 'imic-framework-admin'),
			'default' => '0',
		),
		array(
            'id' => 'header_area_height',
            'type' => 'text',
            'title' => __('Header Area Height', 'imic-framework-admin'),
            'subtitle' => __('Enter height for header Area', 'imic-framework-admin'),
            'default' => 80
        ),
		array(  'id' => 'header_background_alpha',
				'type' => 'color_rgba',
				'output' => array('background-color' => '.site-header .topbar'),
				'title' => __('Header(Logo Area) Translucent Background', 'imic-framework-demo'),
				'subtitle'=> __('Default: rgba(255, 255, 255, 0.8)','imic-framework-admin'),
				'options'       => array(
					'show_input'                => true,
					'show_initial'              => true,
					'show_alpha'                => true,
					'show_palette'              => false,
					'show_palette_only'         => false,
					'show_selection_palette'    => true,
					'max_palette_size'          => 10,
					'allow_empty'               => true,
					'clickout_fires_change'     => false,
					'choose_text'               => 'Choose',
					'cancel_text'               => 'Cancel',
					'show_buttons'              => true,
					'use_extended_classes'      => true,
					'palette'                   => null,  // show default
					'input_text'                => 'Select Color'
				),
				'default'   => array(
					'color'     => '#ffffff',
					'alpha'     => .8
				),
		),
		array(  'id' => 'header_background_image',
				'type' => 'background',
				'background-color'=> false,
				'output' => array('.site-header .topbar'),
				'title' => __('Header(Logo Area) Background Image', 'imic-framework-demo'),
				'subtitle'=> __('This will override the translucent color style.','imic-framework-admin'),
		),
	),
);
$this->sections[] = array(
    'icon' => 'el-icon-ok',
    'title' => esc_html__('Sticky Header', 'imic-framework-admin'),
    'desc' => esc_html__('These are the options for the header.', 'imic-framework-admin'),
	'subsection' => true,
    'fields' => array(
        array(
            'id' => 'enable-header-stick',
            'type' => 'switch',
            'title' => __('Enable Header Stick', 'imic-framework-admin'),
            'subtitle' => __('Enable/Disable Header Stick behaviour of the theme', 'imic-framework-admin'),
            "default" => 1,
        ),
		array(  'id' => 'sticky_header_background_alpha',
			'type' => 'color_rgba',
			'output' => array('background-color' => '.is-sticky .main-menu-wrapper, .header-style4 .is-sticky .site-header .topbar, .header-style2 .is-sticky .main-menu-wrapper'),
			'title' => __('Sticky Header Background', 'imic-framework-demo'),
			'subtitle'=> __('Default: rgba(255, 255, 255, 0.8)','imic-framework-admin'),
			'desc' => __('On Header style 4 header logo area top area will be sticky so its bg color will be used here as well.', 'imic-framework-demo'),
			'options'       => array(
				'show_input'                => true,
				'show_initial'              => true,
				'show_alpha'                => true,
				'show_palette'              => false,
				'show_palette_only'         => false,
				'show_selection_palette'    => true,
				'max_palette_size'          => 10,
				'allow_empty'               => true,
				'clickout_fires_change'     => false,
				'choose_text'               => 'Choose',
				'cancel_text'               => 'Cancel',
				'show_buttons'              => true,
				'use_extended_classes'      => true,
				'palette'                   => null,  // show default
				'input_text'                => 'Select Color'
			),
			'default'   => array(
				'color'     => '#ffffff',
				'alpha'     => .8
			),
		),
		array(  'id' => 'sticky_header_background',
				'type' => 'background',
				'background-color'=> false,
				'output' => array('.is-sticky .main-menu-wrapper, .header-style4 .is-sticky .site-header .topbar, .header-style2 .is-sticky .main-menu-wrapper'),
				'title' => __('Sticky Header Background Image', 'imic-framework-admin'),
    			'subtitle' => __('Background color or image for the header.', 'imic-framework-admin')
		),
		array(
			'id'       => 'sticky_link_color',
			'type'     => 'link_color',
			'required' => array('header_layout','!=','4'),
			'title'    => __('Sticky Header Link Color', 'imic-framework-admin'),
			'desc'     => __('Set the sticky header/menu links color, hover, active.', 'imic-framework-admin'),
			'output'   => array('.is-sticky .navbar-collapse i,.is-sticky ul.nav.navbar-nav > li > a'),
		),
		array(
			'id'       => 'h4_sticky_link_color',
			'type'     => 'link_color',
			'required' => array('header_layout','=','4'),
			'title'    => __('Sticky Header Link Color', 'imic-framework-admin'),
			'desc'     => __('Set the sticky header/menu links color, hover, active.', 'imic-framework-admin'),
			'output'   => array('.header-style4 .is-sticky .top-navigation > li > a'),
		),
	),
);
$this->sections[] = array(
    'icon' => 'el-icon-ok',
    'title' => esc_html__('Topbar', 'imic-framework-admin'),
	'subsection' => true,
    'fields' => array(
		array(
            'id' => 'social-facebook',
            'type' => 'text',
			'Panel' => false,
			'required' => array('theme_color_types','equals','0'),
            'title' => __('Facebook', 'imic-framework-admin'),
            'subtitle' => __('Facebook URL to link your social bar\'s facebook icon.', 'imic-framework-admin'),
            'desc' => __('Enter your facebook URL for your theme footer.', 'imic-framework-admin'),
            'default' => 'https://www.facebook.com/',
        ),
        array(
            'id' => 'social-twitter',
            'type' => 'text',
			'required' => array('theme_color_types','equals','0'),
            'title' => __('Twitter', 'imic-framework-admin'),
            'subtitle' => __("Twitter URL to link your social bar's twitter icon.", 'imic-framework-admin'),
            'desc' => __('Enter your twitter URL for your theme footer.', 'imic-framework-admin'),
            'default' => 'https://twitter.com/',
        ),
        array(
            'id' => 'social-pinterest',
            'type' => 'text',
			'required' => array('theme_color_types','equals','0'),
            'title' => __('Pinterest', 'imic-framework-admin'),
            'subtitle' => __('Pinterest URL to link your social bar\'s Pinterest icon.', 'imic-framework-admin'),
            'desc' => __('Enter your Pinterest URL for your theme footer.', 'imic-framework-admin'),
            'default' => 'https://www.pinterest.com/',
        ),
        array(
            'id' => 'social-googleplus',
            'type' => 'text',
			'required' => array('theme_color_types','equals','0'),
            'title' => __('Google+', 'imic-framework-admin'),
            'subtitle' => __('Google+ URL to link your social bar\'s googleplus icon.', 'imic-framework-admin'),
            'desc' => __('Enter your googleplus URL for your theme footer.', 'imic-framework-admin'),
            'default' => 'https://www.google.co.in/',
        ),
        array(
            'id' => 'social-youtube',
            'type' => 'text',
			'required' => array('theme_color_types','equals','0'),
            'title' => __('Youtube', 'imic-framework-admin'),
            'subtitle' => __('Youtube URL to link your social bar\'s youtube icon.', 'imic-framework-admin'),
            'desc' => __('Enter your Youtube URL for your theme footer.', 'imic-framework-admin'),
            'default' => 'http://youtube.com/',
        ),
		
		array(
            'id' => 'social-instagram',
            'type' => 'text',
			'required' => array('theme_color_types','equals','0'),
            'title' => __('Instagram', 'imic-framework-admin'),
            'subtitle' => __('Instagram URL to link your social bar\'s Instagram icon.', 'imic-framework-admin'),
            'desc' => __('Enter your Instagram URL for your theme footer.', 'imic-framework-admin'),
            'default' => 'http://instagram.com/',
        ),
		
		array(
            'id' => 'social-vimeo',
            'type' => 'text',
			'required' => array('theme_color_types','equals','0'),
            'title' => __('Vimeo', 'imic-framework-admin'),
            'subtitle' => __('Vimeo URL to link your social bar\'s Vimeo icon.', 'imic-framework-admin'),
            'desc' => __('Enter your Vimeo URL for your theme footer.', 'imic-framework-admin'),
            'default' => 'http://vimeo.com/',
        ),
        array(
            'id' => 'site-rss',
            'type' => 'text',
			'required' => array('theme_color_types','equals','0'),
            'title' => __('Rss', 'imic-framework-admin'),
            'subtitle' => __('Rss URL to link your  Rss icon.', 'imic-framework-admin'),
            'desc' => __('Enter your Rss URL for you theme footer.', 'imic-framework-admin'),
            'default' => site_url().'/feed/',
        ),
		array(
			'id' => 'header_social_links',
			'type' => 'sortable',
			'label' => true,
			'compiler'=>true,
			'title' => __('Social Links', 'imic-framework-admin'),
			'desc' => __('Enter the social links and sort to active and display according to sequence in header.', 'imic-framework-admin'),
			'options' => array(
				'fa-facebook-square' => 'facebook',
				'fa-twitter-square' => 'twitter',
				'fa-pinterest' => 'pinterest',
				'fa-google-plus' => 'google',
				'fa-youtube' => 'youtube',
				'fa-instagram' => 'instagram',
				'fa-vimeo-square' => 'vimeo',
				'fa-rss' => 'rss',
				'fa-dribbble' => 'dribbble',
				'fa-dropbox' => 'dropbox',
				'fa-bitbucket' => 'bitbucket',
				'fa-flickr' => 'flickr',
				'fa-foursquare' => 'foursquare',
				'fa-github' => 'github',
				'fa-gittip' => 'gittip',
				'fa-linkedin' => 'linkedin',
				'fa-pagelines' => 'pagelines',
				'fa-skype' => 'Enter Skype ID',
				'fa-tumblr' => 'tumblr',
				'fa-vk' => 'vk',
				'fa-envelope' => 'Enter Email Address'
			),
		)
	),
);
$this->sections[] = array(
    'icon' => 'el-icon-ok',
    'title' => esc_html__('Inner Page Header', 'imic-framework-admin'),
	'subsection' => true,
    'fields' => array(
		array(
            'id' => 'header_image',
            'type' => 'media',
            'url' => true,
            'title' => __('Sub Pages Header Image', 'imic-framework-admin'),
            'desc' => __('Default header image for post types.', 'imic-framework-admin'),
            'subtitle' => __('Set this image as default header image for all Page/Post/Event/Sermons/Gallery.', 'imic-framework-admin'),
            'default' => array('url' => ''),
        ),
	),
);
$this->sections[] = array(
    'icon' => 'el-icon-ok',
    'title' => esc_html__('Mobile Header', 'imic-framework-admin'),
	'subsection' => true,
    'fields' => array(
		array(
            'id' => 'slider_behind_header',
            'type' => 'checkbox',
            'title' => __('Show slider behind header', 'imic-framework-admin'),
            'desc' => __('Uncheck if you want the slider on homepage to show below the header and not behind in header style 1 and 3.', 'imic-framework-admin'),
            'default' => 1,
        ),
	),
);
$this->sections[] = array(
    'icon' => 'el-icon-upload',
    'title' => esc_html__('Logo', 'imic-framework-admin'),
    'fields' => array(
        array(
            'id' => 'logo_upload',
            'type' => 'media',
            'url' => true,
            'title' => __('Upload Logo', 'imic-framework-admin'),
            'desc' => __('Logo used by most of the devices including desktops and not retina devices', 'imic-framework-admin'),
            'subtitle' => __('Upload site logo to display in header.', 'imic-framework-admin'),
            'default' => array('url' => $default_logo),
        ),
		array(
            'id' => 'logo_alt_text',
            'type' => 'text',
            'title' => __('Logo Image Alt Text', 'imic-framework-admin'),
            'subtitle' => __('Enter logo image alternative text. This will appear in browser tooltip on logo image hover.', 'imic-framework-admin'),
            'default' => 'Logo'
        ),
        array(
            'id' => 'retina_logo_upload',
            'type' => 'media',
            'url' => true,
            'title' => __('Upload Logo for Retina Devices', 'imic-framework-admin'),
            'desc' => __('Retina Display is a marketing term developed by Apple to refer to devices and monitors that have a resolution and pixel density so high – roughly 300 or more pixels per inch', 'imic-framework-admin'),
            'subtitle' => __('Upload site logo to display in header.', 'imic-framework-admin'),
        ),
		array(
            'id' => 'retina_logo_width',
            'type' => 'text',
            'title' => __('Standard Logo Width for Retina Logo', 'imic-framework-admin'),
            'subtitle' => __('If retina logo is uploaded, enter the standard logo (1x) version width, do not enter the retina logo width.', 'imic-framework-admin'),
        ),
		array(
            'id' => 'retina_logo_height',
            'type' => 'text',
            'title' => __('Standard Logo Height for Retina Logo', 'imic-framework-admin'),
            'subtitle' => __('If retina logo is uploaded, enter the standard logo (1x) version height, do not enter the retina logo height.', 'imic-framework-admin'),
        ),
	),
);
$this->sections[] = array(
    'icon' => 'el-icon-ok',
    'title' => esc_html__('Admin Logo', 'imic-framework-admin'),
	'subsection' => true,
    'fields' => array(
        array(
            'id' => 'custom_admin_login_logo',
            'type' => 'media',
            'url' => true,
            'title' => __('Custom admin login logo', 'imic-framework-admin'),
            'compiler' => 'true',
            //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
            'desc' => __('Upload a 254 x 95px image here to replace the admin login logo.', 'imic-framework-admin'),
            'subtitle' => __('', 'imic-framework-admin'),
            'default' => array('url' => $default_logo),
        ),
	),
);
$this->sections[] = array(
    'icon' => 'el-icon-ok',
    'title' => esc_html__('Favicon Options', 'imic-framework-admin'),
	'subsection' => true,
    'fields' => array(
        array(
            'id' => 'custom_favicon',
            'type' => 'media',
            'compiler' => 'true',
            'title' => __('Custom favicon', 'imic-framework-admin'),
            'desc' => __('Upload a .ico favicon image that will represent your website favicon', 'imic-framework-admin'),
            'default' => array('url' => $default_favicon),
        ),
        array(
            'id' => 'iphone_icon',
            'type' => 'media',
            'compiler' => 'true',
            'title' => __('Apple iPhone Icon', 'imic-framework-admin'),
            'desc' => __('Upload Favicon for Apple iPhone (57px x 57px)', 'imic-framework-admin'),
            'default' => array('url' => $default_iphone),
        ),
        array(
            'id' => 'iphone_icon_retina',
            'type' => 'media',
            'compiler' => 'true',
            'title' => __('Apple iPhone Retina Icon', 'imic-framework-admin'),
            'desc' => __('Upload Favicon for Apple iPhone Retina Version (114px x 114px)', 'imic-framework-admin'),
            'default' => array('url' => $default_iphone_retina),
        ),
        array(
            'id' => 'ipad_icon',
            'type' => 'media',
            'compiler' => 'true',
            'title' => __('Apple iPad Icon', 'imic-framework-admin'),
            'desc' => __('Upload Favicon for Apple iPad (72px x 72px)', 'imic-framework-admin'),
            'default' => array('url' => $default_ipad),
        ),
        array(
            'id' => 'ipad_icon_retina',
            'type' => 'media',
            'compiler' => 'true',
            'title' => __('Apple iPad Retina Icon Upload', 'imic-framework-admin'),
            'desc' => __('Upload Favicon for Apple iPad Retina Version (144px x 144px)', 'imic-framework-admin'),
            'default' => array('url' => $default_ipad_retina),
        ),
	),
);
$this->sections[] = array(
    'icon' => 'el-icon-lines',
    'title' => esc_html__('Menu', 'imic-framework-admin'),
    'fields' => array(
		array(  'id' => 'navigation_background_alpha',
			'type' => 'ƒ_rgba',
			'output' => array('background-color' => '.navigation, .header-style2 .main-menu-wrapper'),
			'title' => __('Navigation Bar Background', 'imic-framework-demo'),
			'subtitle'=> __('Default: #f8f7f3)','imic-framework-admin'),
			'options'       => array(
				'show_input'                => true,
				'show_initial'              => true,
				'show_alpha'                => true,
				'show_palette'              => false,
				'show_palette_only'         => false,
				'show_selection_palette'    => true,
				'max_palette_size'          => 10,
				'allow_empty'               => true,
				'clickout_fires_change'     => false,
				'choose_text'               => 'Choose',
				'cancel_text'               => 'Cancel',
				'show_buttons'              => true,
				'use_extended_classes'      => true,
				'palette'                   => null,  // show default
				'input_text'                => 'Select Color'
			),
			'default'   => array(
				'color'     => '#F8F7F3',
				'alpha'     => 1
			),
		),
        array(
			'id'          => 'main_nav_typo',
			'type'        => 'typography',
			'title'       => __('Main Navigation Typography', 'imic-framework-demo'),
			'subtitle'       => __('<strong>Defaults:</strong><br>Font Family - Roboto Condensed<br>Font weight - Normal<br>Font Size - 16px<br>Letter Spacing - 0<br>Text Transform - Uppercase', 'imic-framework-demo'),
			'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => false,
			'font-family' => true,
			'font-style'  => true,
			'font-weight' => true,
			'preview' 	  => true,
			'text-align'	  => false,
			'font-size'	  => true,
			'line-height' => false,
			'text-transform'=> true,
			'letter-spacing' => true,
			'output'      => array('.navbar-collapse i,ul.nav.navbar-nav > li > a'),
			'units'       =>'px',
		),
		array(
			'id'       => 'mainmenu_link_color',
			'type'     => 'link_color',
			'title'    => __('Main Navigation Link Color', 'imic-framework-demo'),
			'subtitle' => __('Default Regular: #5E5E5E<br>Hover: Your primary color<br>Active: Your primary color', 'imic-framework-demo'),
			'desc'     => __('Set the top navigation parent links color, hover, active.', 'imic-framework-demo'),
			'output'   => array('.navbar-collapse i,ul.nav.navbar-nav > li > a'),
		),
		array(  'id' => 'main_dropdown_background_alpha',
				'type' => 'color_rgba',
				'output' => array('background-color' => '.navigation > ul > li ul','border-bottom-color' => '.navigation > ul > li.megamenu > ul:before, .navigation > ul > li ul:before','border-right-color' => '.navigation > ul > li ul li ul:before'),
				'title' => __('Main Menu Dropdown Background', 'imic-framework-demo'),
				'subtitle'=> __('Default: #ffffff','imic-framework-admin'),
				'options'       => array(
					'show_input'                => true,
					'show_initial'              => true,
					'show_alpha'                => true,
					'show_palette'              => false,
					'show_palette_only'         => false,
					'show_selection_palette'    => true,
					'max_palette_size'          => 10,
					'allow_empty'               => true,
					'clickout_fires_change'     => false,
					'choose_text'               => 'Choose',
					'cancel_text'               => 'Cancel',
					'show_buttons'              => true,
					'use_extended_classes'      => true,
					'palette'                   => null,  // show default
					'input_text'                => 'Select Color'
				),
				'default'   => array(
					'color'     => '#ffffff',
					'alpha'     => 1
				),
		),
		array(
			'id'       => 'main_menu_dropdown_border',
			'type'     => 'border',
			'title'    => __('Main Menu Dropdown Links Border Bottom', 'imic-framework-demo'),
			'subtitle' => __('Default: 1px solid #f8f7f3', 'imic-framework-demo'),
			'output'   => array('.navigation > ul > li > ul li > a'),
			'top' 	   => false,
			'left' 	   => false,
			'right' 	   => false,
			'default'  => array(
				'border-color'  => '#f8f7f3',
				'border-style'  => 'solid',
				'border-width' => '1px',
			)
		),
        array(
			'id'          => 'main_nav_dropdown_typo',
			'type'        => 'typography',
			'title'       => __('Main Navigation Dropdown Typography', 'imic-framework-demo'),
			'subtitle'       => __('<strong>Defaults:</strong><br>Font Family - Roboto Condensed<br>Font weight - Normal<br>Font Size - 14px<br>Line Height - 20px<br>Letter Spacing - 0<br>Text transform - Uppercase', 'imic-framework-demo'),
			'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => false,
			'font-family' => true,
			'font-style'  => true,
			'font-weight' => true,
			'preview' 	  => true,
			'text-align'	  => false,
			'font-size'	  => true,
			'line-height' => true,
			'letter-spacing' => true,
			'text-transform'=> true,
			'output'      => array('.navigation > ul > li > ul li > a'),
			'units'       =>'px',
		),
		array(
			'id'       => 'main_dropdown_link_color',
			'type'     => 'link_color',
			'title'    => __('Main Menu Dropdown Link Color', 'imic-framework-demo'),
			'subtitle' => __('Default Regular: #5E5E5E<br>Hover: Your primary color<br>Active: Your primary color', 'imic-framework-demo'),
			'desc'     => __('Set the dropdown menu links color, hover, active.', 'imic-framework-demo'),
			'output'   => array('.navigation > ul > li > ul li > a'),
		),
	),
);
$this->sections[] = array(
    'icon' => 'el-icon-ok',
    'title' => esc_html__('Mobile Menu', 'imic-framework-admin'),
	'subsection' => true,
    'fields' => array(
        array(
            'id' => 'mobile_menu_icon',
            'type'        => 'typography',
			'title'       => __('Mobile Menu Icon', 'imic-framework-demo'),
			'google'      => false,
			'font-backup' => false,
			'subsets' 	  => false,
			'color' 		  => true,
			'text-align'	  => false,
            'font-weight' => false,
            'font-style' => false,
			'font-size'	  => true,
			'font-family' => false,
            'word-spacing'=>false,
			'line-height' => false,
			'letter-spacing' => false,
			'output'      => array('.site-header .menu-toggle'),
			'units'       =>'px',
            'default' => array(
             	'font-size' => '18px',
				'color' => '#5e5e5e',
              ),
        ),
		array(
            'id' => 'mobile_menu_text',
            'type' => 'text',
            'title' => __('Show text with mobile menu icon', 'imic-framework-admin'),
            'subtitle' => __('Enter text you want to show next to mobile menu icon. Keep it short and sweet. Eg: Menu', 'imic-framework-admin'),
			'default'=> ''
        ),
	),
);
$this->sections[] = array(
    'icon' => 'el-icon-ok',
    'title' => esc_html__('Top Menu', 'imic-framework-admin'),
	'subsection' => true,
    'fields' => array(
        array(
            'id' => 'enable-top-menu',
            'type' => 'switch',
            'title' => __('Enable Top Menu for Mobile', 'imic-framework-admin'),
            'subtitle' => __('Enable/Disable top navigation for small screen devices. If enabled, your top navigation will show as select menu on mobile devices.', 'imic-framework-admin'),
            "default" => 1,
        ),
        array(
			'id'          => 'top_nav_typo',
			'type'        => 'typography',
			'title'       => __('Top Navigation Typography', 'imic-framework-demo'),
			'subtitle'       => __('<strong>Defaults:</strong><br>Font Family - Roboto Condensed<br>Font weight - Bold<br>Font Size - 12px<br>Line Height - 20px<br>Letter Spacing - 2px<br>Text transform - Uppercase', 'imic-framework-demo'),
			'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => false,
			'font-family' => true,
			'font-style'  => true,
			'font-weight' => true,
			'preview' 	  => true,
			'text-align'	  => false,
			'font-size'	  => true,
			'line-height' => true,
			'letter-spacing' => true,
			'text-transform'=> true,
			'output'      => array('.top-navigation > li > a'),
			'units'       =>'px',
		),
		array(
			'id'       => 'topmenu_link_color',
			'type'     => 'link_color',
			'title'    => __('Top Navigation Link Color', 'imic-framework-demo'),
			'subtitle' => __('Default Regular: #5E5E5E<br>Hover: Your primary color<br>Active: Your primary color', 'imic-framework-demo'),
			'desc'     => __('Set the top navigation parent links color, hover, active.', 'imic-framework-demo'),
			'output'   => array('.top-navigation > li > a'),
		),
		array(  'id' => 'top_dropdown_background_alpha',
			'type' => 'color_rgba',
			'output' => array('background-color' => '.top-navigation > li ul','border-bottom-color' => '.top-navigation > li.megamenu > ul:before, .top-navigation > li ul:before','border-right-color' => '.top-navigation > li ul li ul:before'),
			'title' => __('Top Menu Dropdown Background', 'imic-framework-demo'),
			'subtitle'=> __('Default: #ffffff','imic-framework-admin'),
			'options'       => array(
				'show_input'                => true,
				'show_initial'              => true,
				'show_alpha'                => true,
				'show_palette'              => false,
				'show_palette_only'         => false,
				'show_selection_palette'    => true,
				'max_palette_size'          => 10,
				'allow_empty'               => true,
				'clickout_fires_change'     => false,
				'choose_text'               => 'Choose',
				'cancel_text'               => 'Cancel',
				'show_buttons'              => true,
				'use_extended_classes'      => true,
				'palette'                   => null,  // show default
				'input_text'                => 'Select Color'
			),
			'default'   => array(
				'color'     => '#ffffff',
				'alpha'     => 1
			),
		),
		array(
			'id'       => 'top_menu_dropdown_border',
			'type'     => 'border',
			'title'    => __('Top Menu Dropdown Links Border Bottom', 'imic-framework-demo'),
			'subtitle' => __('Default: 1px solid #f8f7f3', 'imic-framework-demo'),
			'output'   => array('.top-navigation > li > ul li > a'),
			'top' 	   => false,
			'left' 	   => false,
			'right' 	   => false,
			'default'  => array(
				'border-color'  => '#f8f7f3',
				'border-style'  => 'solid',
				'border-width' => '1px',
			)
		),
        array(
			'id'          => 'top_nav_dropdown_typo',
			'type'        => 'typography',
			'title'       => __('Top Navigation Dropdown Typography', 'imic-framework-demo'),
			'subtitle'       => __('<strong>Defaults:</strong><br>Font Family - Roboto Condensed<br>Font weight - Normal<br>Font Size - 12px<br>Line Height - 20px<br>Letter Spacing - 2px<br>Text transform - Uppercase', 'imic-framework-demo'),
			'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => false,
			'font-family' => true,
			'font-style'  => true,
			'font-weight' => true,
			'preview' 	  => true,
			'text-align'	  => false,
			'font-size'	  => true,
			'line-height' => true,
			'text-transform'=> true,
			'letter-spacing' => true,
			'output'      => array('.top-navigation > li > ul li > a'),
			'units'       =>'px',
		),
		array(
			'id'       => 'top_dropdown_link_color',
			'type'     => 'link_color',
			'title'    => __('Top Menu Dropdown Link Color', 'imic-framework-demo'),
			'subtitle' => __('Default Regular: #5E5E5E<br>Hover: Your primary color<br>Active: Your primary color', 'imic-framework-demo'),
			'desc'     => __('Set the dropdown menu links color, hover, active.', 'imic-framework-demo'),
			'output'   => array('.top-navigation > li > ul li > a'),
		),
	),
);
$this->sections[] = array(
    'icon' => 'el-icon-chevron-down',
    'title' => __('Footer', 'imic-framework-admin'),
    'desc' => __('<p class="description">These are the options for the footer.</p>', 'imic-framework-admin'),
    'fields' => array(
        array(
			'id'=>'footer_wide_width',
			'type' => 'checkbox',
			'compiler'=>true,
			'title' => __('100% Footer Width', 'imic-framework-admin'), 
			'subtitle' => __('Check this box to set the footer to 100% of the browser width. Uncheck to follow site width. Only works with wide layout mode.', 'imic-framework-admin'),
			'default' => '0',
		),
		array(
    		'id' => 'footer_layout',
    		'type' => 'image_select',
    		'compiler'=>true,
			'title' => __('Footer Layout', 'imic-framework-admin'), 
			'subtitle' => __('Select the footer layout', 'imic-framework'),
    			'options' => array(
					'12' => array('title' => '', 'img' => get_template_directory_uri().'/images/footerColumns/footer-1.png'),
    				'6' => array('title' => '', 'img' => get_template_directory_uri().'/images/footerColumns/footer-2.png'),
    				'4' => array('title' => '', 'img' => get_template_directory_uri().'/images/footerColumns/footer-3.png'),
    				'3' => array('title' => '', 'img' => get_template_directory_uri().'/images/footerColumns/footer-4.png'),
					'2' => array('title' => '', 'img' => get_template_directory_uri().'/images/footerColumns/footer-5.png'),
    							),
    		'default' => '4'
    	),
        array(
            'id' => 'footer_top_sec',
            'type' => 'section',
			'indent' => true,
            'title' => __('Footer Widgets Area', 'imic-framework-admin'),
        ),
		array(  'id' => 'top_footer_background_alpha',
				'type' => 'background',
				'output' => array('.site-footer'),
				'title' => __('Footer(Widgets Area) Background', 'imic-framework-demo'),
				'subtitle'=> __('Default: #F8F7F3','imic-framework-admin'),
				'default'  => array(
					'background-color' => '#F8F7F3',
				)
		),
		array(
			'id'=> 'footer_padding',
			'type'=> 'spacing',
			'output'=> array('.site-footer'),
			'mode' => 'padding',
			'left'=> false,
			'right'=> false,
			'units'=> array('px'),
			'title'=> __('Footer Widget Area Padding', 'redux-framework-demo'),
			'desc'=> __('Enter Top and Bottom padding values for the footer widget area. Do not include px in the fields', 'redux-framework-demo'),
			'default'=> array(
				'padding-top'=> '50px',
				'padding-bottom'=> '50px',
				'units'=> 'px',
			)
		),
        array(
			'id'          => 'top_footer_typo',
			'type'        => 'typography',
			'title'       => __('Footer(Widgets Area) Typography', 'imic-framework-demo'),
			'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => true,
			'font-family' => true,
			'font-style'  => true,
			'font-weight' => true,
			'preview' 	  => true,
			'text-align'	  => false,
			'font-size'	  => true,
			'line-height' => true,
			'letter-spacing' => true,
			'output'      => array('.site-footer, .site-footer p'),
			'units'       =>'px',
		),
        array(
			'id'          => 'top_footer_widgets_typo',
			'type'        => 'typography',
			'title'       => __('Footer Widgets Title Typography', 'imic-framework-demo'),
			'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => true,
			'font-family' => true,
			'font-style'  => true,
			'font-weight' => true,
			'preview' 	  => true,
			'text-align'	  => false,
			'font-size'	  => true,
			'line-height' => true,
			'letter-spacing' => true,
			'output'      => array('.site-footer .widgettitle'),
			'units'       =>'px',
			'default'     => array(
				'color'=> '#333333',
			),
		),
		array(
			'id'       => 'top_footer_widget_border',
			'type'     => 'border',
			'title'    => __('Border Bottom for Footer Widget Lists', 'imic-framework-demo'),
			'subtitle' => __('Default: 1px solid #ECEAE4', 'imic-framework-demo'),
			'output'   => array('.site-footer .listing-header, .site-footer .post-title, .site-footer .listing .item, .site-footer .post-meta, .site-footer .widget h4.footer-widget-title, .site-footer .widget ul > li'),
			'top' 	   => false,
			'left' 	   => false,
			'right' 	   => false,
			'default'  => array(
				'border-color'  => '#ECEAE4',
				'border-style'  => 'solid',
				'border-width' => '1px',
			)
		),
		array(
			'id'       => 'top_footer_link_color',
			'type'     => 'link_color',
			'title'    => __('Footer(Widgets Area) Link Color', 'imic-framework-demo'),
			'subtitle' => __('Default Regular: #5E5E5E<br>Hover: Your primary color<br>Active: Your primary color', 'imic-framework-demo'),
			'desc'     => __('Set the top footer links color, hover, active.', 'imic-framework-demo'),
			'output'   => array('.site-footer a'),
		),
        array(
            'id' => 'footer_bottom_sec',
            'type' => 'section',
			'indent' => true,
            'title' => __('Footer Copyrights Area', 'imic-framework-admin'),
        ),
        array(
            'id' => 'footer_copyright_text',
            'type' => 'textarea',
            'title' => __('Footer Copyright Text', 'imic-framework-admin'),
            'subtitle' => __(' Enter Copyright Text', 'imic-framework-admin'),
            'default' => __('All Rights Reserved', 'imic-framework-admin')
        ),
		array(
			'id' => 'footer_social_links',
			'type' => 'sortable',
			'label' => true,
			'compiler'=>true,
			'title' => __('Social Links', 'imic-framework-admin'),
			'desc' => __('Insert Social URL in their respective fields and sort as your desired order.', 'imic-framework-admin'),
			'options' => array(
				'fa-facebook' => $old_social_facebook,
				'fa-twitter' => $old_social_twitter,
				'fa-pinterest' => $old_social_pinterest,
				'fa-google-plus' => $old_social_gplus,
				'fa-youtube' => $old_social_ytube,
				'fa-instagram' => $old_social_instagram,
				'fa-vimeo-square' => $old_social_vimeo,
				'fa-rss' => $old_social_rss,
				'fa-dribbble' => 'dribbble',
				'fa-dropbox' => 'dropbox',
				'fa-bitbucket' => 'bitbucket',
				'fa-flickr' => 'flickr',
				'fa-foursquare' => 'foursquare',
				'fa-github' => 'github',
				'fa-gittip' => 'gittip',
				'fa-linkedin' => 'linkedin',
				'fa-pagelines' => 'pagelines',
				'fa-skype' => 'Enter Skype ID',
				'fa-tumblr' => 'tumblr',
				'fa-vk' => 'vk',
				'fa-envelope' => 'Enter Email Address'
			),
		),
		array(  'id' => 'bottom_footer_background_alpha',
				'type' => 'background',
				'output' => array('.site-footer-bottom'),
				'title' => __('Footer(Copyrights Area) Background', 'imic-framework-demo'),
				'subtitle'=> __('Default: #ECEAE4','imic-framework-admin'),
				'default'  => array(
					'background-color' => '#ECEAE4',
				)
		),
		array(
			'id'=> 'copyrights_padding',
			'type'=> 'spacing',
			'output'=> array('.site-footer-bottom'),
			'mode' => 'padding',
			'left'=> false,
			'right'=> false,
			'units'=> array('px'),
			'title'=> __('Footer Copyrights Area Padding', 'redux-framework-demo'),
			'desc'=> __('Enter Top and Bottom padding values for the footer copyrights area. Do not include px in the fields', 'redux-framework-demo'),
			'default'=> array(
				'padding-top'=> '20px',
				'padding-bottom'=> '20px',
				'units'=> 'px',
			)
		),	
        array(
			'id'          => 'bottom_footer_typo',
			'type'        => 'typography',
			'title'       => __('Footer(Copyrights Area) Typography', 'imic-framework-demo'),
			'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => true,
			'font-family' => true,
			'font-style'  => true,
			'font-weight' => true,
			'preview' 	  => true,
			'text-align'	  => false,
			'font-size'	  => true,
			'line-height' => true,
			'letter-spacing' => true,
			'output'      => array('.site-footer-bottom .copyrights-col-left'),
			'units'       =>'px',
			'subtitle'    => __('Line height should be same as the height you just defined above.', 'imic-framework-demo'),
		),
		array(
			'id'       => 'bottom_footer_link_color',
			'type'     => 'link_color',
			'title'    => __('Footer(Copyrights Area) Links Color', 'imic-framework-demo'),
			'subtitle' => __('Default Regular: #5E5E5E<br>Hover: Your primary color<br>Active: Your primary color', 'imic-framework-demo'),
			'desc'     => __('Set the bottom footer links color, hover, active.', 'imic-framework-demo'),
			'output'   => array('.site-footer-bottom .copyrights-col-left a'),
		),
		array(  'id' => 'footer_social_background_alpha',
			'type' => 'color_rgba',
			'output' => array('background-color' => '.site-footer-bottom .social-icons a'),
			'title' => __('Footer Social Icons Background', 'imic-framework-demo'),
			'subtitle'=> __('Default: #999999','imic-framework-admin'),
			'options'       => array(
				'show_input'                => true,
				'show_initial'              => true,
				'show_alpha'                => true,
				'show_palette'              => false,
				'show_palette_only'         => false,
				'show_selection_palette'    => true,
				'max_palette_size'          => 10,
				'allow_empty'               => true,
				'clickout_fires_change'     => false,
				'choose_text'               => 'Choose',
				'cancel_text'               => 'Cancel',
				'show_buttons'              => true,
				'use_extended_classes'      => true,
				'palette'                   => null,  // show default
				'input_text'                => 'Select Color'
			),
			'default'   => array(
				'color'     => '#999999',
				'alpha'     => 1
			),
		),
		array(  'id' => 'footer_social_background_hover_alpha',
			'type' => 'color_rgba',
			'output' => array('background-color' => '.site-footer-bottom .social-icons a:hover'),
			'title' => __('Footer Social Icons Hover Background', 'imic-framework-demo'),
			'subtitle'=> __('Default: #666666','imic-framework-admin'),
			'options'       => array(
				'show_input'                => true,
				'show_initial'              => true,
				'show_alpha'                => true,
				'show_palette'              => false,
				'show_palette_only'         => false,
				'show_selection_palette'    => true,
				'max_palette_size'          => 10,
				'allow_empty'               => true,
				'clickout_fires_change'     => false,
				'choose_text'               => 'Choose',
				'cancel_text'               => 'Cancel',
				'show_buttons'              => true,
				'use_extended_classes'      => true,
				'palette'                   => null,  // show default
				'input_text'                => 'Select Color'
			),
			'default'   => array(
				'color'     => '#666666',
				'alpha'     => 1
			),
		),
		array(
			'id'       => 'footer_social_link_color',
			'type'     => 'link_color',
			'title'    => __('Footer Social Icons Link Color', 'imic-framework-demo'),
			'subtitle' => __('Default Regular: #ffffff<br>Hover: Your primary color<br>Active: Your primary color', 'imic-framework-demo'),
			'desc'     => __('Set the bottom footer social icons link color, hover, active.', 'imic-framework-demo'),
			'output'   => array('.site-footer-bottom .social-icons a'),
		),
		array(
			'id'       => 'footer_social_link_dimensions',
			'type'     => 'dimensions',
			'units'    => array('px'),
			'output'   => array('.site-footer-bottom .social-icons a'),
			'title'    => __('Footer Social Icons Dimensions', 'imic-framework-demo'),
			'subtitle' => __('Enter width/height of the footer rounded icons. Default is 25/25', 'imic-framework-demo'),
			'default'  => array(
				'width'   => '25',
				'height'  => '25'
			),
		),
        array(
			'id'          => 'footer_social_link_typo',
			'type'        => 'typography',
			'title'       => __('Footer Social Links typography', 'imic-framework-demo'),
			'google'      => false,
			'font-backup' => false,
			'subsets' 	  => false,
			'color' 		  => false,
			'font-family' => false,
			'font-style' => false,
			'font-weight' => false,
			'preview' => false,
			'text-align'	  => false,
			'font-size'	  => true,
			'line-height' => true,
			'letter-spacing' => false,
			'output'      => array('.site-footer-bottom .social-icons a'),
			'units'       =>'px',
			'subtitle'    => __('Line height should be same as the height you just defined above.', 'imic-framework-demo'),
			'default'     => array(
				'font-size'=> '14px',
				'line-height'=>'25px'
			),
		),
    ),
);
$this->sections[] = array(
    'icon' => 'el-icon-lines',
    'title' => __('Sidebars', 'imic-framework-admin'),
    'fields' => array(
        array(
    		'id' => 'sidebar_position',
    		'type' => 'image_select',
    		'compiler'=>true,
			'title' => __('Sidebar position','imic-framework-admin'), 
			'subtitle' => __('Select the Global Sidebar Position. Can be overridden by page sidebar settings.', 'imic-framework-admin'),
    			'options' => array(
    				'2' => array('title' => 'Left', 'img' => ReduxFramework::$_url.'assets/img/2cl.png'),
					'1' => array('title' => 'Right', 'img' => ReduxFramework::$_url.'assets/img/2cr.png'),
    				),
    		'default' => '1'
    	),
		array(
			'id'       => 'post_sidebar',
			'type'     => 'select',
			'title'    => __('Post Sidebar Option', 'imic-framework-admin'), 
			'desc'     => __('Select sidebar to display by default on post pages.', 'imic-framework-admin'),
			'data'  => 'sidebars',
			'default'  => '',
		),
		array(
			'id'       => 'page_sidebar',
			'type'     => 'select',
			'title'    => __('Page Sidebar Option', 'imic-framework-admin'), 
			'desc'     => __('Select sidebar to display by default on pages.', 'imic-framework-admin'),
			'data'  => 'sidebars',
			'default'  => '',
		),
		array(
			'id'       => 'event_sidebar',
			'type'     => 'select',
			'title'    => __('Event Sidebar Option', 'imic-framework-admin'), 
			'desc'     => __('Select sidebar to display by default on events.', 'imic-framework-admin'),
			'data'  => 'sidebars',
			'default'  => '',
		),
		array(
			'id'       => 'cause_sidebar',
			'type'     => 'select',
			'title'    => __('Cause Sidebar Option', 'imic-framework-admin'), 
			'desc'     => __('Select sidebar to display by default on causes.', 'imic-framework-admin'),
			'data'  => 'sidebars',
			'default'  => '',
		),
		array(
			'id'       => 'sermon_sidebar',
			'type'     => 'select',
			'title'    => __('Sermon Sidebar Option', 'imic-framework-admin'), 
			'desc'     => __('Select sidebar to display by default on sermon pages.', 'imic-framework-admin'),
			'data'  => 'sidebars',
			'default'  => '',
		),
		array(
			'id'       => 'staff_sidebar',
			'type'     => 'select',
			'title'    => __('Staff Sidebar Option', 'imic-framework-admin'), 
			'desc'     => __('Select sidebar to display by default on staff pages.', 'imic-framework-admin'),
			'data'  => 'sidebars',
			'default'  => '',
		),
		array(
			'id'       => 'bbpress_sidebar',
			'type'     => 'select',
			'title'    => __('bbpress Sidebar Option', 'imic-framework-admin'), 
			'desc'     => __('Select sidebar to display by default on all bbpress pages globally.', 'imic-framework-admin'),
			'data'  => 'sidebars',
			'default'  => '',
		)
	),
);
$this->sections[] = array(
    'icon' => 'el-icon-brush',
    'title' => __('Color Scheme', 'imic-framework-admin'),
    'fields' => array(
		array(
			'id' => 'section-backgrounds-start',
			'type' => 'section',
			'title' => __('Primary Color', 'imic-framework-demo'),
			'subtitle' => __('Set the primary color scheme for the website', 'imic-framework-demo'),
			'indent' => false
	   ),
		 array(
			'id'=>'theme_color_type',
			'type' => 'button_set',
			'compiler'=>true,
			'title' => __('Site Color Scheme', 'imic-framework-admin'), 
			'subtitle' => __('Select the color scheme of the website', 'imic-framework-admin'),
			'options' => array(
					'0' => __('Pre-Defined Color Schemes','imic-framework-admin'),
					'1' => __('Custom Color','imic-framework-admin')
				),
			'default' => '0',
			),
        array(
            'id' => 'theme_color_scheme',
            'type' => 'select',
			'required' => array('theme_color_type','equals','0'),
            'title' => __('Predefined Schemes', 'imic-framework-admin'),
            'subtitle' => __('Select your themes alternative color scheme.', 'imic-framework-admin'),
            'options' => array('color0.css' => 'color0.css','color1.css' => 'color1.css', 'color2.css' => 'color2.css', 'color3.css' => 'color3.css', 'color4.css' => 'color4.css', 'color5.css' => 'color5.css', 'color6.css' => 'color6.css', 'color7.css' => 'color7.css', 'color8.css' => 'color8.css', 'color9.css' => 'color9.css', 'color10.css' => 'color10.css'),
            'default' => 'color0.css',
        ),	
		array(
			'id'=>'custom_theme_color',
			'type' => 'color',
			'required' => array('theme_color_type','equals','1'),
			'title' => __('Custom Color', 'imic-framework-admin'), 
			'subtitle' => __('Pick a primary color for the template.', 'imic-framework-admin'),
			'validate' => 'color',
		),
	),
);
$this->sections[] = array(
    'icon' => 'el-icon-font',
    'title' => __('Typography', 'imic-framework-admin'),
);
$this->sections[] = array(
    'icon' => 'el-icon-font',
    'title' => __('Global Font Options', 'imic-framework-admin'),
    'subtitle' => __('Global Font Family Sets (Can be override with dedicated styling options)', 'imic-framework-admin'),
	'subsection' => true,
	'desc' => __('These options are as per the design which consists of 3 fonts. For more advanced typography options see Sub Sections below this in Left Sidebar. Make sure you set these options only if you have knowledge about every property to avoid disturbing the whole layout. If something went wrong just reset this section to reset all fields in Typography Options or click the small cross signs in each select field/delete text from input fields to reset them.','framework'),
    'fields' => array(
        array(
            'id' => 'body_font_typography',
            'type'        => 'typography',
			'title'       => __('Primary font', 'imic-framework-demo'),
			'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => false,
			'text-align'	  => false,
            'font-weight' => false,
            'font-style' => false,
			'font-size'	  => false,
            'word-spacing'=>true,
			'line-height' => false,
			'letter-spacing' => true,
			'output'      => array('h1,h2,h3,h4,h5,h6,body,.event-item .event-detail h4,.site-footer-bottom'),
			'units'       =>'px',
	    	'subtitle' => __('Please Enter Body Font.', 'imic-framework-admin'),
            'default' => array(
             	'font-family' => 'Roboto',
				'font-backup' => '',
				'word-spacing' => '0px',
				'letter-spacing' => '0px',
              ),
        ),
        array(
            'id' => 'heading_font_typography',
            'type'        => 'typography',
			'title'       => __('Secondary font', 'imic-framework-demo'),
			'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => false,
			'text-align'	  => false,
            'font-weight' => false,
            'font-style' => false,
			'font-size'	  => false,
            'word-spacing'=>true,
			'line-height' => false,
			'letter-spacing' => true,
			'output'      => array('h4,.title-note,.btn,.top-navigation,.navigation,.notice-bar-title strong,.timer-col #days, .timer-col #hours, .timer-col #minutes, .timer-col #seconds,.event-date,.event-date .date,.featured-sermon .date,.page-header h1,.timeline > li > .timeline-badge span,.woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button'),
			'units'       =>'px',
            'subtitle' => __('Please Enter Heading Font', 'imic-framework-admin'),
            'default' => array(
            	'font-family' => 'Roboto Condensed',
				'font-backup' => '',
				'word-spacing' => '0px',
				'letter-spacing' => '0px',
               ),
        ),
        array(
            'id' => 'metatext_date_font_typography',
            'type' => 'typography',
            'title' => __('Metatext/date Cursive Font', 'imic-framework-admin'),
            'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => false,
			'text-align'	  => false,
            'font-weight' => false,
            'font-style' => false,
			'font-size'	  => false,
            'word-spacing'=>true,
			'line-height' => false,
			'letter-spacing' => true,
			'output'      => array('blockquote p,.cursive,.meta-data,.fact'),
			'units'       =>'px',
            'subtitle' => __('Please Enter Metatext date Font', 'imic-framework-admin'),
            'default' => array(
           	 	'font-family' => 'Volkhov',
				'font-backup' => '',
				'word-spacing' => '0px',
				'letter-spacing' => '0px',
            ),
        ),
    ),
);
$this->sections[] = array(
    'icon' => 'el-icon-check-empty',
    'title' => __('Body', 'imic-framework-admin'),
    'subtitle' => __('Style typography, color and other settings for body content', 'imic-framework-admin'),
	'subsection' => true,
    'fields' => array(
        array(
			'id'          => 'body_font_typo',
			'type'        => 'typography',
			'title'       => __('Body text default typography', 'imic-framework-demo'),
			'subtitle'       => __('<strong>Defaults:</strong><br>Font Family - Roboto<br>Font weight - Normal<br>Font Size - 14px<br>Line Height - 20px<br>Letter Spacing - 0px<br>Color - #666666<br>Text transform - none', 'imic-framework-demo'),
			'desc'		  => __('This applies to only the parts of your website where the content from page editor comes in','imic-framework-admin'),
			'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => true,
			'font-family' => true,
			'font-style'  => true,
			'font-weight' => true,
			'preview' 	  => true,
			'text-align'	  => false,
			'font-size'	  => true,
			'line-height' => true,
			'letter-spacing' => true,
			'text-transform' => true,
			'output'      => array('.page-content, .page-content p'),
			'units'       =>'px',
		),
        array(
			'id'          => 'body_h1_font_typo',
			'type'        => 'typography',
			'title'       => __('H1 heading typography', 'imic-framework-demo'),
			'subtitle'       => __('<strong>Defaults:</strong><br>Font Family - Roboto<br>Font weight - Normal<br>Font Size - 36px<br>Line Height - 42px<br>Letter Spacing - 0px<br>Color - #333333<br>Text transform - none', 'imic-framework-demo'),
			'desc'		  => __('This applies to only the parts of your website where the content from page editor comes in','imic-framework-admin'),
			'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => true,
			'font-family' => true,
			'font-style'  => true,
			'font-weight' => true,
			'preview' 	  => true,
			'text-align'	  => false,
			'font-size'	  => true,
			'line-height' => true,
			'text-transform' => true,
			'letter-spacing' => true,
			'output'      => array('.page-content h1'),
			'units'       =>'px',
		),
        array(
			'id'          => 'body_h2_font_typo',
			'type'        => 'typography',
			'title'       => __('H2 heading typography', 'imic-framework-demo'),
			'subtitle'       => __('<strong>Defaults:</strong><br>Font Family - Roboto<br>Font weight - Normal<br>Font Size - 30px<br>Line Height - 36px<br>Letter Spacing - 0px<br>Color - #333333<br>Text transform - none', 'imic-framework-demo'),
			'desc'		  => __('This applies to only the parts of your website where the content from page editor comes in','imic-framework-admin'),
			'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => true,
			'font-family' => true,
			'font-style'  => true,
			'font-weight' => true,
			'preview' 	  => true,
			'text-align'	  => false,
			'font-size'	  => true,
			'line-height' => true,
			'text-transform' => true,
			'letter-spacing' => true,
			'output'      => array('.page-content h2'),
			'units'       =>'px',
		),
        array(
			'id'          => 'body_h3_font_typo',
			'type'        => 'typography',
			'title'       => __('H3 heading typography', 'imic-framework-demo'),
			'subtitle'       => __('<strong>Defaults:</strong><br>Font Family - Roboto<br>Font weight - Normal<br>Font Size - 24px<br>Line Height - 30px<br>Letter Spacing - 0px<br>Color - #333333<br>Text transform - none', 'imic-framework-demo'),
			'desc'		  => __('This applies to only the parts of your website where the content from page editor comes in','imic-framework-admin'),
			'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => true,
			'font-family' => true,
			'font-style'  => true,
			'font-weight' => true,
			'preview' 	  => true,
			'text-align'	  => false,
			'font-size'	  => true,
			'line-height' => true,
			'text-transform' => true,
			'letter-spacing' => true,
			'output'      => array('.page-content h3'),
			'units'       =>'px',
		),
        array(
			'id'          => 'body_h4_font_typo',
			'type'        => 'typography',
			'title'       => __('H4 heading typography', 'imic-framework-demo'),
			'subtitle'       => __('<strong>Defaults:</strong><br>Font Family - Roboto Condensed<br>Font weight - Bold<br>Font Size - 16px<br>Line Height - 22px<br>Letter Spacing - 2px<br>Color - #333333<br>Text transform - Uppercase', 'imic-framework-demo'),
			'desc'		  => __('This applies to only the parts of your website where the content from page editor comes in','imic-framework-admin'),
			'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => true,
			'font-family' => true,
			'font-style'  => true,
			'font-weight' => true,
			'preview' 	  => true,
			'text-align'	  => false,
			'font-size'	  => true,
			'line-height' => true,
			'text-transform' => true,
			'letter-spacing' => true,
			'output'      => array('.page-content h4'),
			'units'       =>'px',
		),
        array(
			'id'          => 'body_h5_font_typo',
			'type'        => 'typography',
			'title'       => __('H5 heading typography', 'imic-framework-demo'),
			'subtitle'       => __('<strong>Defaults:</strong><br>Font Family - Roboto<br>Font weight - Bold<br>Font Size - 16px<br>Line Height - 22px<br>Letter Spacing - 0px<br>Color - #333333<br>Text transform - none', 'imic-framework-demo'),
			'desc'		  => __('This applies to only the parts of your website where the content from page editor comes in','imic-framework-admin'),
			'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => true,
			'font-family' => true,
			'font-style'  => true,
			'font-weight' => true,
			'preview' 	  => true,
			'text-align'	  => false,
			'font-size'	  => true,
			'line-height' => true,
			'text-transform' => true,
			'letter-spacing' => true,
			'output'      => array('.page-content h5'),
			'units'       =>'px',
		),
        array(
			'id'          => 'body_h6_font_typo',
			'type'        => 'typography',
			'title'       => __('H6 heading typography', 'imic-framework-demo'),
			'subtitle'       => __('<strong>Defaults:</strong><br>Font Family - Roboto<br>Font weight - Normal<br>Font Size - 12px<br>Line Height - 18px<br>Letter Spacing - 0px<br>Color - #333333<br>Text transform - none', 'imic-framework-demo'),
			'desc'		  => __('This applies to only the parts of your website where the content from page editor comes in','imic-framework-admin'),
			'google'      => true,
			'font-backup' => true,
			'subsets' 	  => true,
			'color' 		  => true,
			'font-family' => true,
			'font-style'  => true,
			'font-weight' => true,
			'preview' 	  => true,
			'text-align'	  => false,
			'font-size'	  => true,
			'line-height' => true,
			'text-transform' => true,
			'letter-spacing' => true,
			'output'      => array('.page-content h6'),
			'units'       =>'px',
		),
	)
);
$this->sections[] = array(
    'icon' => 'el-icon-share',
    'title' => __('Share Options', 'imic-framework-admin'),
    'fields' => array(
        array(
            'id' => 'switch_sharing',
            'type' => 'switch',
            'title' => __('Social Sharing', 'imic-framework-admin'),
            'subtitle' => __('Enable/Disable theme default social sharing buttons for posts/events/sermons/causes single pages.<br><br>Install the recommended plugin "<a href="https://wordpress.org/plugins/wonderm00ns-simple-facebook-open-graph-tags/">Facebook Open Graph, Google+ and Twitter Card Tags</a> from Appearance > Install Plugins. This will help get the correct data for share options."', 'imic-framework-admin'	
			),
            "default" => 1,
       	),
		 array(
			'id'=>'sharing_style',
			'type' => 'button_set',
			'compiler'=>true,
			'title' => __('Share Buttons Style', 'imic-framework-admin'), 
			'subtitle' => __('Choose the style of share button icons', 'imic-framework-admin'),
			'options' => array(
					'0' => __('Rounded','imic-framework-admin'),
					'1' => __('Squared','imic-framework-admin')
				),
			'default' => '0',
		),
		 array(
			'id'=>'sharing_color',
			'type' => 'button_set',
			'compiler'=>true,
			'title' => __('Share Buttons Color', 'imic-framework-admin'), 
			'subtitle' => __('Choose the color scheme of the share button icons', 'imic-framework-admin'),
			'options' => array(
					'0' => __('Brand Colors','imic-framework-admin'),
					'1' => __('Theme Color','imic-framework-admin'),
					'2' => __('GrayScale','imic-framework-admin')
				),
			'default' => '0',
			),
		array(
			'id'       => 'share_icon',
			'type'     => 'checkbox',
			'required' => array('switch_sharing','equals','1'),
			'title'    => __('Social share options', 'imic-framework-demo'),
			'subtitle' => __('Click on the buttons to disable/enable share buttons', 'imic-framework-demo'),
			'options'  => array(
				'1' => 'Facebook',
				'2' => 'Twitter',
				'3' => 'Google',
				'4' => 'Tumblr',
				'5' => 'Pinterest',
				'6' => 'Reddit',
				'7' => 'Linkedin',
				'8' => 'Email',
				'9' => 'VKontakte'
			),
			'default' => array(
				'1' => '1',
				'2' => '1',
				'3' => '1',
				'4' => '1',
				'5' => '1',
				'6' => '1',
				'7' => '1',
				'8' => '1',
				'9' => '0'
			)
		),
		array(
			'id'       => 'share_post_types',
			'type'     => 'checkbox',
			'required' => array('switch_sharing','equals','1'),
			'title'    => __('Select share buttons for post types', 'imic-framework-admin'),
			'subtitle'     => __('Uncheck to disable for any type', 'imic-framework-admin'),
			'options'  => array(
				'1' => 'Posts',
				'2' => 'Pages',
				'3' => 'Events',
				'4' => 'Sermons',
				'5' => 'Causes',
				'6' => 'Gallery'
			),
			'default' => array(
				'1' => '1',
				'2' => '1',
				'3' => '1',
				'4' => '1',
				'5' => '1',
				'6' => '1'
			)
		),
		array(
            'id' => 'facebook_share_alt',
            'type' => 'text',
            'title' => __('Tooltip text for Facebook share icon', 'imic-framework-admin'),
            'subtitle' => __('Text for the Facebook share icon browser tooltip.', 'imic-framework-admin'),
            'default' => 'Share on Facebook'
        ),
		array(
            'id' => 'twitter_share_alt',
            'type' => 'text',
            'title' => __('Tooltip text for Twitter share icon', 'imic-framework-admin'),
            'subtitle' => __('Text for the Twitter share icon browser tooltip.', 'imic-framework-admin'),
            'default' => 'Tweet'
        ),
		array(
            'id' => 'google_share_alt',
            'type' => 'text',
            'title' => __('Tooltip text for Google Plus share icon', 'imic-framework-admin'),
            'subtitle' => __('Text for the Google Plus share icon browser tooltip.', 'imic-framework-admin'),
            'default' => 'Share on Google+'
        ),
		array(
            'id' => 'tumblr_share_alt',
            'type' => 'text',
            'title' => __('Tooltip text for Tumblr share icon', 'imic-framework-admin'),
            'subtitle' => __('Text for the Tumblr share icon browser tooltip.', 'imic-framework-admin'),
            'default' => 'Post to Tumblr'
        ),
		array(
            'id' => 'pinterest_share_alt',
            'type' => 'text',
            'title' => __('Tooltip text for Pinterest share icon', 'imic-framework-admin'),
            'subtitle' => __('Text for the Pinterest share icon browser tooltip.', 'imic-framework-admin'),
            'default' => 'Pin it'
        ),
		array(
            'id' => 'reddit_share_alt',
            'type' => 'text',
            'title' => __('Tooltip text for Reddit share icon', 'imic-framework-admin'),
            'subtitle' => __('Text for the Reddit share icon browser tooltip.', 'imic-framework-admin'),
            'default' => 'Submit to Reddit'
        ),
		array(
            'id' => 'linkedin_share_alt',
            'type' => 'text',
            'title' => __('Tooltip text for Linkedin share icon', 'imic-framework-admin'),
            'subtitle' => __('Text for the Linkedin share icon browser tooltip.', 'imic-framework-admin'),
            'default' => 'Share on Linkedin'
        ),
		array(
            'id' => 'email_share_alt',
            'type' => 'text',
            'title' => __('Tooltip text for Email share icon', 'imic-framework-admin'),
            'subtitle' => __('Text for the Email share icon browser tooltip.', 'imic-framework-admin'),
            'default' => 'Email'
        ),
		array(
            'id' => 'vk_share_alt',
            'type' => 'text',
            'title' => __('Tooltip text for vk share icon', 'imic-framework-admin'),
            'subtitle' => __('Text for the vk share icon browser tooltip.', 'imic-framework-admin'),
            'default' => 'Share on vk'
        ),
	)
);
$this->sections[] = array(
    'icon' => 'el-icon-zoom-in',
    'title' => __('Lightbox Options', 'imic-framework-admin'),
    'fields' => array(
        array(
            'id' => 'switch_lightbox',
            'type' => 'button_set',
            'title' => __('Lightbox Plugin', 'imic-framework-admin'),
            'subtitle' => __('Choose the plugin for the Lightbox Popup for theme.', 'imic-framework-admin'	
			),
			'options' => array(
				'0' => __('PrettyPhoto','imic-framework-admin'),
				'1' => __('Magnific Popup','imic-framework-admin')
			),
            "default" => 0,
       	),
		array(
			'id'       => 'prettyphoto_theme',
			'type'     => 'select',
			'required' => array('switch_lightbox','equals','0'),
			'title'    => __('Theme Style', 'imic-framework-admin'), 
			'desc'     => __('Select style for the prettyPhoto Lightbox', 'imic-framework-admin'),
			'options'  => array(
				'pp_default' => __('Default','imic-framework-admin'),
				'light_rounded' => __('Light Rounded','imic-framework-admin'),
				'dark_rounded' => __('Dark Rounded','imic-framework-admin'),
				'light_square' => __('Light Square','imic-framework-admin'),
				'dark_square' => __('Dark Square','imic-framework-admin'),
				'facebook' => __('Facebook','imic-framework-admin'),
			),
			'default'  => 'pp_default',
		),
		array(
			'id' => 'prettyphoto_opacity',
			'required' => array('switch_lightbox','equals','0'),
			'type' => 'slider',
			'title' => __('Overlay Opacity', 'imic-framework-demo'),
			'desc' => __('Enter values between 0.1 to 1. Default is 0.5', 'imic-framework-demo'),
			"default" => .5,
			"min" => 0,
			"step" => .1,
			"max" => 1,
			'resolution' => 0.1,
			'display_value' => 'text'
		),
        array(
            'id' => 'prettyphoto_title',
			'required' => array('switch_lightbox','equals','0'),
            'type' => 'button_set',
            'title' => __('Show Image Title', 'imic-framework-admin'),
			'options' => array(
				0 => __('Yes','imic-framework-admin'),
				1 => __('No','imic-framework-admin')
			),
            "default" => 0,
       	),
        array(
            'id' => 'prettyphoto_opt_resize',
			'required' => array('switch_lightbox','equals','0'),
            'type' => 'button_set',
            'title' => __('Allow Image Resize', 'imic-framework-admin'),
			'options' => array(
				true => __('Yes','imic-framework-admin'),
				false => __('No','imic-framework-admin')
			),
            "default" => true,
       	),
	)
);
$this->sections[] = array(
    'icon' => 'el-icon-podcast',
    'title' => __('Podcast Options', 'imic-framework-admin'),
    'fields' => array(
		array(
            'id' => 'podcast_title',
            'type' => 'text',
            'title' => __('Podcast Title', 'imic-framework-admin'),
            'placeholder' => 'e.g. '. get_bloginfo('name').''
        ),
		array(
            'id' => 'podcast_description',
            'type' => 'text',
            'title' => __('Podcast Description', 'imic-framework-admin'),
            'placeholder' => 'e.g. '. get_bloginfo('description').''
        ),
		array(
            'id' => 'podcast_website_url',
            'type' => 'text',
            'title' => __('Website Link', 'imic-framework-admin'),
            'placeholder' => 'e.g. '. home_url().''
        ),
		array(
            'id' => 'podcast_language',
            'type' => 'text',
            'title' => __('Language', 'imic-framework-admin'),
            'placeholder' => 'e.g. '.get_bloginfo('language').''
        ),
		array(
            'id' => 'podcast_copyright',
            'type' => 'text',
            'title' => __('Copyright', 'imic-framework-admin'),
			'desc' => __('Copy "&copy;" to put a copyright symbol.','imic-framework-admin'),
            'placeholder' => 'e.g. Copyright &copy; ' . get_bloginfo('name').''
        ),
		array(
            'id' => 'podcast_webmaster_name',
            'type' => 'text',
            'title' => __('Webmaster Name', 'imic-framework-admin'),
            'placeholder' => 'e.g. Your name'
        ),
		array(
            'id' => 'podcast_webmaster_email',
            'type' => 'text',
            'title' => __('Webmaster Email', 'imic-framework-admin'),
            'placeholder' => 'e.g. ' . get_bloginfo('admin_email').''
        ),
		array(
            'id' => 'podcast_itunes_author',
            'type' => 'text',
            'title' => __('Author', 'imic-framework-admin'),
			'desc' => __('This will display at the "Artist" in the iTunes Store.','imic-framework-admin'),
            'placeholder' => 'e.g. Primary Speaker or Church Name'
        ),
		array(
            'id' => 'podcast_itunes_subtitle',
            'type' => 'text',
            'title' => __('Subtitle', 'imic-framework-admin'),
			'desc' => __('Your subtitle should briefly tell the listener what they can expect to hear.','imic-framework-admin'),
            'placeholder' => 'e.g. Preaching and teaching audio from'
        ),
		array(
            'id' => 'podcast_itunes_summary',
            'type' => 'textarea',
            'title' => __('Summary', 'imic-framework-admin'),
			'desc' => __('Keep your Podcast Summary short, sweet and informative. Be sure to include a brief statement about your mission and in what region your audio content originates.','imic-framework-admin'),
            'placeholder' => 'e.g. Weekly teaching audio brought to you by'
        ),
		array(
            'id' => 'podcast_itunes_owner_name',
            'type' => 'text',
            'title' => __('Owner Name', 'imic-framework-admin'),
			'desc' => __('This should typically be the name of your Church.','imic-framework-admin'),
            'placeholder' => 'e.g. ' . get_bloginfo('name').''
        ),
		array(
            'id' => 'podcast_itunes_owner_email',
            'type' => 'text',
            'title' => __('Owner Email', 'imic-framework-admin'),
			'desc' => __('Use an email address that you dont mind being made public. If someone wants to contact you regarding your Podcast this is the address they will use.','imic-framework-admin'),
            'placeholder' => 'e.g. ' . get_bloginfo('admin_email').''
        ),
		array(
            'id' => 'podcast_itunes_cover_image',
            'type' => 'media',
            'url' => true,
            'title' => __('Cover Image', 'imic-framework-admin'),
			'desc' => __('This JPG will serve as the Podcast artwork in the iTunes Store. The image should be 1400px by 1400px','imic-framework-admin'),
            'default' => array('url' => $default_cover),
        ),
		array(
            'id' => 'podcast_itunes_top_category',
            'type' => 'text',
            'title' => __('Top Category', 'imic-framework-admin'),
			'desc' => __('Choose the appropriate top-level category for your Podcast listing in iTunes. <a href="http://www.apple.com/itunes/podcasts/specs.html#submitting" target="_blank">Reference</a>','imic-framework-admin'),
			'default' => 'Religion & Spirituality'
        ),
		array(
            'id' => 'podcast_itunes_sub_category',
            'type' => 'text',
            'title' => __('Sub Category', 'imic-framework-admin'),
			'desc' => __('Choose the appropriate sub category for your Podcast listing in iTunes. <a href="http://www.apple.com/itunes/podcasts/specs.html#submitting" target="_blank">Reference</a>','imic-framework-admin'),
			'default' => 'Christianity'
        ),
		array(
            'id' => 'podcast_itunes_feed_url',
            'type' => 'text',
            'title' => __('Feed URL', 'imic-framework-admin'),
			'desc' => __('This is your Feed URL to submit to iTunes','imic-framework-admin'),
			'default' => ''.home_url('/').'feed/?post_type=sermons',
			'readonly' => true
        ),
		array(
			'id'   => 'info_normal',
			'type' => 'info',
			'desc' => 'Use the <a href="http://www.feedvalidator.org/check.cgi?url='.home_url('/').'feed/?post_type=sermons" target="_blank">Feed Validator</a> to diagnose and fix any problems before submitting your Podcast to iTunes.
						<p>Once your Podcast Settings are complete and your Sermons are ready, its time to <a href="https://podcastsconnect.apple.com" target="_blank">Submit Your Podcast</a> to the iTunes Store! Check <a href="http://www.apple.com/itunes/podcasts/specs.html#submitting" target="_blabk">how to submit your podcast</a></p>
						<p>Alternatively, if you want to track your Podcast subscribers, simply pass the Podcast Feed URL above through <a href="http://feedburner.google.com/" target="_blank">FeedBurner</a>. FeedBurner will then give you a new URL to submit to iTunes instead.</p>
						<p>Please read the <a href="http://www.apple.com/itunes/podcasts/creatorfaq.html" target="_blank">iTunes FAQ for Podcast Makers</a> for more information.</p>'
		)
	)
);
$this->sections[] = array(
    'icon' => 'el-icon-calendar',
    'title' => __('Calendar Options', 'imic-framework-admin'),
    'fields' => array(
		array(
		'id'=>'calendar_header_view',
		'type' => 'image_select',
		'compiler'=>true,
		'title' => __('Calendar Header View','imic-framework-admin'), 
		'subtitle' => __('Select the view for your calendar header', 'imic-framework-admin'),
			'options' => array(
				1 => array('title' => '', 'img' => get_template_directory_uri().'/images/calendarheaderLayout/header-1.jpg'),
				2 => array('title' => '', 'img' => get_template_directory_uri().'/images/calendarheaderLayout/header-2.jpg'),
				),
		'default' => 1
		),
		array(
            'id' => 'calendar_event_limit',
            'type' => 'text',	
            'title' => __('Limit of Events', 'imic-framework-admin'),
            'desc' => __('Enter a number to limit number of events to show maximum in a single day block of calendar and remaining in a small popover(Default is 4)', 'imic-framework-admin'),
			'default' => '4',
        ),
        array(
            'id' => 'calendar_month_name',
            'type' => 'textarea',	
			'rows' => 2,
            'title' => __('Calendar Month Name', 'imic-framework-admin'),
            'desc' => __('Insert month name in local language by comma seperated to display on event calender like: January,February,March,April,May,June,July,August,September,October,November,December', 'imic-framework-admin'),
			'default' => 'January,February,March,April,May,June,July,August,September,October,November,December',
        ),
		array(
            'id' => 'calendar_month_name_short',
            'type' => 'textarea',	
			'rows' => 2,
            'title' => __('Calendar Month Name Short', 'imic-framework-admin'),
            'desc' => __('Insert month name short in local language by comma seperated to display on event calender like: Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec', 'imic-framework-admin'),
			'default' => 'Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec',
        ),
		array(
            'id' => 'calendar_day_name',
            'type' => 'textarea',
			'rows' => 2,	
            'title' => __('Calendar Day Name', 'imic-framework-admin'),
            'desc' => __('Insert day name in local language by comma seperated to display on event calender like: Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday', 'imic-framework-admin'),
			'default' => 'Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
        ),
		array(
            'id' => 'calendar_day_name_short',
            'type' => 'textarea',	
			'rows' => 2,
            'title' => __('Calendar Day Name Short', 'imic-framework-admin'),
            'desc' => __('Insert day name short in local language by comma seperated to display on event calender like: Sun,Mon,Tue,Wed,Thu,Fri,Sat', 'imic-framework-admin'),
			'default' => 'Sun,Mon,Tue,Wed,Thu,Fri,Sat',
        ),
		array(
            'id' => 'calendar_today',
            'type' => 'text',	
            'title' => __('Heading Today', 'imic-framework-admin'),
            'desc' => __('Translate Calendar Heading for Today Button', 'imic-framework-admin'),
			'default' => 'Today',
        ),
		array(
            'id' => 'calendar_month',
            'type' => 'text',	
            'title' => __('Heading Month', 'imic-framework-admin'),
            'desc' => __('Translate Calendar Heading for Month Button', 'imic-framework-admin'),
			'default' => 'Month',
        ),
		array(
            'id' => 'calendar_week',
            'type' => 'text',	
            'title' => __('Heading Week', 'imic-framework-admin'),
            'desc' => __('Translate Calendar Heading for Week Button', 'imic-framework-admin'),
			'default' => 'Week',
        ),
		array(
            'id' => 'calendar_day',
            'type' => 'text',	
            'title' => __('Heading Day', 'imic-framework-admin'),
            'desc' => __('Translate Calendar Heading for Day Button', 'imic-framework-admin'),
			'default' => 'Day',
        ),
		array(
			'id'       => 'event_feeds',
			'type'     => 'checkbox',
			'title'    => __('Show WP Events', 'imic-framework-admin'),
			'desc'     => __('Check if you want to show your Wordpress Events in Calendar.', 'imic-framework-admin'),
			'default'  => '1'// 1 = on | 0 = off
		),
		array(
            'id' => 'google_feed_key',
            'type' => 'text',	
            'title' => __('Google Calendar API Key', 'imic-framework-admin'),
            'desc' => __('Enter Google Calendar Feed API Key. <a href="http://support.imithemes.com/forums/topic/setting-up-google-calendar-api-for-events-calendar/" target="_blank">See instructions</a>', 'imic-framework-admin'),
			'default' => '',
        ),
		array(
            'id' => 'google_feed_id',
            'type' => 'text',	
            'title' => __('Google Calendar ID', 'imic-framework-admin'),
			'subtitle' => __('You can specify individual calendar IDs for each calendar using the calendar shortcode.', 'imic-framework-admin'),
            'desc' => __('Enter your Google Calendar ID. <a href="http://support.imithemes.com/forums/topic/setting-up-google-calendar-api-for-events-calendar/" target="_blank">See instructions</a>', 'imic-framework-admin'),
			'default' => '',
        ),
		array(
			'id'=>'event_default_color',
			'type' => 'color',
			'transparent' => false,
			'title' => __('Event Color', 'imic-framework-admin'), 
			'subtitle' => __('Pick a default bg color for events blocks over Calendar.', 'imic-framework-admin'),
			'validate' => 'color',
			'default' => ''
			),
			array(
			'id'=>'recurring_event_color',
			'type' => 'color',
			'transparent' => false,
			'title' => __('Recurring Event Color', 'imic-framework-admin'), 
			'subtitle' => __('Pick a bg color for recurring events blocks over calendar.', 'imic-framework-admin'),
			'validate' => 'color',
			'default' => ''
			),
    ),
);
$this->sections[] = array(
    'icon' => 'el-icon-bullhorn',
    'title' => __('Event Options', 'imic-framework-admin'),
    'fields' => array(
        /*array(
            'id' => 'event_admin_time_format',
            'type' => 'switch',
            'title' => __('Event Time format', 'imic-framework-admin'),
            'subtitle' => __('Event time format while creating/updating event.', 'imic-framework-admin'),
			'on' => '24 Hours',
			'off' => '12 Hours',
            "default" => '1',
        ),*/
        array(
            'id' => 'recurring_icon',
            'type' => 'switch',
            'title' => __('Show Recurring Icon', 'imic-framework-admin'),
            'subtitle' => __('Show/Hide recurring icon which comes next to event name in listing/grid of events.', 'imic-framework-admin'),
			'on' => 'Yes',
			'off' => 'No',
            "default" => 1,
        ),
        array(
            'id' => 'event_google_icon',
            'type' => 'switch',
            'title' => __('Show Google Icon', 'imic-framework-admin'),
            'subtitle' => __('Show/Hide Google icon which comes before the title of events in listing, grid, timeline layouts.', 'imic-framework-admin'),
			'on' => 'Yes',
			'off' => 'No',
            "default" => 1,
        ),
		array(
            'id' => 'event_google_open_link',
            'type' => 'switch',
            'title' => __('Open Google Events', 'imic-framework-admin'),
            'subtitle' => __('Open google event links in new tab/window.', 'imic-framework-admin'),
			'on' => 'Yes',
			'off' => 'No',
            "default" => 1,
        ),
		array(
            'id' => 'countdown_timer',
            'type' => 'select',
            'title' => __('Events Display Time', 'imic-framework-admin'),
            'subtitle' => __('Select till End Time/Start Time', 'imic-framework-admin'),
            'options' => array('0' => 'Start Time', '1' => 'End Time'),
            'default' => '0',
        ),
		/*array(
          'id'=>'event_show_till_time',
			'type' => 'button_set',
			'compiler'=>true,
			'title' => __('Show Event Till Time', 'imic-framework-admin'), 
			'subtitle' => __('Show event till start or end time', 'imic-framework-admin'),
			'options' => array(
					'0' => __('Start Time','imic-framework-admin'),
					'1' => __('End Time','imic-framework-admin'),
				),
			'default' => '0',
			),
		array(
          'id'=>'event_show_till_date',
			'type' => 'button_set',
			'compiler'=>true,
			'title' => __('Show Event Till Date', 'imic-framework-admin'), 
			'subtitle' => __('Show event till start or end date', 'imic-framework-admin'),
			'options' => array(
					'0' => __('Start Date','imic-framework-admin'),
					'1' => __('End Date','imic-framework-admin'),
				),
			'default' => '0',
			),	*/
		array(
          'id'=>'event_tm_opt',
			'type' => 'button_set',
			'compiler'=>true,
			'title' => __('Show Event Time', 'imic-framework-admin'), 
			'subtitle' => __('Show event time of events in listing, grid, calender layouts.', 'imic-framework-admin'),
			'options' => array(
					'0' => __('Start Time','imic-framework-admin'),
					'1' => __('End Time','imic-framework-admin'),
					'2' => __('Both','imic-framework-admin')
				),
			'default' => '0',
			),
		array(
          'id'=>'event_dt_opt',
			'type' => 'button_set',
			'compiler'=>true,
			'title' => __('Show Event Date', 'imic-framework-admin'), 
			'subtitle' => __('Show event date of events in listing, grid, calender layouts.', 'imic-framework-admin'),
			'options' => array(
					'0' => __('Start Date','imic-framework-admin'),
					'1' => __('End Date','imic-framework-admin'),
					'2' => __('Both','imic-framework-admin')
				),
			'default' => '0',
			),
	),
);
/*$this->sections[] = array(
    'icon' => 'el-icon-envelope',
    'title' => __('Email Options', 'imic-framework-admin'),
    'fields' => array(
	array(
		'id' => 'event_mail_owner_ctn',
		'type' => 'textarea',
		'title' => __('Content Mail Format For Event Owner', 'imic-framework-admin'),
		'subtitle' => __('Content of mail sent for event registration owner.', 'imic-framework-admin'),
		'default' => '',
		'placeholder'=>'Use custom mail format here using below sample format.',
		'description'=>'<pre>[Owner_name] has been registered for [event_name].</br>Event: [event_name]</br>Event Date: [event_date]</br>Name: [full_name]</br>Email: [email_id]<br>Phone: [phone]</br>You can contact via email, [admin_mail]
		</pre>',
	),
	array(
		'id' => 'event_mail_user_ctn',
		'type' => 'textarea',
		'title' => __('Content Mail Format For User', 'imic-framework-admin'),
		'subtitle' => __('Content of mail sent for event registration user.', 'imic-framework-admin'),
		"default" => '',
		'placeholder'=>'Use custom mail format here using below sample format.',
		'description'=>'<pre>[Owner_name] has been registered for [event_name].</br>Event: [event_name]</br>Event Date: [event_date]</br>Name: [full_name]</br>Email: [email_id]<br>Phone: [phone]</br>You can contact via email, [admin_mail]
		</pre>',
	),
	),
);*/
$this->sections[] = array(
    'icon' => 'el-icon-user',
    'title' => __('Staff Options', 'imic-framework-admin'),
    'subtitle' => __('Staff Posts Options', 'imic-framework-admin'),
    'fields' => array(
        array(
            'id' => 'switch_staff_read_more',
            'type' => 'switch',
            'title' => __('Show Read More Button on Staff Listing', 'imic-framework-admin'),
            'subtitle' => __('Enable/Disable read more button link on staff listing shortcode or template."', 'imic-framework-admin'	
			),
            "default" => 0,
       	),
		 array(
			'id'=>'staff_read_more',
			'type' => 'button_set',
			'compiler'=>true,
			'required' => array('switch_staff_read_more','equals','1'),
			'title' => __('Read More Style', 'imic-framework-admin'), 
			'subtitle' => __('Choose the read more style', 'imic-framework-admin'),
			'options' => array(
					'0' => __('Button','imic-framework-admin'),
					'1' => __('Text Link','imic-framework-admin')
				),
			'default' => '0',
		),
		 array(
			'id'=>'staff_read_more_text',
			'type' => 'text',
			'compiler'=>true,
			'required' => array('switch_staff_read_more','equals','1'),
			'title' => __('Read More text', 'imic-framework-admin'), 
			'subtitle' => __('Enter button/link text for read more', 'imic-framework-admin'),
			'default' => 'Read more',
		),
	)
);
$this->sections[] = array(
    'icon' => 'el-icon-music',
    'title' => __('Sermon Options', 'imic-framework-admin'),
    'subtitle' => __('Sermon Posts Options', 'imic-framework-admin'),
    'fields' => array(
        array(
            'id' => 'switch_sermon_filters',
            'type' => 'switch',
            'title' => __('Show Sermons Filters', 'imic-framework-admin'),
            'subtitle' => __('Enable/Disable filters on sermons archive pages"', 'imic-framework-admin'),
            "default" => 1,
       	),
	)
);
$this->sections[] = array(
    'icon' => 'el-icon-edit',
    'title' => __('Custom CSS/JS', 'imic-framework-admin'),
    'fields' => array(
        array(
            'id' => 'custom_css',
            'type' => 'ace_editor',
            //'required' => array('layout','equals','1'),	
            'title' => __('CSS Code', 'imic-framework-admin'),
            'subtitle' => __('Paste your CSS code here.', 'imic-framework-admin'),
            'mode' => 'css',
            'theme' => 'monokai',
            'desc' => '',
            'default' => "#header{\nmargin: 0 auto;\n}"
        ),
        array(
            'id' => 'custom_js',
            'type' => 'ace_editor',
            //'required' => array('layout','equals','1'),	
            'title' => __('JS Code', 'imic-framework-admin'),
            'subtitle' => __('Paste your JS code here.', 'imic-framework-admin'),
            'mode' => 'javascript',
            'theme' => 'chrome',
            'desc' => '',
            'default' => "jQuery(document).ready(function(){\n\n});"
        )
    ),
);
$this->sections[] = array(
                'title' => __('Import / Export', 'imic-framework-admin'),
                'desc' => __('Import and Export your Theme Framework settings from file, text or URL.', 'imic-framework-admin'),
                'icon' => 'el-icon-refresh',
                'fields' => array(
                    array(
                        'id' => 'opt-import-export',
                        'type' => 'import_export',
                       'title' => __('Import Export','imic-framework-admin'),
                        'subtitle' => __('Save and restore your Theme options','imic-framework-admin'),
                        'full_width' => false,
                    ),
                ),
            ); 
                       if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon'      => 'el-icon-book',
                    'title'     => __('Documentation', 'imic-framework-admin'),
                    'content'   => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }
        public function setHelpTabs() {
            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            /* $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'imic-framework-admin'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'imic-framework-admin')
            );
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'imic-framework-admin'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'imic-framework-admin')
            );
            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'imic-framework-admin');
			*/
        }
        /**
          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
         * */
        public function setArguments() {
            $theme = wp_get_theme(); // For use with some settings. Not necessary.
            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'imic_options',            // This is where your data is stored in the database and also becomes your global variable name.
				'disable_tracking' => true,
                'display_name'      => $theme->get('Name'),     // Name that appears at the top of your panel
                'display_version'   => $theme->get('Version'),  // Version that appears at the top of your panel
                'menu_type'         => 'menu',                  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => true,                    // Show the sections below the admin menu item or not
                'menu_title'        => __('Theme Options', 'imic-framework-admin'),
                'page_title'        => __('Theme Options', 'imic-framework-admin'),
                
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '', // Must be defined to add google fonts to the typography module
                
                'async_typography'  => false,                    // Use a asynchronous font on the front end or font string
                'admin_bar'         => true,                    // Show the panel pages on the admin bar
                'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                    // Show the time the page took to load, etc
                'customizer'        => true,                    // Enable basic customizer support
                
                // OPTIONAL -> Give you extra features
                'page_priority'     => '57',                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'themes.php',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => '',                      // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'icon-themes',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => '_options',              // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => false,                   // Shows the Import/Export panel when not used as a field.
                
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                'footer_credit'     => __('Made with love by <a href="http://www.imithemes.com">imithemes</a>', 'imic-framework-admin'),                   // Disable the footer credit of Redux. Please leave if you can help it.
                
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE
                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );
            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/imithemes',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://twitter.com/imithemes',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );
            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace('-', '_', $this->args['opt_name']);
                }
                $this->args['intro_text'] = sprintf(__('<p>Did you know that we sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'imic-framework-admin'), $v);
            } else {
                //$this->args['intro_text'] = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'imic-framework-admin');
            }
            // Add content after the form.
            //$this->args['footer_text'] = __('<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'imic-framework-admin');
        }
    }
    
    global $reduxConfig;
    $reduxConfig = new Redux_Framework_sample_config();
}
/**
  Custom function for the callback referenced above
 */
if (!function_exists('redux_my_custom_field')):
    function redux_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;
/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('redux_validate_callback_function')):
    function redux_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';
        /*
          do your validation
          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */
        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;
