<!--label and text field for widget title-->
<p>
    <label for="<?php echo $this->get_field_name('title'); ?>"><?php _e('Title:'); ?></label><br />
    <input class="cjtadsense" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" size="25" maxlength="25"/>
</p>

<!--label and text field for publisher-->
<p>
    <label for="<?php echo $this->get_field_name('publisher'); ?>"><?php _e('Publisher ID:'); ?></label><br />
    <input class="cjtadsense" name="<?php echo $this->get_field_name( 'publisher' ); ?>" type="text" value="<?php echo esc_attr( $publisher ); ?>" size="25" maxlength="20"/>
</p>

<!--label and buttons for size-->
<p>
    <label for="<?php echo $this->get_field_name('size'); ?>"><?php _e('Dimensions:'); ?></label><br />
    <select name="<?php echo $this->get_field_name('size'); ?>" id="<?php echo $this->get_field_id('size'); ?>">
        <optgroup label="Recommened">
        <option class="indent"  value="300x250" <?php if( $size == '300x250' ) echo "selected='selected'" ?>><?php _e('300 X 250 Medium Rectangle'); ?></option>
        <option class="indent"  value="336x280" <?php if( $size == '336x280' ) echo "selected='selected'" ?>><?php _e('336 X 280 Large Rectangle');  ?></option>
        <option class="indent"  value="728x90"  <?php if( $size == '728x90' )  echo "selected='selected'" ?>><?php _e('728 X 90 Leaderboard');       ?></option>
        <option class="indent"  value="160x600" <?php if( $size == '160x600' ) echo "selected='selected'" ?>><?php _e('160 X 600 Wide Scyscraper');  ?></option>
        </optgroup>
        <optgroup label="Other - Horizontal">
        <option class="indent"  value="468x60" <?php if( $size == '468x60' )  echo "selected='selected'" ?>><?php _e('468 X 60 Banner');            ?></option>
        <option class="indent"  value="234x60" <?php if( $size == '234x60' )  echo "selected='selected'" ?>><?php _e('234 X 60 Half Banner');       ?></option>
        </optgroup>
        <optgroup label="Other - Vertical">
        <option class="indent"  value="120x600" <?php if( $size == '120x600' ) echo "selected='selected'" ?>><?php _e('120 X 600 Scyscraper');       ?></option>
        <option class="indent"  value="120x240" <?php if( $size == '120x240' ) echo "selected='selected'" ?>><?php _e('120 X 240 Vertical Banner');  ?></option>
        </optgroup>
        <optgroup label="Other - Square">
        <option class="indent"  value="250x250" <?php if( $size == '250x250' ) echo "selected='selected'" ?>><?php _e('250 X 250 Square');           ?></option>
        <option class="indent"  value="200x200" <?php if( $size == '200x200' ) echo "selected='selected'" ?>><?php _e('200 X 200 Small Square');     ?></option>
        <option class="indent"  value="180x150" <?php if( $size == '180x150' ) echo "selected='selected'" ?>><?php _e('180 X 150 Small Rectangle');  ?></option>
        <option class="indent"  value="125x125" <?php if( $size == '125x125' ) echo "selected='selected'" ?>><?php _e('125 X 125 Button');           ?></option>
        </optgroup>
        <optgroup label="Link Units">
        <option class="indent"  value="728x15" <?php if( $size == '728x15' )  echo "selected='selected'" ?>><?php _e('728 X 15');                   ?></option>
        <option class="indent"  value="468x15" <?php if( $size == '468x15' )  echo "selected='selected'" ?>><?php _e('468 X 15');                   ?></option>
        </optgroup>
    </select>
</p>

<!--label and dropdown for type-->	
<p>
    <label for="<?php echo $this->get_field_name('type'); ?>"><?php _e('Type:'); ?></label><br />
    <select name="<?php echo $this->get_field_name('type'); ?>" id="<?php echo $this->get_field_id('type'); ?>">
        <option value="text" <?php if( $type == 'text' )       echo "selected='selected'" ?>><?php _e('Text');?></option>
        <option value="image" <?php if( $type == 'image' )      echo "selected='selected'" ?>><?php _e('Image');        ?></option>
        <option value="text_image" <?php if( $type == 'text_image' ) echo "selected='selected'" ?>><?php _e('Text & Image'); ?></option>
    </select>
