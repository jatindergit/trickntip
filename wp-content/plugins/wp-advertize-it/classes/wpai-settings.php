<?php

if (!class_exists('WPAI_Settings')) {

    /**
     * Handles plugin settings and user profile meta fields
     */
    class WPAI_Settings extends WPAI_Module
    {
        protected $settings;
        protected static $default_settings;
        //protected static $defaultOptions;
        protected static $readable_properties = array('settings');
        protected static $writeable_properties = array('settings');

        const REQUIRED_CAPABILITY = 'administrator';

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

            if ($variable != 'settings') {
                return;
            }

            //$this->settings = self::validate_settings($value);
            //update_option('wpai_settings', $this->settings);
        }

        /**
         * Register callbacks for actions and filters
         *
         * @mvc Controller
         */
        public function register_hook_callbacks()
        {
            add_action('init', array($this, 'init'));         
            
            add_action('wp_ajax_get_settings',  array($this, 'get_master_settings_callback'),10,0);
            add_action('wp_ajax_get_tags',  array($this, 'get_tags_callback'),10,0);
            add_action('wp_ajax_get_cats',  array($this, 'get_cats_callback'),10,0);
            add_action('wp_ajax_get_authors',  array($this, 'get_authors_callback'),10,0);
            add_action('wp_ajax_get_languages',  array($this, 'get_languages_callback'),10,0);
            add_action('wp_ajax_get_post_types',  array($this, 'get_post_types_callback'),10,0);
            add_action('wp_ajax_get_post_formats',  array($this, 'get_post_formats_callback'),10,0);
            add_action('wp_ajax_get_placement_settings', array($this, 'get_placement_settings_callback'),10,0);
            
            add_action('wp_ajax_save_settings',  'WPAI_DB::wpai_save_settings_callback', 10,0);
            add_action('wp_ajax_save_placement_settings',  'WPAI_DB::wpai_save_placement_settings_callback', 10,0);
            add_action('wp_ajax_delete_placement_settings',  'WPAI_DB::wpai_delete_placement_settings_callback', 10,0);

            add_filter(
                'plugin_action_links_' . plugin_basename(dirname(__DIR__)) . '/bootstrap.php',
                __CLASS__ . '::add_plugin_action_links'
            );
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
            
            
            $alloptions = get_option('wpai_settings',false,false);
             
            if (version_compare($alloptions['db-version'], '0.9.7', '>')) {
            	
            	self::$default_settings = self::get_default_settings();
            	$this->settings = self::get_settings();
            
            }else{
            	
            	self::$default_settings = self::get_default_settings_097();
            	
            	$this->settings = shortcode_atts(
            			self::$default_settings,
            			get_option('wpai_settings', array())
            			);
            	
            	return $settings;
            }
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
        	//error_log( "<!-- WPAIVERSION ". $db_version . "-->");
            if( version_compare( $db_version, '0.9.4', '<' ) )
            {
                if (isset($this->settings['blocks'])) {
                    $blocks = $this->settings['blocks'];
                    foreach ($blocks as $i => $block) {
                        if (!is_array($block)) {
                            $block_name = 'Ad Block ' . ($i + 1);
                            $this->settings['blocks'][$i]=array();
                            $this->settings['blocks'][$i]['name']=$block_name;
                            $this->settings['blocks'][$i]['text']=$block;
                        }
                    }
                }
                
                update_option('wpai_settings', $this->settings);
            }
            
            if( version_compare( $db_version, '0.9.8', '<' ) )
            {
            	global $wpdb;
            	
            	$this->create_tables();
            	
            	$old_settings  = get_option('wpai_settings');
            	
            	//migrate blocks & placements
            	$blocks = $old_settings['blocks'];            	
            	$placements = $old_settings['placements'];
            	
            	if (!$placements){
            		$placements = array();
            	}
            	
            	if (!$blocks){
            		$blocks = array();
            	}

                $default_settings = $this->get_default_settings();
                foreach ($default_settings['placements'] as $plind => $plval){
            		if ($placements[$plind]==""){
            			$placements [$plind] = array();
            		}
            	}
            	
            	$allplacements = $placements;
            	
            	$table_name = $wpdb->prefix . "wpai_blocks";
            	$table_name_placements = $wpdb->prefix . "wpai_placements";
            	$table_name_options = $wpdb->prefix . "wpai_settings";
            	
            	foreach ($blocks as $i => $block) {
            		
            		$dbblock = array();
            		$dbblock['name'] = $block['name'];
            		$dbblock['default_ads'] = $block['text'];
            		$dbblock['promo_duration'] = 0;
            		$dbblock['promo_every'] = 0;
            		$dbblock['promo_only_not_sold'] = 0;
            		
            		$rows_affected = $wpdb->insert($table_name , $dbblock);
            		
            		$id = $wpdb->insert_id;
            		
            		foreach ($placements as $p => $placement) {
            			if ($placement == $i){
            				$dbplacement = array();
            				$dbplacement['name'] = $p;
            				$dbplacement['blockid'] = $id;
            				
            				$rows_affected = $wpdb->insert($table_name_placements , $dbplacement);
            				
            				unset ($allplacements[$p]);
            				
            				//break; //only one block per placement possible
            			}
            		}
            		
            	}
            	
            	foreach ($allplacements as $p => $placement) {
            		$dbplacement = array();
            		$dbplacement['name'] = $p;
            		 
            		$rows_affected = $wpdb->insert($table_name_placements , $dbplacement);
            	}
            	
            	//migrate options
            	$options = $old_settings['options'];
            	
            	if (!$options){
                    $default_settings1 = $this->get_default_settings();
                    $options = $default_settings1['options'];
            	}
            	
            	foreach ($options as $o => $option) {
            		$dboption = array();
            		$dboption['name'] = $o;
                    $default_settings2 = $this->get_default_settings();
                    $dboption['value'] = ($option || $option===0) ?$option: $default_settings2['options'][$o];
            		
            		if (is_array($dboption['value'])){
            			$dboption['value'] = implode(",", $dboption['value']);
            		
            		}else if ($dboption['value'] instanceof stdClass){
            		
            			$dboption['value'] = $dboption['value']->value;
            		}
            		
            		$dboption['placementid'] = 0;
            		
            		$rows_affected = $wpdb->insert($table_name_options, $dboption);
            	}
            }
            else {
                $this->create_tables();
            }
        }
        
        /**
         * to be called by upgrades if DB changed (adds delta only)
         */
        protected function create_tables(){
        	global $wpdb;
        	
        	$blocks_table_name = $wpdb->prefix . "wpai_blocks";
        	$blocks_table_sql = 
        		"CREATE TABLE $blocks_table_name (
				  id INT NOT NULL AUTO_INCREMENT,
				  name VARCHAR(200) DEFAULT NULL,
				  default_ads TEXT,
				  sold_ads TEXT,
				  promo TEXT,
				  promo_duration INT DEFAULT NULL,
				  promo_every INT DEFAULT NULL,
				  promo_only_not_sold TINYINT(4) DEFAULT 1,
				  rotate_ads TINYINT(4) DEFAULT 0,
				  rotation_duration INT DEFAULT 10,
				  promotion TINYINT(4) DEFAULT 0,
				  alignment VARCHAR(20) DEFAULT 'default',
				  style VARCHAR(200) DEFAULT NULL,
				  UNIQUE KEY id_UNIQUE (id)
				);";
        	
        	$placements_table_name = $wpdb->prefix . "wpai_placements";
        	$placements_table_sql =
        	"CREATE TABLE $placements_table_name (
        		id INT NOT NULL AUTO_INCREMENT,
			  	name VARCHAR(100) DEFAULT NULL,
			  	blockid INT DEFAULT 0,
			  	type VARCHAR(45) DEFAULT NULL,
                priority INT DEFAULT 10,
        		UNIQUE KEY id_UNIQUE (id));";
        	
        	$settings_table_name = $wpdb->prefix . "wpai_settings";
        	$settings_table_sql =
        	"CREATE TABLE $settings_table_name (
        		id INT NOT NULL AUTO_INCREMENT,
				name VARCHAR(100) NOT NULL,
				value TEXT,
				placementid INT NOT NULL DEFAULT 0,
        		UNIQUE KEY id_UNIQUE (id));";
        	
        	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        	dbDelta( $blocks_table_sql );
        	dbDelta( $placements_table_sql );
        	dbDelta( $settings_table_sql );
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


        /*
         * Plugin Settings
         */

        /**
         * Establishes initial values for all settings
         *
         * @mvc Model
         *
         * @return array
         */
        public static function get_default_settings()
        {
            $blocks = array();
            
            $placements = array(
            		'homepage-below-title' => array('txt'=>__('Home page below title','wpailang'),'val'=>''),
            		'post-below-title' => array('txt'=>__('Posts below title','wpailang'),'val'=>''),
            		'post-below-content' => array('txt'=>__('Posts below content','wpailang'),'val'=>''),
            		'post-below-comments' => array('txt'=>__('Posts below comments','wpailang'),'val'=>''),
            		'page-below-title' => array('txt'=>__('Pages below title','wpailang'),'val'=>''),
            		'page-below-content' => array('txt'=>__('Pages below content','wpailang'),'val'=>''),
            		'page-below-comments' => array('txt'=>__('Pages below comments','wpailang'),'val'=>''),
            		'middle-of-post' => array('txt'=>__('Middle of post','wpailang'),'val'=>''),
            		'middle-of-page' => array('txt'=>__('Middle of page','wpailang'),'val'=>''),
            		'before-last-post-paragraph' => array('txt'=>__('Before last post paragraph','wpailang'),'val'=>''),
            		'after-first-page-paragraph' => array('txt'=>__('After first page paragraph','wpailang'),'val'=>''),
            		'before-last-page-paragraph' => array('txt'=>__('Before last page paragraph','wpailang'),'val'=>''),
            		'after-first-post-paragraph' => array('txt'=>__('After first post paragraph','wpailang'),'val'=>''),
            		'between-posts' => array('txt'=>__('Between posts','wpailang'),'val'=>''),
            		'above-everything' => array('txt'=>__('Above everything','wpailang'),'val'=>''),
            		'all-below-footer' => array('txt'=>__('Below footer','wpailang'),'val'=>'')
            		
            );
            
            $masterSettings = array( 'options'=>array(
            		'hide-editor-button'=>array('txt'=> __('Hide button in visual editor','wpailang'),'type'=>'checkbox','val'=>false),
            		'suppress-on-posts'=>array('txt'=>__('Suppress ads on posts','wpailang'),'type'=>'checkbox','val'=>false),
            		'suppress-on-pages'=>array('txt'=>__('Suppress ads on pages','wpailang'),'type'=>'checkbox','val'=>false),
            		'suppress-on-attachment'=>array('txt'=>__('Suppress ads on attachment page','wpailang'),'type'=>'checkbox','val'=>false),
            		'suppress-on-category'=>array('txt'=>__('Suppress ads on category page','wpailang'),'type'=>'checkbox','val'=>false),
            		'suppress-on-tag'=>array('txt'=>__('Suppress ads on tag page','wpailang'),'type'=>'checkbox','val'=>false),
            		'suppress-on-home'=>array('txt'=>__('Suppress ads on home page','wpailang'),'type'=>'checkbox','val'=>false),
            		'suppress-on-front'=>array('txt'=>__('Suppress ads on front page','wpailang'),'type'=>'checkbox','val'=>false),
            		'suppress-on-archive'=>array('txt'=>__('Suppress ads on archive page','wpailang'),'type'=>'checkbox','val'=>false),
            		'suppress-on-error'=>array('txt'=>__('Suppress ads on error page','wpailang'),'type'=>'checkbox','val'=>false),
            		'suppress-on-author'=>array('txt'=>__('Suppress ads on author page','wpailang'),'type'=>'checkbox','val'=>false),
            		'suppress-on-logged-in'=>array('txt'=>__('Suppress ads for logged in users','wpailang'),'type'=>'checkbox','val'=>false),
            		'suppress-on-wptouch'=>array('txt'=>__('Suppress ads on WPtouch mobile site','wpailang'),'type'=>'checkbox','val'=>false),
            		'suppress-post-id'=>array('txt'=>__('Suppress ads for specific post/page IDs','wpailang'),'type'=>'text','placeholder'=>'e.g. 32,9-19,33','attrs'=>'num-range','val'=>''),
            		'suppress-category'=>array('txt'=>__('Suppress ads for specific categories','wpailang'),'type'=>'array','src'=>'cats','val'=>''),
            		'suppress-tag'=>array('txt'=>__('Suppress ads for specific tags','wpailang'),'type'=>'array','src'=>'tags','val'=>''),
            		'suppress-user'=>array('txt'=>__('Suppress ads for specific authors','wpailang'),'type'=>'array','src'=>'authors','val'=>''),
            		'suppress-format'=>array('txt'=>__('Suppress ads for specific post formats','wpailang'),'type'=>'array','src'=>'post_formats','val'=>''),
            		'suppress-post-type'=>array('txt'=>__('Suppress ads for specific post types','wpailang'),'type'=>'array','src'=>'post_types','val'=>''),
            		'suppress-language'=>array('txt'=>__('Suppress ads for specific languages','wpailang'),'type'=>'array','src'=>'languages','val'=>''),
            		'suppress-url'=>array('txt'=>__('Suppress ads for specific URL paths','wpailang'),'type'=>'text','val'=>''),
            		'suppress-referrer'=>array('txt'=>__('Suppress ads for specific referrers','wpailang'),'type'=>'text','val'=>''),
            		'suppress-ipaddress'=>array('txt'=>__('Suppress ads for specific IP addresses','wpailang'),'type'=>'text','placeholder'=>'e.g. 127.0.0.1,10.0.1.10','attrs'=>'ip-enum','val'=>''),
            		'min-char-count'=>array('txt'=>__('Min. character count for inline ads','wpailang'),'type'=>'number','val'=>0),
            		'min-word-count'=>array('txt'=>__('Min. word count for inline ads','wpailang'),'type'=>'number','val'=>0),
            		'min-paragraph-count'=>array('txt'=>__('Min. paragraph count for inline ads','wpailang'),'type'=>'number','val'=>0),
            		'between-posts-every'=>array('txt'=>__('After every N posts','wpailang'),'type'=>'number','val'=>0),
            		'between-posts-max'=>array('txt'=>__('No. of ads between posts','wpailang'),'type'=>'number','val'=>0),
            		'homepage-below-title-max'=>array('txt'=>__('No. of ads below titles on home page','wpailang'),'type'=>'number','val'=>0),
                    'max-ads-count'=>array('txt'=>__('Max. number of displayed ads','wpailang'),'type'=>'number','val'=>0),
            )
            );

            $placementSettings = array( 'options'=>array(
                    'suppress-on-posts'=>array('txt'=>__('Suppress ads on posts','wpailang'),'type'=>'checkbox','val'=>false),
                    'suppress-post-id'=>array('txt'=>__('Suppress ads for specific post/page IDs','wpailang'),'type'=>'text','placeholder'=>'e.g. 32,9-19,33','attrs'=>'num-range','val'=>''),
                    'suppress-language'=>array('txt'=>__('Suppress ads for specific languages','wpailang'),'type'=>'array','src'=>'languages','val'=>''),
                    'suppress-url'=>array('txt'=>__('Suppress ads for specific URL paths','wpailang'),'type'=>'text','val'=>''),
                    'suppress-referrer'=>array('txt'=>__('Suppress ads for specific referrers','wpailang'),'type'=>'text','val'=>''),
                    'suppress-ipaddress'=>array('txt'=>__('Suppress ads for specific IP addresses','wpailang'),'type'=>'text','placeholder'=>'e.g. 127.0.0.1,10.0.1.10','attrs'=>'ip-enum','val'=>''),
                    'min-char-count'=>array('txt'=>__('Min. character count for inline ads','wpailang'),'type'=>'number','val'=>0),
                    'min-word-count'=>array('txt'=>__('Min. word count for inline ads','wpailang'),'type'=>'number','val'=>0),
                    'min-paragraph-count'=>array('txt'=>__('Min. paragraph count for inline ads','wpailang'),'type'=>'number','val'=>0)
            )
            );
            
            //prepare the defaults for fast merge via shortcode_atts (for get_settings, used by FE)
            //$vals = array_column($masterSettings['options'], 'val');
            //$names = array_keys($masterSettings['options']);
            //self::$defaultOptions = array_combine($names,$vals);

            return array(
                //'db-version' => '0',
                'blocks' => $blocks,
                'placements' => $placements,
                'options' => $masterSettings,//$options
                'placementOptions' => $placementSettings
            );
        }
        
        protected static function get_default_settings_097()
        {
        	$blocks = array();
        
        	$placements = array(
        			"homepage-below-title" => "",
        			"post-below-title" => "",
        			"post-below-content" => "",
        			"post-below-comments" => "",
        			"page-below-title" => "",
        			"page-below-content" => "",
        			"page-below-comments" => "",
        			"all-below-footer" => "",
        			"middle-of-post" => "",
        			"before-last-post-paragraph" => "",
        			"before-last-page-paragraph" => "",
        			//"before-last-post-sentence" => "", 
        			//"before-last-page-sentence" => "",
        			"after-first-post-paragraph" => "",
        			"after-first-page-paragraph" => "",
        			"between-posts" => "",
        			"above-everything" => ""
        	);
        
        	$options = array(
        			"hide-editor-button" => false,
        			"suppress-on-posts" => false,
        			"suppress-on-pages" => false,
        			"suppress-on-attachment" => false,
        			"suppress-on-category" => false,
        			"suppress-on-tag" => false,
        			"suppress-on-home" => false,
        			"suppress-on-front" => false,
        			"suppress-on-author" => false,
        			"suppress-on-archive" => false,
        			"suppress-on-error" => false,
        			"suppress-on-wptouch" => false,
        			"suppress-on-logged-in" => false,
        			"suppress-post-id" => "",
        			"suppress-category" => array(),
        			"suppress-tag" => array(),
        			"suppress-user" => array(),
        			"suppress-format" => array(),
        			"suppress-post-type" => array(),
        			"suppress-language" => array(),
        			"suppress-url" => "",
        			"suppress-referrer" => "",
        			"suppress-ipaddress" => "",
        			"min-char-count" => 0,
        			"min-word-count" => 0,
        			"min-paragraph-count" => 0,
        			"between-posts-every" => 0,
        			"between-posts-max" => 0,
        			"homepage-below-title-max" => 0,
                    "max-ads-count" => 0
        	);
        
        	return array(
        			'db-version' => '0',
        			'blocks' => $blocks,
        			'placements' => $placements,
        			'options' => $options
        	);
        }

	    /**
	     * Retrieves all of the settings from the database
	     *
	     * @mvc Model
	     *
	     * @param int $placementid
	     *
	     * @return array
	     */
        protected static function get_settings($placementid = 0)
        {
            
            $placements = WPAI_DB::wpai_get_placements();
            
            $blocks = WPAI_DB::wpai_get_blocks();
            
            $dbsettings = WPAI_DB::wpai_get_settings_short($placementid);
            
            
            //self::$default_settings['options'];//$options;
            $settings = array();
            $settings['options'] = $dbsettings;
            $settings['placements'] = $placements;
            $settings['blocks'] = $blocks;

            return $settings;
        }
         
        public static function get_master_settings_callback(){
        	        	
        	$placementid = $_REQUEST['p'];
        	
        	$dbsettings = WPAI_DB::wpai_get_settings($placementid);
        		
        	$data = array('settings'=>$dbsettings, 'masterSettings'=>self::$default_settings['options']);
        	
        	if (count($data['masterSettings'])>0){
        		$response = array("STATUS"=>"OK", "OBJ"=>$data,"MSG"=>__("Settings retrieved successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
        	}else{
        		$response = array("STATUS"=>"ERROR", "MSG"=>__("Error: settings could not be retrieved!",'wpailang'),"MSG_HEADER"=>__("ERROR","wpailang"),);
        	}
        	
        	echo json_encode($response);
        	die();
        	exit;
        }
        
        public static function get_placement_settings_callback(){
                        
            $placementid = $_REQUEST['p'];
            
            $dbsettings = WPAI_DB::wpai_get_placement_settings($placementid);
                
            $data = array('settings'=>$dbsettings, 'placementSettings'=>self::$default_settings['placementOptions']);
            
            if (count($data['placementSettings'])>0){
                $response = array("STATUS"=>"OK", "OBJ"=>$data,"MSG"=>__("Settings retrieved successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
            }else{
                $response = array("STATUS"=>"ERROR", "MSG"=>__("Error: settings could not be retrieved!",'wpailang'),"MSG_HEADER"=>__("ERROR","wpailang"),);
            }
            
            echo json_encode($response);
            die();
            exit;
        }

        public static function get_tags_callback(){
        	
        	if (!current_user_can(self::REQUIRED_CAPABILITY)) {
        		wp_die('Access denied!');
        	}
        	
        	$data = get_terms('post_tag'); 
        	 
        	if (!($data instanceof WP_Error)){
        		$response = array("STATUS"=>"OK", "OBJ"=>$data,"MSG"=>__("Tags retrieved  successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
        	}else{
        		$response = array("STATUS"=>"ERROR", "MSG"=>__("Error: tags could not be retrieved!",'wpailang'),"MSG_HEADER"=>__("ERROR","wpailang"),);
        	}
        	
        	echo json_encode($response);
        	die();
        	exit;
        }
        
        public static function get_cats_callback(){
        	
        	if (!current_user_can(self::REQUIRED_CAPABILITY)) {
        		wp_die('Access denied!');
        	}
        	
        	$data = get_terms('category');
        
        	if (!($data instanceof WP_Error)){
        		$response = array("STATUS"=>"OK", "OBJ"=>$data,"MSG"=>__("Categories retrieved  successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
        	}else{
        		$response = array("STATUS"=>"ERROR", "MSG"=>__("Error: categories could not be retrieved!",'wpailang'),"MSG_HEADER"=>__("ERROR","wpailang"),);
        	}
        	
        	echo json_encode($response);
        	die();
        	exit;
        }
        
        public static function get_authors_callback(){
        	
        	if (!current_user_can(self::REQUIRED_CAPABILITY)) {
        		wp_die('Access denied!');
        	}
        	
        	$data = get_users(array(
        		'orderby'=>'post_count',
        		'order'=>'DESC',
        		'fields'=>array('ID','user_nicename')
        			));
        
        	$response = array("STATUS"=>"OK", "OBJ"=>$data,"MSG"=>__("Users retrieved  successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
        	
        	echo json_encode($response);
        	die();
        	exit;
        }
        
        
        public static function get_post_formats_callback(){
        	
        	if (!current_user_can(self::REQUIRED_CAPABILITY)) {
        		wp_die('Access denied!');
        	}
        	
        	$formats = get_theme_support('post-formats');
        	
        	if (is_array($formats) && count($formats) > 0) {
        		foreach ($formats[0] as $format_name) {
        			$result[$format_name] = esc_html(get_post_format_string($format_name));
        		}
			}
        	
			$response = array("STATUS"=>"OK", "OBJ"=>$result,"MSG"=>__("Post formats retrieved  successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
        	  
        	echo json_encode($response);
        	die();
        	exit;
        }

        public static function get_post_types_callback(){
        	
        	if (!current_user_can(self::REQUIRED_CAPABILITY)) {
        		wp_die('Access denied!');
        	}
        	 
        	$data = get_post_types();
        	 
        	$response = array("STATUS"=>"OK", "OBJ"=>$data,"MSG"=>__("Post types retrieved  successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
        	
        	echo json_encode($response);
        	die();
        	exit;
        }
        
        public static function get_languages_callback(){
        	
        	if (!current_user_can(self::REQUIRED_CAPABILITY)) {
        		wp_die('Access denied!');
        	}
        	
        	if (function_exists('qtrans_getSortedLanguages')){
        
	        	$data = qtrans_getSortedLanguages();
	        	$result = array();
	        	
	        	if (is_array($data) && count($data) > 0) {
	        		foreach ($data as $d) {
	        			$result[$d] = qtrans_getLanguageName($d);
	        		}
	        	}
	        	
	        	$response = array("STATUS"=>"OK", "OBJ"=>$result,"MSG"=>__("Languages retrieved  successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
	        	
	        	echo json_encode($response);
	        	
        	}else if (function_exists('qtranxf_getSortedLanguages')){
        
	        	$data = qtranxf_getSortedLanguages();
	        	$result = array();
	        	
	        	if (is_array($data) && count($data) > 0) {
	        		foreach ($data as $d) {
	        			$result[$d] = qtranxf_getLanguageNameNative($d);
	        		}
	        	}
	        	
	        	$response = array("STATUS"=>"OK", "OBJ"=>$result,"MSG"=>__("Languages retrieved  successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
	        	
	        	echo json_encode($response);
	        	
        	}else{
        		$result = 0;
        		$response = array("STATUS"=>"OK", "OBJ"=>$result,"MSG"=>__("Languages not defined!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
        		echo json_encode($response);
        	}
        	
        	die();
        	exit;
        }
        
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
            array_unshift($links, '<a href="http://wordpress.org/extend/plugins/wp-advertize-it/faq/">Help</a>');
            array_unshift($links, '<a href="options-general.php?page=' . 'wpai_main">Settings</a>');

            return $links;
        }

        public static function get_ad_block($blocks, $id, $priority=0)
        {
            global $ads_list;
            if (!isset($ads_list)) $ads_list = array();
            
            global $ads_id;
            if (!isset($ads_id)) $ads_id = 0;
                                 
            if (isset($blocks[intval($id)]) && isset($blocks[intval($id)]->default_ads)) {
            	
            	$adstyle='style="';
            	if ($blocks[intval($id)]->alignment != "default"){
            		if ($blocks[intval($id)]->alignment == "center"){
            			$adstyle .= 'float:none;text-align:center;';
            			if (isset($blocks[intval($id)]->style)){
            				$adstyle .= 'margin:'.$blocks[intval($id)]->style.'px 0 '.$blocks[intval($id)]->style.'px 0;';
            			}
            		}else{
            			$adstyle .= 'float:'.$blocks[intval($id)]->alignment.';';
            			if ($blocks[intval($id)]->alignment == "right" 
            				&& isset($blocks[intval($id)]->style)){
            				$adstyle .= 'margin:'.$blocks[intval($id)]->style.'px 0 '.$blocks[intval($id)]->style.'px '.$blocks[intval($id)]->style.'px;';
            			}else if ($blocks[intval($id)]->alignment == "left" 
            				&& isset($blocks[intval($id)]->style)){
            				$adstyle .= 'margin:'.$blocks[intval($id)]->style.'px '.$blocks[intval($id)]->style.'px '.$blocks[intval($id)]->style.'px 0;';
            			}
            		}
            	}
            	$adstyle .= '"';
            	
            	//prepare json in comment for FE
            	$ads = explode('#wpai-del#',$blocks[intval($id)]->default_ads);
            	
            	if (intval($blocks[intval($id)]->rotate_ads) != 1){
            		$ads = array($ads[0]);
            		
            		if (intval($blocks[intval($id)]->promotion) != 1){
                        $ads_id++;
                        
                        $new_ad = array(
                            'content'=> '<!-- START-WP-ADS-ID: '. $ads_id . ' --><div id="wpads-'.sanitize_title($blocks[intval($id)]->name).'" '.$adstyle.'>' . $ads[0] .'</div><!-- END-WP-ADS-PRIO -->',
                            'priority'=> $priority,
                        	'ads_id'=>$ads_id
                        );
                        //array_push($ads_list, $new_ad);
                        $ads_list[$ads_id] = $new_ad;
            			//no rotation - write the ads directly
            			return $new_ad['content'];
            		}
            	}
            	
            	foreach($ads as $ad){
            		$rot_dur[] = $blocks[intval($id)]->rotation_duration;
            	}
            	
            	if (intval($blocks[intval($id)]->promotion) == 1){
            		if ($blocks[intval($id)]->sold_ads != ''){
            			$ads = array($blocks[intval($id)]->sold_ads); //overwrite the array if sold
            			if (intval($blocks[intval($id)]->promo_every) > 0 != '' 
            					&& $blocks[intval($id)]->promo != ''){
            				$rot_dur = array($blocks[intval($id)]->promo_every);
            			}else{
            				$rot_dur = array(0);
            			}
            		}
            		
            		if (	(($blocks[intval($id)]->sold_ads != '' && intval($blocks[intval($id)]->promo_only_not_sold) == 0) 
            					|| $blocks[intval($id)]->sold_ads == '' )
            				&& $blocks[intval($id)]->promo_every>0 != '' 
            				&& $blocks[intval($id)]->promo != '')
            		{
            			$ads[] = $blocks[intval($id)]->promo;
            			$rot_dur[] = $blocks[intval($id)]->promo_duration;
            		}
            	}
            	
            	foreach($ads as $key=>$ad){            		
            		$ad = str_replace('-->', '#wpai-endc#',$ad);            		
            		$ads[$key] = $ad;
            		
            	}

                $ads_id++;
                $new_ad = array(
                        'content'=> '<!-- START-WP-ADS-ID: '. $ads_id . ' --><div id="wpads-'.sanitize_title($blocks[intval($id)]->name).'" '.$adstyle.'>' .
                                    '<!-- WPAI ' . json_encode(array('ads'=>$ads,'rot_dur'=>$rot_dur)) . '--> ' .
                                    '</div><!-- END-WP-ADS-PRIO -->',
                        'priority'=> $priority
                );
                $ads_list[$ads_id] = $new_ad;
                return $new_ad['content'];
            }
            return "";
        }

    } // end WPAI_Settings
}
