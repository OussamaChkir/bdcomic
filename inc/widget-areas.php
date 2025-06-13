<?php
class Goldland_widget_areas
{

    public function __construct()
    {
        add_action('widgets_init', array($this, 'rpl_register_widgets'), 20);
        add_action('widgets_init', array($this, 'register_social_media_widget'), 25);
        add_action('widgets_init', array($this, 'register_contact_info_widget'), 30);
    }

    public function rpl_register_widgets()
    {
        // Register Footer widget area.
        register_sidebar(array(
            'name' => __('Footer Left Side', 'degesa'),
            'id' => 'footer-left-side',
            'description' => '',
            'before_widget' => '<div class="widget">',
            'after_widget' => '</div>',
            'before_title' => '<div class="title">',
            'after_title' => '</div>',
        ));
        register_sidebar(array(
            'name' => __('Footer Right Side', 'degesa'),
            'id' => 'footer-right-side',
            'description' => '',
            'before_widget' => '<div class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<div class="title">',
            'after_title' => '</div>',
        ));
    }

    // Register the custom social media widget
    public function register_social_media_widget()
    {
        register_widget('Goldland_Social_Media_Widget');
    }

    // Register the contact info widget
    public function register_contact_info_widget()
    {
        register_widget('Goldland_Contact_Info_Widget');
    }
}

// Define the Social Media Widget class
class Goldland_Social_Media_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'goldland_social_media_widget',
            __('Degesa: Social Media', 'degesa'),
            array('description' => __('A widget to display social media icons with URLs', 'degesa'))
        );
    }

    // Backend widget form
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : '';

        $linkedin = !empty($instance['linkedin']) ? $instance['linkedin'] : '';
        $instagram = !empty($instance['instagram']) ? $instance['instagram'] : '';
        $youtube = !empty($instance['youtube']) ? $instance['youtube'] : '';
        $whatsapp = !empty($instance['whatsapp']) ? $instance['whatsapp'] : '';
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('linkedin'); ?>">LinkedIn URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('linkedin'); ?>" name="<?php echo $this->get_field_name('linkedin'); ?>" type="text" value="<?php echo esc_attr($linkedin); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('instagram'); ?>">Instagram URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('instagram'); ?>" name="<?php echo $this->get_field_name('instagram'); ?>" type="text" value="<?php echo esc_attr($instagram); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('youtube'); ?>">YouTube URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('youtube'); ?>" name="<?php echo $this->get_field_name('youtube'); ?>" type="text" value="<?php echo esc_attr($youtube); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('whatsapp'); ?>">WhatsApp URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('whatsapp'); ?>" name="<?php echo $this->get_field_name('whatsapp'); ?>" type="text" value="<?php echo esc_attr($whatsapp); ?>">
        </p>
        <?php
    }

    // Save widget settings
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';

        $instance['linkedin'] = (!empty($new_instance['linkedin'])) ? esc_url_raw($new_instance['linkedin']) : '';
        $instance['instagram'] = (!empty($new_instance['instagram'])) ? esc_url_raw($new_instance['instagram']) : '';
        $instance['youtube'] = (!empty($new_instance['youtube'])) ? esc_url_raw($new_instance['youtube']) : '';
        $instance['whatsapp'] = (!empty($new_instance['whatsapp'])) ? esc_url_raw($new_instance['whatsapp']) : '';
        return $instance;
    }

    // Display the widget on the frontend
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        
        // Display the title
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        ?>
        <div class="social-media-icons">
            <?php if (!empty($instance['linkedin'])): ?>
                <a class="linkedin" href="<?php echo esc_url($instance['linkedin']); ?>" target="_blank" rel="noopener noreferrer">
                    <i class="icon icon-linkedin"></i> LinkedIn
                </a>
            <?php endif; ?>
            <?php if (!empty($instance['instagram'])): ?>
                <a class="instagram" href="<?php echo esc_url($instance['instagram']); ?>" target="_blank" rel="noopener noreferrer">
                    <i class="icon icon-instagram"></i> Instagram
                </a>
            <?php endif; ?>
            <?php if (!empty($instance['youtube'])): ?>
                <a class="youtube" href="<?php echo esc_url($instance['youtube']); ?>" target="_blank" rel="noopener noreferrer">
                    <i class="icon icon-youtube"></i> Youtube
                </a>
            <?php endif; ?>
            <?php if (!empty($instance['whatsapp'])): ?>
                <a class="whatsapp" href="<?php echo esc_url($instance['whatsapp']); ?>" target="_blank" rel="noopener noreferrer">
                    <i class="icon icon-whatsapp"></i> WhatsApp
                </a>
            <?php endif; ?>
        </div>
        <?php
        echo $args['after_widget'];
    }
}

// Define the Contact Info Widget class
class Goldland_Contact_Info_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'goldland_contact_info_widget',
            __('Degesa: Contact Info', 'degesa'),
            array('description' => __('A widget to display contact information', 'degesa'))
        );
    }

    // Backend widget form
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : __('Kontakt', 'degesa');
        $phone = !empty($instance['phone']) ? $instance['phone'] : '';
        $email = !empty($instance['email']) ? $instance['email'] : '';
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('phone'); ?>">Phone:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" type="text" value="<?php echo esc_attr($phone); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('email'); ?>">Email:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="email" value="<?php echo esc_attr($email); ?>">
        </p>

        <?php
    }

    // Save widget settings
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['phone'] = (!empty($new_instance['phone'])) ? sanitize_text_field($new_instance['phone']) : '';
        $instance['email'] = (!empty($new_instance['email'])) ? sanitize_email($new_instance['email']) : '';
        return $instance;
    }

    // Display the widget on the frontend
    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        // Display the title
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        ?>

        <div class="contact-info">
            <?php if (!empty($instance['phone'])): ?>
                <?php
                    $raw_phone = $instance['phone'];
                    $tel_phone = preg_replace('/[^+0-9]/', '', str_replace('(0)', '', $raw_phone));
                ?>
                <div><i class="icon icon-phone"></i> <a href="tel:<?php echo esc_attr($tel_phone); ?>"><?php echo esc_html($raw_phone); ?></a></div>
            <?php endif; ?>

            <?php if (!empty($instance['email'])): ?>
                <div><i class="icon icon-envelope"></i> <a href="mailto:<?php echo esc_attr($instance['email']); ?>"><?php echo esc_html($instance['email']); ?></a></div>
            <?php endif; ?>
        </div>

        <?php
        echo $args['after_widget'];
    }
}

new Goldland_widget_areas();