<?php

/*This file contains different custom functions
used in adsense made easy. The functions are seperated
onto there own page to help the ease in development
as well as easy editing by the user*/


/*---------------------------START DISPLAY FUNCTIONS-------------------------------------*/

//Display Divs and Javascript Adsense
function cjt_display_adsense_made_easy($args, $title, $publisher, $size, $type, $channel, $color_border, $color_bg, $color_link, $color_text, $color_url, $corners, $align)
{
	//seperate the w and h from size where size is 111x222
	$width = substr($size, 0, 3);
	$height = substr($size, 4, 3);	
	
	//echo functions for displaying div-script-div
	echo cjt_display_before_widget($args, $title);
	echo cjt_display_alignment_div($align, $width);
	echo cjt_display_adsense_script($publisher, $size, $type, $channel, $color_border, $color_bg, $color_link, $color_text, $color_url, $corners, $width, $height);
	echo cjt_display_alignment_close_div($align);
	echo cjt_display_after_widget($args, $title);
}
//Display the before widget and title WP functions if TITLE exist
function cjt_display_before_widget($args, $title)
{
	extract( $args, EXTR_SKIP );
	
	if (strlen($title) > 0) {
		echo $before_widget; 
 		echo $before_title . $title . $after_title; 
	}
}
//Display the after widget WP functions if Title exit
function cjt_display_after_widget($args, $title) 
{
	extract( $args, EXTR_SKIP );
	
	if (strlen($title) > 0) {
		echo $after_widget; 
	}	
}
//Align the adsense add using simple css styling
function cjt_display_alignment_div($align, $width)
{
		if ($align == 'right') {
			echo "<div style='float:right;'>";
		} else if ($align == 'left') {
			echo "<div style='float:left;'>";	
		} else {
			echo "<div style='margin: 0px auto; width:". $width ."px;'>";
		}
}
//Close the adsense alignment div
function cjt_display_alignment_close_div($align)
{
		if ($align == 'right') {
			echo "</div>";
			echo "<div style='clear:right;'></div>";
		} else if ($align == 'left') {
			echo "</div>";
			echo "<div style='clear:left;'></div>";	
		} else {
			echo "</div>";
		}
}
//display the script for adsense
function cjt_display_adsense_script($publisher, $size, $type, $channel, $color_border, $color_bg, $color_link, $color_text, $color_url, $corners, $width, $height)
{

	$script_adsense = "";
	
	if ($height == 15) {     //Check height to see if link unit first
		$ad_format_suf = '_0ads_al';
		
		$script_adsense = '<script type="text/javascript">';
		$script_adsense .= '<!--
		google_ad_client = "'.$publisher.'";
		google_ad_width = '.$width.';
		google_ad_height = '.$height.';
		google_ad_format = "'.$size . $ad_format_suf.'";
		google_ad_channel ="'.$channel.'";
		google_color_border = "'.$color_border.'";
		google_color_link = "'.$color_bg.'";
		google_color_bg = "'.$color_link.'";
		google_color_text = "'.$color_text.'";
		google_color_url = "'.$color_url.'";
		google_ui_features = "'.$corners.'";
		//--></script>
		<script type="text/javascript"
		src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>';	
	} else {      //if not link unit then it is an add unit
		$ad_format_suf = '_as';
		
		$script_adsense = '<script type="text/javascript">';
		$script_adsense .= '<!--
		google_ad_client = "'.$publisher.'";
		google_ad_width = '.$width.';
		google_ad_height = '.$height.';
		google_ad_format = "'.$size . $ad_format_suf.'";
		google_ad_type = "'.$type.'";
		google_ad_channel ="'.$channel.'";
		google_color_border = "'.$color_border.'";
		google_color_link = "'.$color_bg.'";
		google_color_bg = "'.$color_link.'";
		google_color_text = "'.$color_text.'";
		google_color_url = "'.$color_url.'";
		google_ui_features = "'.$corners.'";
		//--></script>
		<script type="text/javascript"
		src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>';	
	}

	return $script_adsense;
}


/*--------------------------END DISPLAY FUNCTIONS-----------------------------*/
?>