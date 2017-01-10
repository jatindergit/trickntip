<?php
/*
Plugin Name: WP Advertize It
Plugin URI:  https://wordpress.org/plugins/wp-advertize-it/
Description: A plugin to place adsense blocks on your site
Version:     1.2.1
Author:      Henri Benoit, Alexander Herdt, amazingweb.de
Author URI:  http://amazingweb.de
*/

/*
 * This plugin was built on top of WordPress-Plugin-Skeleton by Ian Dunn.
 * See https://github.com/iandunn/WordPress-Plugin-Skeleton for details.
 */

if (!defined('ABSPATH')) {
    die('Access denied.');
}

define('WPAI_NAME', 'WP Advertize It');
define('WPAI_REQUIRED_PHP_VERSION', '5.3'); // because of get_called_class()
define('WPAI_REQUIRED_WP_VERSION', '3.1'); // because of esc_textarea()

define('WPAI_PLUGIN_URL', plugin_dir_url( __FILE__ ));

/**
 * Checks if the system requirements are met
 *
 * @return bool True if system requirements are met, false if not
 */
function wpai_requirements_met()
{
    global $wp_version;

    if (version_compare(PHP_VERSION, WPAI_REQUIRED_PHP_VERSION, '<')) {
        return false;
    }

    if (version_compare($wp_version, WPAI_REQUIRED_WP_VERSION, '<')) {
        return false;
    }

    return true;
}

/**
 * Prints an error that the system requirements weren't met.
 */
function wpai_requirements_error()
{
    global $wp_version;

    require_once(dirname(__FILE__) . '/views/requirements-error.php');
}

function wpai_ajaxurl() {
	?>
		<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		</script>
   <?php
}

/*
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met. Otherwise older PHP installations could crash when trying to parse it.
 */
if (wpai_requirements_met()) {
    require_once(__DIR__ . '/classes/wpai-module.php');
    require_once(__DIR__ . '/classes/wp-advertize-it.php');
    require_once(__DIR__ . '/includes/admin-notice-helper/admin-notice-helper.php');
    require_once(__DIR__ . '/classes/wpai-settings.php');
    require_once(__DIR__ . '/classes/wpai-instance-class.php');
    require_once(__DIR__ . '/classes/wpai-widget.php');
    require_once(__DIR__ . '/classes/wpai-offers.php');
    require_once(__DIR__ . '/classes/wpai-db.php');
    require_once(__DIR__ . '/classes/wpai-image-widget.php');

    if (class_exists('WordPress_Advertize_It')) {
        $GLOBALS['wpai'] = WordPress_Advertize_It::get_instance();
        register_activation_hook(__FILE__, array($GLOBALS['wpai'], 'activate'));
        register_deactivation_hook(__FILE__, array($GLOBALS['wpai'], 'deactivate'));
    }
} else {
    add_action('admin_notices', 'wpai_requirements_error');
}

if (class_exists('WordPress_Advertize_It')) {
    function show_ad_block($block)
    {
        echo $GLOBALS['wpai']->get_ad_block($block);
    }
}