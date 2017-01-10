<?php

/**
 * Created by PhpStorm.
 * User: benohead
 * Date: 09.05.14
 * Time: 13:55
 */
class WPAI_Widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'wpai_widget',
            __('Ad Block', 'wpai_widget_domain'),
            array('description' => __('WP Advertize It', 'wpai_widget_domain'),)
        );
    }

    public function widget($args, $instance)
    {
        $content = get_the_content();

        $options = WPAI_Settings::get_instance()->settings['options'];

        if (WordPress_Advertize_It::get_instance()->is_suppress_specific($options, $content)
            || WordPress_Advertize_It::get_instance()->is_placement_suppress_specific('widget')) 
        {
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

        $block = $instance['block'];
        $blocks = WPAI_Settings::get_instance()->settings['blocks'];
        echo WPAI_Settings::get_ad_block($blocks, $block, $instance['priority']);
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $settings = WPAI_Settings::get_instance()->settings;
        $categories = get_categories();

        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New title', 'wpai_widget_domain');
        }

        if (isset($instance['block'])) {
            $selected_block = $instance['block'];
        } else {
            $selected_block = "";
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
            <label for="<?php echo $this->get_field_id('block'); ?>"><?php _e('Ad Block:'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('block'); ?>"
                    name="<?php echo $this->get_field_name('block'); ?>">
                <?php
                foreach ($settings['blocks'] as $i => $block) :
                    $label = $block->name;
                    $selected = '';
                    if ($selected_block == $i)
                        $selected = 'selected="selected"';
                    ?>
                    <option style="padding-right: 10px;"
                            value="<?php echo esc_attr($i); ?>" <?php echo $selected ?>><?php echo $label ?></option>
                <?php
                endforeach;
                ?>
            </select>
        </p>
        <p>
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
    <?php
    }

// Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['block'] = (!empty($new_instance['block'])) ? strip_tags($new_instance['block']) : '';
        $instance['categories'] = (!empty($new_instance['categories'])) ? esc_sql($new_instance['categories']) : array();
        $instance['priority'] = (!empty($new_instance['priority'])) ? strip_tags($new_instance['priority']) : 10;
        return $instance;
    }
} // Class wpai_widget ends here

// Register and load the widget
function wpai_load_widget()
{
    register_widget('wpai_widget');
}

add_action('widgets_init', 'wpai_load_widget');
