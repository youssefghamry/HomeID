<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define default system color
 *
 * @return array[]
 */
function ube_get_default_system_colors() {
    $colors = array(
        'border' => array(
            '_id' => 'border',
            'title' => esc_html__('Border', 'ube'),
            'color' => '#eee',
        ),
        'dark' => array(
            '_id' => 'dark',
            'title' => esc_html__('Dark', 'ube'),
            'color' => '#333',
        ),
        'light' => array(
            '_id' => 'light',
            'title' => esc_html__('Light', 'ube'),
            'color' => '#fafafa',
        ),
        'gray' => array(
            '_id' => 'gray',
            'title' => esc_html__('Gray', 'ube'),
            'color' => '#8f8f8f',
        ),
        'muted' => array(
            '_id' => 'muted',
            'title' => esc_html__('Muted', 'ube'),
            'color' => '#ababab',
        ),
        'placeholder' => array(
            '_id' => 'placeholder',
            'title' => esc_html__('Placeholder', 'ube'),
            'color' => '#b6b6b6',
        ),
    );
    foreach ($colors as &$v) {
        if (isset($v['color'])) {
            $v['color'] = strtoupper($v['color']);
        }
    }

    return $colors;
}

/**
 * Get setting system colors
 *
 * @return array[]
 */
function ube_get_setting_default_colors() {
    $colors = apply_filters('ube_get_setting_default_colors', array(
        'primary' => '#cc3366',
        'secondary' => '#6c757d',
        'border' => '#eee',
        'dark' => '#333',
        'light' => '#fafafa',
        'gray' => '#8f8f8f',
        'muted' => '#ababab',
        'placeholder' => '#b6b6b6',
    ));

    foreach ($colors as $k => $v) {
        $colors[$k] = strtoupper($v);
    }
    return $colors;
}

/**
 * Change color in site settings
 */
function ube_change_color_settings() {
    $kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
    $kit = Elementor\Plugin::$instance->documents->get( $kit_id );

    if (empty($kit)) {
    	return false;
    }

    $meta_key = Elementor\Core\Settings\Page\Manager::META_KEY;
    $kit_raw_settings = $kit->get_meta( $meta_key );

    $ube_colors = ube_get_default_system_colors();

    if (!is_array($kit_raw_settings)) {
        $kit_raw_settings = array();
    }

    if ( !isset( $kit_raw_settings['system_colors'] ) ) {
        $kit_raw_settings['system_colors'] = array();
    }

    $current_color_keys = array();
    foreach ($kit_raw_settings['system_colors'] as $k => $v) {
        $current_color_keys[] = $v['_id'];
    }

    $default_keys = [
        1 => 'primary',
        2 => 'secondary',
        3 => 'text',
        4 => 'accent',
    ];

    $scheme_obj = Elementor\Plugin::$instance->schemes_manager->get_scheme( 'color' );

    $default_colors = $scheme_obj->get_scheme();

    foreach ( $default_colors as $index => $color ) {
        if (!in_array($default_keys[$index], $current_color_keys)) {
            $kit->add_repeater_row( 'system_colors', [
                '_id' => $default_keys[$index],
                'title' => $color['title'],
                'color' => strtoupper( $color['value'] ),
            ] );
        }
    }

    $is_update_default_key = false;

    foreach ( $ube_colors as $k => $v ) {
        $color_id = $v['_id'];

        if (!in_array($color_id, $current_color_keys)) {
            $is_update_default_key = true;
        }
    }

    if ($is_update_default_key) {
        foreach ( $ube_colors as $k => $v ) {
            if (!in_array($v['_id'], $current_color_keys)) {
                $kit->add_repeater_row( 'system_colors', [
                    '_id' => $v['_id'],
                    'title' => $v['title'],
                    'color' => strtoupper( $v['color'] ),
                ] );
            }
        }
    }
    return true;
}

/**
 * Set system color when init plugin/update plugin
 */
function ube_set_default_colors() {
    $default_colors = ube_get_setting_default_colors();

    $kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
    $kit = Elementor\Plugin::$instance->documents->get( $kit_id );

    if (empty($kit)) {
    	return false;
    }

    $meta_key = Elementor\Core\Settings\Page\Manager::META_KEY;

    $kit_raw_settings = $kit->get_meta( $meta_key );

    $ube_default_color_previous = get_option('ube_default_color_previous', false);

    $is_change_default = false;

    if ($ube_default_color_previous === false) {
        foreach ($default_colors as $k => $v) {
            foreach ($kit_raw_settings['system_colors'] as $s_key => $s_value) {
                if ($s_value['_id'] === $k) {
                    $kit_raw_settings['system_colors'][$s_key]['color'] = $v;
                    $is_change_default = true;
                }
            }
        }
    }
    else {
        foreach ($default_colors as $k => $v) {
            if (!isset($ube_default_color_previous[$k])) {
                foreach ($kit_raw_settings['system_colors'] as $s_key => $s_value) {
                    if ($s_value['_id'] === $k) {
                        $kit_raw_settings['system_colors'][$s_key]['color'] = $v;

                        $is_change_default = true;
                    }
                }
            }
        }
    }

    if ($is_change_default) {
        $kit->update_meta($meta_key, $kit_raw_settings);

        $post_css = Elementor\Core\Files\CSS\Post::create( $kit_id );
        $post_css->delete();
    }

    update_option('ube_default_color_previous', $default_colors);

    return true;
}

function ube_get_system_colors() {
    if (isset($GLOBALS['ube_system_colors'])) {
        return $GLOBALS['ube_system_colors'];
    }
    $kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();

    $kit = Elementor\Plugin::$instance->documents->get( $kit_id );

    if (empty($kit)) {
    	return array();
    }

    $meta_key = Elementor\Core\Settings\Page\Manager::META_KEY;

    $kit_raw_settings = $kit->get_meta( $meta_key );
    $GLOBALS['ube_system_colors'] = isset($kit_raw_settings['system_colors']) ? $kit_raw_settings['system_colors'] : array();
	$GLOBALS['ube_system_colors'] = apply_filters('ube_system_colors', $GLOBALS['ube_system_colors']);

    return $GLOBALS['ube_system_colors'];
}
