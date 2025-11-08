<?php
/**
 * Plugin Name: Custom Timeline By Uchit
 * Description: Advanced timeline widget for Elementor with nested containers
 * Version: 1.0.0
 * Author: Uchit Chakma
 * License: GPLv2 or later
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Main Plugin Class
 */
class CustomTimelineByUchit {
    
    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }
    
        public function init() {
        // Check if Elementor is installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return;
        }

        // Register widget
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        
        // Enqueue scripts and styles
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueue_styles']);
        add_action('elementor/frontend/after_register_scripts', [$this, 'enqueue_scripts']);
        
        // Add admin menu
        add_action('admin_menu', [$this, 'add_admin_menu']);
        
        // Add AJAX handlers
        add_action('wp_ajax_timeline_get_template_content', [$this, 'ajax_get_template_content']);
        add_action('wp_ajax_nopriv_timeline_get_template_content', [$this, 'ajax_get_template_content']);
    }


        // Add this new method after enqueue_styles
    public function ajax_get_template_content() {
        // Verify nonce if needed
        if (!isset($_POST['template_id']) || empty($_POST['template_id'])) {
            wp_send_json_error(['message' => 'No template ID provided']);
        }
        
        $template_id = intval($_POST['template_id']);
        
        if ($template_id <= 0) {
            wp_send_json_error(['message' => 'Invalid template ID']);
        }
        
        $content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($template_id);
        
        if (empty($content)) {
            wp_send_json_error(['message' => 'Template not found or empty']);
        }
        
        wp_send_json_success(['content' => $content]);
    }
    
    
    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'custom-timeline-by-uchit'),
            '<strong>' . esc_html__('Custom Timeline By Uchit', 'custom-timeline-by-uchit') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'custom-timeline-by-uchit') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
    
    public function add_admin_menu() {
        add_menu_page(
            __('Timeline Settings', 'custom-timeline-by-uchit'),
            __('Timeline Widget', 'custom-timeline-by-uchit'),
            'manage_options',
            'custom-timeline-settings',
            [$this, 'admin_page_content'],
            'dashicons-timeline',
            99
        );
    }
    
    public function admin_page_content() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Custom Timeline Widget', 'custom-timeline-by-uchit'); ?></h1>
            <div class="card">
                <h2><?php echo esc_html__('About This Plugin', 'custom-timeline-by-uchit'); ?></h2>
                <p><?php echo esc_html__('This plugin adds a powerful timeline widget to Elementor with nested containers.', 'custom-timeline-by-uchit'); ?></p>
                <h3><?php echo esc_html__('Features:', 'custom-timeline-by-uchit'); ?></h3>
                <ul>
                    <li>✅ Nested Elementor containers for left and right content</li>
                    <li>✅ Animated scrolling timeline with moving dot</li>
                    <li>✅ Fully responsive design</li>
                    <li>✅ Extensive styling options</li>
                    <li>✅ Add unlimited timeline sections</li>
                    <li>✅ Mobile-optimized layout</li>
                </ul>
                <h3><?php echo esc_html__('How to Use:', 'custom-timeline-by-uchit'); ?></h3>
                <ol>
                    <li>Edit a page with Elementor</li>
                    <li>Search for "Timeline" in the widget panel</li>
                    <li>Drag the widget to your page</li>
                    <li>Add timeline items and customize</li>
                </ol>
            </div>
            <div class="card">
                <h2><?php echo esc_html__('Plugin Information', 'custom-timeline-by-uchit'); ?></h2>
                <p><strong><?php echo esc_html__('Version:', 'custom-timeline-by-uchit'); ?></strong> 1.0.0</p>
                <p><strong><?php echo esc_html__('Author:', 'custom-timeline-by-uchit'); ?></strong> Uchit Chakma</p>
            </div>
        </div>
        <?php
    }
    
    public function register_widgets($widgets_manager) {
        require_once(__DIR__ . '/includes/custom-timeline-widget.php');
        $widgets_manager->register(new \CustomTimelineWidget());
    }
    
    public function enqueue_scripts() {
        wp_register_script(
            'custom-timeline-widget',
            plugins_url('assets/js/main.js', __FILE__),
            ['jquery', 'elementor-frontend'],
            '1.0.0',
            true
        );
        wp_enqueue_script('custom-timeline-widget');
    }
    
    public function enqueue_styles() {
        wp_enqueue_style(
            'custom-timeline-widget',
            plugins_url('assets/css/style.css', __FILE__),
            [],
            '1.0.0'
        );
    }
}

// Initialize the plugin
CustomTimelineByUchit::instance();