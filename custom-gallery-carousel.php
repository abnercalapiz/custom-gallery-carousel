<?php
/**
 * Plugin Name: Custom Gallery Carousel
 * Description: Custom Gallery Carousel is a responsive image gallery built with Owl Carousel. It lets users define custom gallery fields and integrate seamlessly with WooCommerce galleries, offering optimized settings for mobile, tablet, and desktop devices.
 * Version: 1.5
 * Author: Jezweb
 * Author URI: https://www.jezweb.com.au/
 * Plugin URI: /wp-admin/options-general.php?page=cgc_owl_custom_gallery_carousel
 */

class Custom_Gallery_Carousel {

    private $plugin_basename;
    private $acf_active = false;

    public function __construct() {
        $this->plugin_basename = plugin_basename(__FILE__);
        $this->acf_active = function_exists('get_field');
        $this->init();
    }

    private function init() {
        if (is_admin()) {
            add_filter('plugin_action_links_' . $this->plugin_basename, [$this, 'settings_link']);
            add_action('admin_init', [$this, 'register_settings']);
            add_action('admin_menu', [$this, 'register_options_page']);
            add_action('admin_enqueue_scripts', [$this, 'admin_styles']);
        }
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_shortcode('custom_gallery_carousel', [$this, 'shortcode']);
    }

    public function settings_link($links) {
        $settings_link = '<a href="options-general.php?page=cgc_owl_custom_gallery_carousel">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }

    public function enqueue_assets() {
        wp_enqueue_style('owl-carousel', plugin_dir_url(__FILE__) . 'css/owl.carousel.min.css', [], '2.3.4');
        wp_enqueue_style('owl-theme-default', plugin_dir_url(__FILE__) . 'css/owl.theme.default.min.css', [], '2.3.4');
        wp_enqueue_script('owl-carousel', plugin_dir_url(__FILE__) . 'js/owl.carousel.min.js', ['jquery'], '2.3.4', true);
        wp_enqueue_style('font-awesome-solid', plugin_dir_url(__FILE__) . 'css/solid.min.css', [], '6.7.2');  // FontAwesome
        wp_enqueue_style('custom-gallery-carousel-style', plugin_dir_url(__FILE__) . 'css/style.css', [], '1.3');
        
        // Enqueue lightbox assets
        wp_enqueue_style('custom-gallery-carousel-lightbox', plugin_dir_url(__FILE__) . 'css/lightbox.css', [], '1.0');
        wp_enqueue_script('custom-gallery-carousel-lightbox', plugin_dir_url(__FILE__) . 'js/lightbox.js', ['jquery'], '1.0', true);
    }

    public function admin_styles() {
        wp_enqueue_style('custom-gallery-carousel-admin-style', plugin_dir_url(__FILE__) . 'css/admin-style.css', [], '1.3');
    }

    public function register_settings() {
        add_option($this->get_option_name('field'), 'gallery');
        add_option($this->get_option_name('woocommerce_integration'), 0);
        add_option($this->get_option_name('mobile_items'), 3);
        add_option($this->get_option_name('tablet_items'), 5);
        add_option($this->get_option_name('desktop_items'), 5);
        add_option($this->get_option_name('main_nav'), 0);
        add_option($this->get_option_name('pagination'), 0);
        add_option($this->get_option_name('thumbnail_nav'), 0);
        add_option($this->get_option_name('thumbnail_style'), 'bottom');
        add_option($this->get_option_name('thumbnail_spacing'), 10);
        add_option($this->get_option_name('show_image_title'), 0);
        add_option($this->get_option_name('use_medium_large_thumb'), 0);


        register_setting('cgc_owl_custom_gallery_carousel_options_group', $this->get_option_name('field'), 'sanitize_text_field');
        register_setting('cgc_owl_custom_gallery_carousel_options_group', $this->get_option_name('woocommerce_integration'));
        register_setting('cgc_owl_custom_gallery_carousel_options_group', $this->get_option_name('mobile_items'), 'absint');
        register_setting('cgc_owl_custom_gallery_carousel_options_group', $this->get_option_name('tablet_items'), 'absint');
        register_setting('cgc_owl_custom_gallery_carousel_options_group', $this->get_option_name('desktop_items'), 'absint');
        register_setting('cgc_owl_custom_gallery_carousel_options_group', $this->get_option_name('main_nav'));
        register_setting('cgc_owl_custom_gallery_carousel_options_group', $this->get_option_name('pagination'));
        register_setting('cgc_owl_custom_gallery_carousel_options_group', $this->get_option_name('thumbnail_nav'));
        register_setting('cgc_owl_custom_gallery_carousel_options_group', $this->get_option_name('thumbnail_style'), 'sanitize_text_field');
        register_setting('cgc_owl_custom_gallery_carousel_options_group', $this->get_option_name('thumbnail_spacing'), 'absint');
        register_setting('cgc_owl_custom_gallery_carousel_options_group', $this->get_option_name('show_image_title'));
        register_setting('cgc_owl_custom_gallery_carousel_options_group', $this->get_option_name('use_medium_large_thumb'));
    }

    private function get_option_name($key) {
        return 'cgc_owl_custom_gallery_carousel_' . $key;
    }


    public function register_options_page() {
        add_options_page(
            'Custom Carousel Settings',        // Page title
            'Custom Carousel',                // Menu title
            'manage_options',               // Capability
            'cgc_owl_custom_gallery_carousel',  // Menu slug (unique ID)
            [$this, 'options_page']        // Callback function to display the page
        );
    }




    public function options_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (isset($_POST['submit'])) {
            if (check_admin_referer('cgc_owl_custom_gallery_carousel_options_verify')) { // Nonce verification

                // Use $this->get_option_name() consistently for all options.
				update_option($this->get_option_name('field'), sanitize_text_field($_POST[$this->get_option_name('field')]));
                update_option($this->get_option_name('woocommerce_integration'), isset($_POST[$this->get_option_name('woocommerce_integration')]) ? 1 : 0);
                update_option($this->get_option_name('mobile_items'), absint($_POST[$this->get_option_name('mobile_items')]));
                update_option($this->get_option_name('tablet_items'), absint($_POST[$this->get_option_name('tablet_items')]));
                update_option($this->get_option_name('desktop_items'), absint($_POST[$this->get_option_name('desktop_items')]));
                update_option($this->get_option_name('main_nav'), isset($_POST[$this->get_option_name('main_nav')]) ? 1 : 0);
                update_option($this->get_option_name('pagination'), isset($_POST[$this->get_option_name('pagination')]) ? 1 : 0);
                update_option($this->get_option_name('thumbnail_nav'), isset($_POST[$this->get_option_name('thumbnail_nav')]) ? 1 : 0);
                update_option($this->get_option_name('thumbnail_style'), sanitize_text_field($_POST[$this->get_option_name('thumbnail_style')]));
                update_option($this->get_option_name('thumbnail_spacing'), absint($_POST[$this->get_option_name('thumbnail_spacing')]));
                update_option($this->get_option_name('show_image_title'), isset($_POST[$this->get_option_name('show_image_title')]) ? 1 : 0);
                update_option($this->get_option_name('use_medium_large_thumb'), isset($_POST[$this->get_option_name('use_medium_large_thumb')]) ? 1 : 0);

                echo '<div class="updated"><p>Settings saved.</p></div>';
            } else {
                wp_die('Nonce verification failed.'); // Handle nonce errors properly.
            }
        }

		require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php'; 
    }


