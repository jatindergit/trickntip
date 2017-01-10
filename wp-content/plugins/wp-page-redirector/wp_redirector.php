<?php
/*
* Plugin Name: WP Page Redirector
* Description: This will create a short code to redirect any page after some time interval. You just need to specify the time in seconds like [wp-page-redirector timeout="5" redirect_url="http://trickntip.com/wordpress-page-redirector-plugin"]
* Version: 1.0
* Author: Jatinder Singh
* Author URI: http://trickntip.com/wordpress-page-redirector-plugin
*/

function redirector( $atts ){
	$atts = shortcode_atts(
		array(
			'timeout' => 1,
			'redirect_url' => ''
		), $atts, 'wp-page-redirector' );

	
	if(is_numeric($atts['timeout']) && !empty($atts['redirect_url'])){
		$timeout = $atts['timeout'] * 1000;
		?><script>setTimeout(function(){window.location.href="<?php echo $atts['redirect_url'];?>"},<?php echo $timeout;?>);</script><?php
	}
}
add_shortcode('wp-page-redirector', 'redirector');
?>

