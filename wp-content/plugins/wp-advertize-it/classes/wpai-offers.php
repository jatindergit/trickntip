<?php 

if (!class_exists('WPAI_Offer')) {

    /**
     * Handles plugin settings and user profile meta fields
     */
    class WPAI_Offers extends WPAI_Module
    {
        //protected $var;        

        const REQUIRED_CAPABILITY = 'administrator';

        const PREFIX = 'wpai_';
        /*
         * General methods
         */

        /**
         * Constructor
         *
         * @mvc Controller
         */
        protected function __construct()
        {
            $this->register_hook_callbacks();
        }

        /**
         * Public setter for protected variables
         *
         * Updates settings outside of the Settings API or other subsystems
         *
         * @mvc Controller
         *
         * @param string $variable
         * @param array $value This will be merged with WPAI_Settings->settings, so it should mimic the structure of the WPAI_Settings::$default_settings. It only needs the contain the values that will change, though. See WordPress_Advertize_It->upgrade() for an example.
         */
        public function __set($variable, $value)
        {
            // Note: WPAI_Module::__set() is automatically called before this

       
        }
        
        /**
         * Prepares site to use the plugin during activation
         *
         * @mvc Controller
         *
         * @param bool $network_wide
         */
        public function activate($network_wide)
        {
        }
        
        /**
         * Rolls back activation procedures when de-activating the plugin
         *
         * @mvc Controller
         */
        public function deactivate()
        {
        }
        
        /**
         * Initializes variables
         *
         * @mvc Controller
         */
        public function init()
        {
        	
        }

        /**
         * Register callbacks for actions and filters
         *
         * @mvc Controller
         */
        public function register_hook_callbacks()
        {
            add_action('admin_menu', array($this, 'register_settings_pages'));
            add_action('init', array($this, 'init'));
            add_action('admin_init', array($this, 'register_settings'));
            
            add_action('wp_enqueue_scripts', __CLASS__ . '::load_resources');
            add_action('admin_enqueue_scripts', __CLASS__ . '::load_resources');
            
            //add_action('admin_head', __CLASS__ . '::add_base_tag');

            add_filter(
                'plugin_action_links_' . plugin_basename(dirname(__DIR__)) . '/bootstrap.php',
                __CLASS__ . '::add_plugin_action_links'
            );
            
            add_action('wp_ajax_get_blocks', 'WPAI_DB::wpai_get_blocks_callback',10,0);
            add_action('wp_ajax_save_block', 'WPAI_DB::wpai_save_block_callback',10,0);
            add_action('wp_ajax_create_block', 'WPAI_DB::wpai_create_block_callback',10,0);
            add_action('wp_ajax_delete_block', 'WPAI_DB::wpai_delete_block_callback',10,0);
            add_action('wp_ajax_get_placements', 'WPAI_DB::wpai_get_placements_callback',10,0);
            add_action('wp_ajax_save_placement', 'WPAI_DB::wpai_save_placement_callback',10,0);
            add_action('wp_ajax_create_placement', 'WPAI_DB::wpai_create_placement_callback',10,0);
            add_action('wp_ajax_delete_placement', 'WPAI_DB::wpai_delete_placement_callback',10,0);
            add_action('wp_ajax_delete_placement', 'WPAI_DB::wpai_delete_placement_callback',10,0);
            
        }
        
        /**
         * Executes the logic of upgrading from specific older versions of the plugin to the current version
         *
         * @mvc Model
         *
         * @param string $db_version
         */
        public function upgrade($db_version = 0)
        {
        	
        }
        
        /**
         * Checks that the object is in a correct state
         *
         * @mvc Model
         *
         * @param string $property An individual property to check, or 'all' to check all of them
         * @return bool
         */
        protected function is_valid($property = 'all')
        {
        	// Note: __set() calls validate_settings(), so settings are never invalid
        
        	return true;
        }
        
        /**
         * Registers settings sections, fields and settings
         *
         * @mvc Controller
         */
        public function register_settings()
        {}
        
        /**
         * Adds links to the plugin's action link section on the Plugins page
         *
         * @mvc Model
         *
         * @param array $links The links currently mapped to the plugin
         * @return array
         */
        public static function add_plugin_action_links($links)
        {
        	return $links;
        }
        
        /**
         * Adds pages to the Admin Panel menu
         *
         * @mvc Controller
         */
        public function register_settings_pages()
        {
            $settingsPage = add_menu_page(WPAI_NAME,
        			WPAI_NAME . '&nbsp;<i class="glyphicon glyphicon-signal"></i>', 
        			self::REQUIRED_CAPABILITY, 
        			'wpai_main', 
        			__CLASS__ . '::markup_offers_page');

            add_action( 'load-' . $settingsPage, array($this, 'load_bootstrap_css') );

        	/*add_submenu_page(
        	'wpai_main',
        	WPAI_NAME,
        	WPAI_NAME . '&nbsp;<i class="glyphicon glyphicon-signal"></i>',
        	self::REQUIRED_CAPABILITY,
        	'wpai_offers',
        	__CLASS__ . '::markup_offers_page'
        			);*/
        }

        public function load_bootstrap_css(){
            add_action( 'admin_enqueue_scripts', array($this, 'enqueue_bootstrap_css' ) );
        }

        public function enqueue_bootstrap_css()
        {
            wp_register_style(self::PREFIX . 'bootstrap-css',
            	'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
                array(),
                null);
            
            wp_enqueue_style(self::PREFIX . 'bootstrap-css');
        }

        /*public static function add_base_tag() {
        
			//$output="<base href='/wp422de/wp-admin/admin.php?page=wpai_offers'/>";//WPAI_PLUGIN_URL
			$output="<base href='/wp423de/wp-admin/admin.php?page=wpai_offers'/>";//WPAI_PLUGIN_URL

        	echo $output;
        
        }*/
        
        public static function load_resources()
        {
        	wp_register_script(
        			self::PREFIX . 'angularjs',
        			'https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular.min.js',
        			array('jquery'),
        			null,
        			true
        	);
        	
        	wp_register_script(
        			self::PREFIX . 'uirouter',
        			plugins_url('javascript/lib/angular-ui-router.min.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);
        	
        	wp_register_script(
        			self::PREFIX . 'ui-bootstrap',
        			plugins_url('javascript/lib/ui-bootstrap-tpls-2.2.0.min.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);
        	
        	wp_register_script(
        			self::PREFIX . 'blockService',
        			plugins_url('javascript/services/BlockService.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);
        	
        	wp_register_script(
        			self::PREFIX . 'placementService',
        			plugins_url('javascript/services/PlacementService.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);
        	
        	wp_register_script(
        			self::PREFIX . 'settingsService',
        			plugins_url('javascript/services/SettingsService.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);
        	
        	//wp_localize_script(self::PREFIX . 'settingsService', 'options',  WPAI_Settings::get_instance()->settings);
        	
        	wp_register_script(
        			self::PREFIX . 'HttpResponseService',
        			plugins_url('javascript/services/HttpResponseService.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);
        	
        	wp_register_script(
        			self::PREFIX . 'keepFocusDir',
        			plugins_url('javascript/directives/keep-focus.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);
        	
        	wp_register_script(
        			self::PREFIX . 'placementCtrl',
        			plugins_url('javascript/controllers/PlacementController.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);
        	
        	wp_register_script(
        			self::PREFIX . 'settingsCtrl',
        			plugins_url('javascript/controllers/SettingsController.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);
        	/*
        	wp_register_script(
        			self::PREFIX . 'tabsCtrl',
        			plugins_url('javascript/controllers/TabsController.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);
        	*/
        	wp_register_script(
        			self::PREFIX . 'blockCtrl',
        			plugins_url('javascript/controllers/BlockController.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);
        	
        	wp_register_script(
        			self::PREFIX . 'placementCtrl',
        			plugins_url('javascript/controllers/PlacementController.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);
        
        	wp_register_script(
        			self::PREFIX . 'compileDirective',
        			plugins_url('javascript/directives/compile.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);
        	
        	wp_register_script(
        			self::PREFIX . 'compileDropdownDirective',
        			plugins_url('javascript/directives/compileDropdown.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);
        	
        	wp_register_script(
        			self::PREFIX . 'app',
        			plugins_url('javascript/wpai-admin-ng.js', dirname(__FILE__)),
        			array(self::PREFIX . 'ui-bootstrap'),
        			null,
        			true
        	);
        	
        	wp_register_script(
        			self::PREFIX . 'validator-num-range',
        			plugins_url('javascript/directives/validator-num-range.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);
        	
        	wp_register_script(
        			self::PREFIX . 'validator-ip',
        			plugins_url('javascript/directives/validator-ip.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);
        	
        	/*wp_register_script(
        			self::PREFIX . 'postscribe',
        			plugins_url('javascript/lib/postscribe.min.js', dirname(__FILE__)),
        			array(),
        			null,
        			true
        	);*/

        	if (is_admin()) {
        		wp_enqueue_script(self::PREFIX . 'angularjs');
        		//wp_enqueue_script(self::PREFIX . 'ngroute');
        		wp_enqueue_script(self::PREFIX . 'uirouter');
        		wp_enqueue_script(self::PREFIX . 'ui-bootstrap');
        		wp_enqueue_script(self::PREFIX . 'app');
        		wp_enqueue_script(self::PREFIX . 'blockService');
        		wp_enqueue_script(self::PREFIX . 'placementService');
        		wp_enqueue_script(self::PREFIX . 'settingsService');
        		wp_enqueue_script(self::PREFIX . 'keepFocusDir');
        		wp_enqueue_script(self::PREFIX . 'compileDirective');
        		wp_enqueue_script(self::PREFIX . 'compileDropdownDirective');
        		wp_enqueue_script(self::PREFIX . 'HttpResponseService');
        		wp_enqueue_script(self::PREFIX . 'placementCtrl');
        		wp_enqueue_script(self::PREFIX . 'settingsCtrl');
        		wp_enqueue_script(self::PREFIX . 'blockCtrl');
        		wp_enqueue_script(self::PREFIX . 'tabsCtrl');
        		wp_enqueue_script(self::PREFIX . 'validator-num-range');
        		wp_enqueue_script(self::PREFIX . 'validator-ip');
        		//wp_enqueue_script(self::PREFIX . 'postscribe');
        	} else {
        	}
        }
        
        /**
         * Creates the markup for the Settings page
         *
         * @mvc Controller
         */
        public static function markup_offers_page()
        {
        	if (current_user_can(self::REQUIRED_CAPABILITY)) {
        		echo self::render_template('templates/page-offers.php');
        	} else {
        		wp_die('Access denied.');
        	}
        }
    }
}


 