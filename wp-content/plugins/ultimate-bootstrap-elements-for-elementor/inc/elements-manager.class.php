<?php
if (!defined('ABSPATH')) {
    exit;
}

class UBE_Elements_Manager
{
    private static $_instance = null;

    public static function get_instance() {
        return self::$_instance === null ? self::$_instance = new self() : self::$_instance;
    }

    public function init() {
        add_action('elementor/widgets/widgets_registered', array($this, 'register_widgets'));
        add_action('elementor/elements/categories_registered', array($this, 'register_widget_categories'));
    }

    /**
     * Register UBE Addons category for widget element
     *
     * @since 1.0.0
     *
     * @param $elements_manager
     */
    public function register_widget_categories($elements_manager) {
        $elements_manager->add_category('ultimate-bootstrap-elements', array(
            'title' => __('UBE Addons', 'ube'),
            'icon'  => 'font',
        ), 1);
    }

    public function register_widgets() {

        include_once UBE_ABSPATH . 'inc/abstracts/element-abstract.class.php';
	    include_once UBE_ABSPATH . 'inc/abstracts/element-slider-abstract.class.php';
        include_once UBE_ABSPATH . 'inc/abstracts/element-grid-abstract.class.php';
        /**
         * Register Widget
         */
        $index = 0;
        foreach (ube_get_elements_enabled() as $el) {
            $el_class = "UBE_Element_{$el}";
            if (class_exists($el_class) && ($el_class::is_enabled())) {
                Elementor\Plugin::instance()->widgets_manager->register_widget_type(new $el_class);
            }
        }
    }
}