    private function get_gallery_images($post_id) {
        $post_id = absint($post_id);

        if ($this->is_woocommerce_integration_enabled() && function_exists('wc_get_product')) {
            $product = wc_get_product($post_id);
            if ($product) {
                $gallery_images = $product->get_gallery_image_ids();
                return array_map(function ($image_id) {
                    return ['ID' => $image_id];
                }, $gallery_images);
            }
        } else if ($this->acf_active) {
            // Only use ACF functions if ACF is active
            $field = sanitize_text_field(get_option($this->get_option_name('field'), 'gallery'));
            $acf_gallery = get_field($field, $post_id);
            if ($acf_gallery && is_array($acf_gallery)) {
                return $acf_gallery;
            }
        } else {
            // Fallback to standard WordPress gallery if ACF is not active
            // Try to get attached images
            $args = array(
                'post_type' => 'attachment',
                'posts_per_page' => -1,
                'post_parent' => $post_id,
                'order' => 'ASC',
                'orderby' => 'menu_order'
            );
            $attachments = get_posts($args);
            
            if ($attachments) {
                return array_map(function($attachment) {
                    return ['ID' => $attachment->ID];
                }, $attachments);
            }
        }

        return []; // Return an empty array if no images are found.
    }




    private function is_woocommerce_integration_enabled() {
        return get_option($this->get_option_name('woocommerce_integration'), 0) == 1;
    }

