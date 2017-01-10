<?php
/*
* Plugin Name: You Tube Page Redirector
* Description: This will create a short code to redirect any page whenever a video stops splaying. You just need to specify the redirect_url like [YT-page-redirector redirect_url="http://trickntip.com/wordpress-you-tube-page-redirector-plugin" video_id="0Bmhjf0rKe8" height="390" width="640"]
* Version: 1.0
* Author: Jatinder Singh
* Author URI: http://trickntip.com/wordpress-you-tube-page-redirector-plugin
*/

function yt_redirector( $atts ){
	
	wp_enqueue_script( 'player_api','http://www.youtube.com/player_api');
	//wp_enqueue_script( 'yt-function',plugins_url().'/youtube-page-redirector/js/yt-functions.js');

	$atts = shortcode_atts(
		array(
		    'div_unique_id' => md5(uniqid(rand(), true)),
			'height' => '390',
			'width' => '640',
			'video_id' => '',
			'redirect_url' => ''
		), $atts, 'YT-page-redirector' );
	
	if(!empty($atts['redirect_url']) && !empty($atts['video_id']) && is_singular('post')){ 
		?>
		
		<div id="yt-player-<?php echo $atts['div_unique_id'];?>"></div>
		
		<script>
			//redirection will be done only on singular post page
			var is_singular_post = <?php echo is_singular('post') ? 'true' : 'false';?>;
			// create youtube player
			var player;
			function onYouTubePlayerAPIReady() {
				player = new YT.Player('yt-player-<?php echo $atts['div_unique_id'];?>', {
				  height: '<?php echo $atts['height'];?>',
				  width: '<?php echo $atts['width'];?>',
				  videoId: '<?php echo $atts['video_id'];?>',
				  events: {
					'onReady': onPlayerReady,
					'onStateChange': onPlayerStateChange
				  }
				});
			}

			// autoplay video
			function onPlayerReady(event) {
				if(is_singular_post){
					event.target.playVideo();
				}
				
			}

			// when video ends
			function onPlayerStateChange(event) {        
				if(event.data === 0 && is_singular_post) {            
					n = window.open(
					  '<?php echo $atts['redirect_url'];?>',
					  '_blank'
					);	
					//check if popup blocked
					if(n == null) {
						window.location.href="<?php echo $atts['redirect_url'];?>"
					}
				}
			}
			
		</script>
		<?php
	}else{
		
	}
}
add_shortcode('YT-page-redirector', 'yt_redirector');
?>

