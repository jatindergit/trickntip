<?php
/*
Plugin Name: Adsense Made Easy
Plugin URI: http://wordpress.org/extend/plugins/adsense-made-easy/
Description: Creates a widget that allows you to add Adsense to any widgetized sidebar of your theme
Version: 0.5.1
Author: Cody J. Tooker
*/

/*  Copyright 2011  Cody Tooker  (email : Cody.Tooker57@hotmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//INCLUDE WIDGET FORM PHP
if (file_exists (dirname (__FILE__).'/cjt_adsense_functions.php')) {
	include (dirname (__FILE__).'/cjt_adsense_functions.php');
} else {
	echo '<font size="+1" color="red">' ;
	_e("Error locating the adsense functions page!\nEnsure cjt_adsense_functions.php exists, or reinstall the plugin.") ;
	echo '</font>' ;
}

class cjt_adsense_made_easy extends WP_Widget {

	/*contstructor initializes the widget*/
	function cjt_adsense_made_easy() 
	{
		$widget_options = array(
			'classname'   => 'cjt_adsense_made_easy',
			'description' => __('SIMPLE and EASY Google Adsense Widget')
		);
		
		// Call the parent class WP_Widget
		parent::WP_Widget('cjt_adsense_made_easy', 'Adsense Made Easy', $widget_options);			
	}

	/*Displays info in the sidebar*/
	function widget($args, $instance) 
	{
		extract( $args, EXTR_SKIP );
		
		/*if not saved in widget admin panel use DEFAULTS*/
		$title = ( $instance['title'] ) ? $instance['title'] : '';
		$publisher = ( $instance['publisher'] ) ? $instance['publisher'] : '';
		$size = ( $instance['size'] ) ? $instance['size'] : '300x250';
		$type = ( $instance['type'] ) ? $instance['type'] : 'text';
		$channel = ( $instance['channel'] ) ? $instance['channel'] : '';
		$color_border = ( $instance['color_border'] ) ? $instance['color_border'] : '000000';
		$color_bg = ( $instance['color_bg'] ) ? $instance['color_bg'] : '0000FF';
		$color_link = ( $instance['color_link'] ) ? $instance['color_link'] : 'FFFFFF';
		$color_text = ( $instance['color_text'] ) ? $instance['color_text'] : '000000';
		$color_url = ( $instance['color_url'] ) ? $instance['color_url'] : '008000';
		$corners = ( $instance['corners'] ) ? $instance['corners'] : 'rc:0';
		$align = ( $instance['align'] ) ? $instance['align'] : 'left';

		//display on site
		if (function_exists('cjt_display_adsense_made_easy')) {
			echo cjt_display_adsense_made_easy($args, $title, $publisher, $size, $type, $channel, $color_border, $color_bg, $color_link, $color_text, $color_url, $corners, $align);
		} else {
			echo '<font size="+1" color="red">' ;
			_e("Error locating the display function!\nEnsure cjt_adsense_functions.php exists, or reinstall the plugin.") ;
			echo '</font>' ;
		}
	}//end widget

	/*Save new information*/
	function update($new_instance, $old_instance) 
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['publisher'] = strip_tags( $new_instance['publisher'] );
		$instance['size'] = strip_tags( $new_instance['size'] );
		$instance['type'] = strip_tags( $new_instance['type'] );
		$instance['channel'] = strip_tags( $new_instance['channel'] );
		$instance['color_border'] = strip_tags( $new_instance['color_border'] );
		$instance['color_bg'] = strip_tags( $new_instance['color_bg'] );
		$instance['color_link'] = strip_tags( $new_instance['color_link'] );
		$instance['color_text'] = strip_tags( $new_instance['color_text'] );
		$instance['color_url'] = strip_tags( $new_instance['color_url'] );
		$instance['corners'] = strip_tags( $new_instance['corners'] );
		$instance['align'] = strip_tags( $new_instance['align'] );
		
		return $instance;
	}

	/*Displays widget in admin widget section*/
	function form($instance)
	{
		//create form defaults
		$defaults = array(
			'title' => '',
			'publisher' => '',
			'size' => '300x250',
			'type' => 'text',
			'channel' => '',
			'color_border' => '000000',
			'color_bg' => '0000FF',
			'color_link' => 'FFFFFF',
			'color_text' => '000000',
			'color_url' => '008000',
			'corners' => 'rc:0',
			'align' => 'left'
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = $instance['title'];
		$publisher = $instance['publisher'];
		$size = $instance['size'];
		$type = $instance['type'];
		$channel = $instance['channel'];
		$color_border = $instance['color_border'];
		$color_bg = $instance['color_bg'];
		$color_link = $instance['color_link'];
		$color_text = $instance['color_text'];
		$color_url = $instance['color_url'];
		$corners = $instance['corners'];
		$align = $instance['align'];

		//INCLUDE WIDGET FORM PHP
		if (file_exists (dirname (__FILE__).'/cjt_widget_form.php')) {
       		include (dirname (__FILE__).'/cjt_widget_form.php');
		} else {
        	echo '<font size="+1" color="red">' ;
        	_e("Error locating the widget form page!\nEnsure cjt_widget_form.php exists, or reinstall the plugin.") ;
        	echo '</font>' ;
      	}

	}//End of form 

}/*Ends WP_Widget Class*/

/*register widget */
function cjt_adsense_made_easy_init() 
{
	register_widget('cjt_adsense_made_easy');
}

/*adds widget to panel*/
add_action('widgets_init', 'cjt_adsense_made_easy_init');

?>