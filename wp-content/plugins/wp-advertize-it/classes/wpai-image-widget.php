<?php

/**
 * Created by PhpStorm.
 * User: benohead
 * Date: 09.05.14
 * Time: 13:55
 */
class WPAI_Image_Widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'wpai_image_widget',
            __('Image Ad', 'wpai_widget_domain'),
            array('description' => __('WP Advertize It Image Ad', 'wpai_widget_domain'),)
        );
    }

    public function widget($args, $instance)
    {
        $content = get_the_content();

        $options = WPAI_Settings::get_instance()->settings['options'];
        
        if (WordPress_Advertize_It::get_instance()->is_suppress_specific($options, $content)
            || WordPress_Advertize_It::get_instance()->is_placement_suppress_specific('widget')) {
            return;
        }

        $categories = array();
        foreach (get_the_category() as $c) {
            array_push($categories, $c->name);
        }

        if($instance['categories'] && !array_intersect($instance['categories'], $categories)) {
            return;
        }

        $title = apply_filters('widget_title', $instance['title']);
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];

        ?>
        <a <?php echo $instance['new-window'] == 1 ? 'target="_blank"' : ''; ?> href='<?php echo $instance['link']; ?>'
                                                                                title='<?php echo $instance['description']; ?>'>
            <img <?php echo $instance['resize'] == 1 ? "style='width: 100%;'" : ''; ?>
                src='<?php echo $instance['image']; ?>' alt='<?php echo $instance['description']; ?>'/>
        </a>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $image_id = $this->get_field_id('image');
        $categories = get_categories();

        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New title', 'wpai_widget_domain');
        }

        if (isset($instance['description'])) {
            $description = $instance['description'];
        } else {
            $description = "";
        }

        if (isset($instance['resize'])) {
            $resize = $instance['resize'];
        } else {
            $resize = 0;
        }

        if (isset($instance['new-window'])) {
            $new_window = $instance['new-window'];
        } else {
            $new_window = 0;
        }

        if (isset($instance['link'])) {
            $link = $instance['link'];
        } else {
            $link = "";
        }

        if (isset($instance['categories'])) {
            $selected_categories = $instance['categories'];
        } else {
            $selected_categories = array();
        }

        if (isset($instance['priority'])) {
            $selected_priority = $instance['priority'];
        } else {
            $selected_priority = 10;
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>
        <?php if (isset($instance['image']) && $instance['image']): ?>
        <div>
            <img style="width:100%;" src="<?php echo $instance['image'] ?>"/>
        </div>
    <?php endif; ?>
        <br/>
        <a href="#" class="media-button button button-secondary" rel="<?php echo $image_id ?>">Select
            image</a>
        <br/>
        <br/>You can also paste in an image URL below.
        <input class="widefat wpai-image-url" placeholder="Image URL" type="text" id="<?php echo $image_id; ?>"
               name="<?php echo $this->get_field_name('image'); ?>"
               value="<?php echo htmlentities(isset($instance['image']) ? $instance['image'] : ""); ?>"/>
        <br/>
        <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:'); ?></label>
        <input class="widefat wpai-image-description" id="<?php echo $this->get_field_id('description'); ?>"
               name="<?php echo $this->get_field_name('description'); ?>" type="text"
               value="<?php echo esc_attr($description); ?>"
               placholder="<?php _e('Enter a description here'); ?>"/>
        <br/>
        <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link:'); ?></label>
        <input class="widefat wpai-image-link" id="<?php echo $this->get_field_id('link'); ?>"
               name="<?php echo $this->get_field_name('link'); ?>" type="text"
               value="<?php echo esc_attr($link); ?>"
               placholder="<?php _e('Enter a link here'); ?>"/>
        <br/>
        <label for="<?php echo $this->get_field_id('categories');?>"><?php _e('Category:') ?></label>
        <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>"
                name="<?php echo $this->get_field_name('categories'); ?>[]" multiple>
            <?php foreach ($categories as $category) {
                $selected = '';
                if (in_array($category->name, $selected_categories))
                    $selected = 'selected="selected"';
                ?>
                <option value="<?php echo $category->name; ?>" <?php echo $selected ?>><?php echo $category->name; ?></option>   
            <?php } ?>
        </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('priority');?>"><?php _e('Priority:') ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('priority'); ?>" name="<?php echo $this->get_field_name('priority'); ?>" min="1" type="number" value="<?php echo $selected_priority;?>"></input>
        </p>
        <p>
            <input class="widefat wpai-image-resize" id="<?php echo $this->get_field_id('resize'); ?>"
                   name="<?php echo $this->get_field_name('resize'); ?>" type="checkbox"
                   value="1" <?php checked(1, $resize) ?>/>
            <label for="<?php echo $this->get_field_id('resize'); ?>"><?php _e('Resize to Max Width.'); ?></label>
        </p>
        <p>
            <input class="widefat wpai-image-new-window" id="<?php echo $this->get_field_id('new-window'); ?>"
                   name="<?php echo $this->get_field_name('new-window'); ?>" type="checkbox"
                   value="1" <?php checked(1, $new_window) ?>/>
            <label for="<?php echo $this->get_field_id('new-window'); ?>"><?php _e('Open in new window.'); ?></label>
        </p>
    <?php
    }

// Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['new-window'] = $new_instance['new-window'];
        $instance['resize'] = $new_instance['resize'];
        $instance['link'] = (!empty($new_instance['link'])) ? strip_tags($new_instance['link']) : '';
        $instance['image'] = (!empty($new_instance['image'])) ? strip_tags($new_instance['image']) : '';
        $instance['description'] = (!empty($new_instance['description'])) ? strip_tags($new_instance['description']) : '';
        $instance['categories'] = (!empty($new_instance['categories'])) ? esc_sql($new_instance['categories']) : array();
        $instance['priority'] = (!empty($new_instance['priority'])) ? strip_tags($new_instance['priority']) : 10;
        return $instance;
    }
} // Class wpai_image_widget ends here

// Register and load the widget
function wpai_image_load_widget()
{
    register_widget('wpai_image_widget');
}

add_action('widgets_init', 'wpai_image_load_widget');