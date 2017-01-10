/*
 *Written by: TricKnTip.com 
 */


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

	