</p>

<!--label and text field for channel-->
<p>
    <label for="<?php echo $this->get_field_name('channel'); ?>"><?php _e('Custom Channel:'); ?></label><br />
    <input class="cjtadsense" name="<?php echo $this->get_field_name( 'channel' ); ?>" type="text" value="<?php echo esc_attr( $channel ); ?>" />
</p>
<p>
<table>
    <tr><!--label and text field for color border-->
        <td class="td_right"><label for="<?php echo $this->get_field_name('color_border'); ?>"><?php _e('Border Color:'); ?></label><br /></td>
        <td><input style="text-transform: uppercase" class="cjtadsense" name="<?php echo $this->get_field_name( 'color_border' ); ?>" type="text" value="<?php echo esc_attr( $color_border ); ?>" size="6" maxlength="6"/></td>
    </tr>
    <tr><!--label and text field for color background-->
        <td class="td_right"><label for="<?php echo $this->get_field_name('color_bg'); ?>"><?php _e('Title Color:'); ?></label><br /></td>
        <td><input style="text-transform: uppercase" class="cjtadsense" name="<?php echo $this->get_field_name( 'color_bg' ); ?>" type="text" value="<?php echo esc_attr( $color_bg ); ?>" size="6" maxlength="6"/></td>
    </tr>
     <tr><!--label and text field for background link-->
        <td class="td_right"><label for="<?php echo $this->get_field_name('color_link'); ?>"><?php _e('Background Color:'); ?></label><br /></td>
        <td><input style="text-transform: uppercase" class="cjtadsense" name="<?php echo $this->get_field_name( 'color_link' ); ?>" type="text" value="<?php echo esc_attr( $color_link ); ?>" size="6" maxlength="6"/></td>
    </tr>
     <tr><!--label and text field for text background color-->
        <td class="td_right"><label for="<?php echo $this->get_field_name('color_text'); ?>"><?php _e('text Color:'); ?></label><br /></td>
        <td><input style="text-transform: uppercase" class="cjtadsense" name="<?php echo $this->get_field_name( 'color_text' ); ?>" type="text" value="<?php echo esc_attr( $color_text ); ?>" size="6" maxlength="6"/></td>
    </tr>
     <tr><!--label and text field for color url-->
        <td class="td_right"><label for="<?php echo $this->get_field_name('color_url'); ?>"><?php _e('URL Color:'); ?></label><br /></td>
        <td><input style="text-transform: uppercase" class="cjtadsense" name="<?php echo $this->get_field_name( 'color_url' ); ?>" type="text" value="<?php echo esc_attr( $color_url ); ?>" size="6" maxlength="6"/></td>
    </tr>
</table>
</p> 

<!--label and dropdown for corners-->	
<p>
    <label for="<?php echo $this->get_field_name('corners'); ?>"><?php _e('Corner Style'); ?></label><br />
    <select name="<?php echo $this->get_field_name('corners'); ?>" id="<?php echo $this->get_field_id('corners'); ?>">
        <option value="rc:0"<?php if( $corners == 'rc:0' )  echo "selected='selected'" ?>><?php _e('Square Corners');           ?></option>
        <option value="rc:6"<?php if( $corners == 'rc:6' )  echo "selected='selected'" ?>><?php _e('Slightly Rounded Corners'); ?></option>
        <option value="rc:10"<?php if( $corners == 'rc:10' ) echo "selected='selected'" ?>><?php _e('Very Rounded Corners');     ?></option>
    </select>
</p>

<!--label and dropdown for ad alignment-->	
<p>
    <label for="<?php echo $this->get_field_name('align'); ?>"><?php _e('Ad Alignment'); ?></label><br />
    <select name="<?php echo $this->get_field_name('align'); ?>" id="<?php echo $this->get_field_id('align'); ?>">
        <option value="left"<?php if( $align == 'left' )  echo "selected='selected'" ?>><?php _e('Left'); ?></option>
        <option value="center"<?php if( $align == 'center' )  echo "selected='selected'" ?>><?php _e('Center'); ?></option>
        <option value="right"<?php if( $align == 'right' ) echo "selected='selected'" ?>><?php _e('Right'); ?></option>
    </select>
</p>