    private function get_carousel_settings() {
        return [
            'mobile_items'  => absint(get_option($this->get_option_name('mobile_items'), 3)),
            'tablet_items'  => absint(get_option($this->get_option_name('tablet_items'), 5)),
            'desktop_items' => absint(get_option($this->get_option_name('desktop_items'), 5)),
            'main_nav'      => get_option($this->get_option_name('main_nav'), 0) == 1,
            'pagination'    => get_option($this->get_option_name('pagination'), 0) == 1,
            'thumbnail_nav' => get_option($this->get_option_name('thumbnail_nav'), 0) == 1,
            'thumbnail_style' => get_option($this->get_option_name('thumbnail_style'), 'bottom'),
            'thumbnail_spacing' => absint(get_option($this->get_option_name('thumbnail_spacing'), 10)),
            'show_image_title' => get_option($this->get_option_name('show_image_title'), 0) == 1,
            'use_medium_large_thumb' => get_option($this->get_option_name('use_medium_large_thumb'), 0) == 1,
        ];
    }


    public function shortcode() {
        global $post;
        $gallery = $this->get_gallery_images($post->ID);

        // Initialize gallery as an empty array if it's not already an array
        if (!is_array($gallery)) {
            $gallery = [];
        }

        // Get featured image and add it to gallery if it exists
        $featured_image_id = get_post_thumbnail_id($post->ID);
        if ($featured_image_id) {
            // If gallery is empty, create a new array with just the featured image
            if (empty($gallery)) {
                $gallery = [['ID' => $featured_image_id]];
            } else {
                // Otherwise, add it to the beginning of the existing gallery
                array_unshift($gallery, ['ID' => $featured_image_id]);
            }
        }

        // If there are no images at all (no gallery and no featured image), return empty
        if (empty($gallery)) {
            return '';
        }

        $settings = $this->get_carousel_settings();

        ob_start();
        require plugin_dir_path(__FILE__) . 'templates/carousel.php';
        return ob_get_clean();
    }
}

// Check if ACF is active before initializing the plugin
function cgc_check_dependencies() {
    // Include plugin.php if needed for is_plugin_active()
    if (!function_exists('is_plugin_active')) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    
    // Check if ACF is active using multiple methods for better detection
    $acf_active = (
        function_exists('get_field') || 
        class_exists('ACF') || 
        class_exists('acf') ||
        (function_exists('is_plugin_active') && (
            is_plugin_active('advanced-custom-fields/acf.php') ||
            is_plugin_active('advanced-custom-fields-pro/acf.php')
        ))
    );
    
    if (!$acf_active) {
        // ACF is not active, show notice
        add_action('admin_notices', 'cgc_acf_missing_notice');
    }
    
    // Initialize the plugin regardless, as we have a fallback
    new Custom_Gallery_Carousel();
}

// Admin notice for when ACF is missing
function cgc_acf_missing_notice() {
    // Check if user has dismissed this notice
    $user_id = get_current_user_id();
    if (get_user_meta($user_id, 'cgc_dismiss_acf_notice', true)) {
        return;
    }
    
    // Double-check ACF status before showing notice
    if (function_exists('get_field') || class_exists('ACF') || class_exists('acf')) {
        return; // ACF is actually active, don't show notice
    }
    ?>
    <div class="notice notice-warning is-dismissible" data-notice="cgc_acf_notice">
        <p><?php _e('Custom Gallery Carousel works best with Advanced Custom Fields plugin. For full functionality, please install and activate ACF.', 'custom-gallery-carousel'); ?></p>
    </div>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $(document).on('click', '.notice[data-notice="cgc_acf_notice"] .notice-dismiss', function() {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'cgc_dismiss_acf_notice',
                    nonce: '<?php echo wp_create_nonce('cgc_dismiss_notice'); ?>'
                }
            });
        });
    });
    </script>
    <?php
}

// Handle AJAX dismissal of notice
add_action('wp_ajax_cgc_dismiss_acf_notice', 'cgc_handle_dismiss_notice');
function cgc_handle_dismiss_notice() {
    if (!wp_verify_nonce($_POST['nonce'], 'cgc_dismiss_notice')) {
        wp_die();
    }
    
    $user_id = get_current_user_id();
    update_user_meta($user_id, 'cgc_dismiss_acf_notice', true);
    wp_die();
}

// Initialize the plugin
add_action('plugins_loaded', 'cgc_check_dependencies');