<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Get plugin dir
 *
 * @param string $file
 *
 * @return string
 */
function ube_get_plugin_dir( $file = '' ) {
	return plugin_dir_path( UBE_PLUGIN_FILE ) . $file;
}

/**
 * Get plugin file name
 *
 * @return string
 */
function ube_get_plugin_file() {
	return plugin_basename( UBE_PLUGIN_FILE );
}

/**
 * Get plugin url
 *
 * @param string $file
 *
 * @return string
 */
function ube_get_plugin_url( $file = '' ) {
	return trailingslashit( plugins_url( '/', UBE_PLUGIN_FILE ) ) . $file;
}

/**
 * Get plugin data
 *
 * @return array
 */
function ube_get_plugin_data() {
	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	return get_plugin_data( UBE_PLUGIN_FILE );
}

/**
 * Get plugin version
 *
 * @return string
 */
function ube_get_plugin_version() {
	$plugin_data = ube_get_plugin_data();

	return isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : '1.0.0';
}

/**
 * Load plugin file
 *
 * @param $file_path
 */
function ube_load_file( $file_path ) {
	include_once( UBE_ABSPATH . $file_path );
}

/**
 * Get template path for plugin template
 *
 * @return string
 */
function ube_template_path() {
	return apply_filters( 'ube_template_path', 'ube/' );
}

/**
 * Locate template
 *
 * @param $template_name
 * @param string $template_path
 * @param string $default_path
 *
 * @return string
 */
function ube_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = ube_template_path();
	}

	if ( ! $default_path ) {
		$default_path = ube_get_plugin_dir( 'templates/' );
	}

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
		)
	);

	// Get default template/
	if ( ! $template || UBE_TEMPLATE_DEBUG_MODE ) {
		$template = $default_path . $template_name;
	}

	// Return what we found.
	return apply_filters( 'ube_locate_template', $template, $template_name, $template_path );
}

/**
 * Include template
 *
 * @param $template_name
 * @param array $args
 * @param string $template_path
 * @param string $default_path
 */
function ube_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}

	$located = ube_locate_template( $template_name, $template_path, $default_path );

	if ( $located !== '' ) {
		$located = apply_filters( 'ube_get_template', $located, $template_name, $args, $template_path, $default_path );

		do_action( 'ube_before_template_part', $template_name, $template_path, $located, $args );

		include( $located );

		do_action( 'ube_after_template_part', $template_name, $template_path, $located, $args );
	}
}

/**
 * Load Admin Templates
 *
 * @param $template_name
 * @param array $args
 */
function ube_get_admin_template( $template_name, $args = array() ) {
	$template_path = ube_get_plugin_dir( 'inc/admin/templates/' . $template_name );
	$template_path = apply_filters( 'ube_locate_admin_template', $template_path, $template_name, $args );
	if ( is_readable( $template_path ) ) {
		do_action( 'ube_before_admin_template_part', $template_path, $template_path, $args );

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		include( $template_path );

		do_action( 'ube_after_admin_template_part', $template_path, $template_path, $args );
	}
}

/**
 * Get Admin template content
 *
 * @param $template_name
 * @param array $args
 *
 * @return false|string
 */
function ube_get_admin_template_content( $template_name, $args = array() ) {
	ob_start();
	ube_get_admin_template( $template_name, $args );

	return ob_get_clean();
}

/**
 * Get template content
 *
 * @param $template_name
 * @param array $args
 * @param string $template_path
 * @param string $default_path
 *
 * @return false|string
 */
function ube_get_template_content( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	ob_start();
	ube_get_template( $template_name, $args, $template_path, $default_path );

	return ob_get_clean();
}

/**
 * Get assets url
 *
 * @param $file
 *
 * @return string
 */
function ube_get_asset_url( $file ) {
	if ( ! file_exists( ube_get_plugin_dir( $file ) ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) {
		$normal_file = preg_replace( '/(.*)(\.min)((\.css)|(\.js))$/', '$1$3', $file );;
		if ( $normal_file != $file ) {
			if ( file_exists( ube_get_plugin_dir( $normal_file ) ) ) {
				return ube_get_plugin_url( $normal_file );
			}
		}
	}

	return ube_get_plugin_url( $file );
}

/**
 * Get current url
 *
 * @return string
 */
function ube_get_current_url() {
	return ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * @param null $type
 *
 * @return array
 */
function ube_get_page_templates( $type = null ) {
	$args = [
		'post_type'      => 'elementor_library',
		'posts_per_page' => - 1,
	];

	if ( $type ) {
		$args['tax_query'] = [
			[
				'taxonomy' => 'elementor_library_type',
				'field'    => 'slug',
				'terms'    => $type,
			],
		];
	}

	$page_templates = get_posts( $args );
	$options        = array();

	if ( ! empty( $page_templates ) && ! is_wp_error( $page_templates ) ) {
		foreach ( $page_templates as $post ) {
			$options[ $post->ID ] = $post->post_title;
		}
	}

	return $options;
}

/**
 * Start session
 */
function ube_session_start() {
	if ( function_exists( 'session_status' ) ) {
		if ( session_status() == PHP_SESSION_NONE ) {
			session_start();
		}
	} else {
		if ( session_id() == '' ) {
			session_start();
		}
	}
}

function ube_resize_image_max( $image, $max_width, $max_height ) {

	$upload_dir       = wp_get_upload_dir();
	$image_path       = pathinfo( $image );
	$image_upload_dir = $upload_dir['path'];
	$image_full_path  = $image_upload_dir . '/' . $image_path['basename'];
	$new_file_name    = $image_path['filename'] . '_' . $max_width . 'x' . $max_height . '.' . $image_path['extension'];
	$new_file_url     = $upload_dir['url'] . '/' . $new_file_name;
	$new_file_path    = $upload_dir['path'] . '/' . $new_file_name;

	if ( file_exists( $new_file_path ) ) {
		return $new_file_url;
	}
	if ( ! file_exists( $image_full_path ) ) {
		return false;
	}
	$image_size = getimagesize( $image_full_path );

	$w = $image_size[0];
	$h = $image_size[1];
	if ( ( ! $w ) || ( ! $h ) ) {
		return false;
	}
	if ( ( $w <= $max_width ) && ( $h <= $max_height ) ) {
		return $image;
	}
	$image_new = wp_get_image_editor( $image_full_path );
	if ( ! is_wp_error( $image ) ) {
		$image_new->resize( $max_width, $max_height, true );
		$image_new->save( $new_file_path );

		return $new_file_url;

	}

	return false;
}

/**
 * @param $data
 *
 * @return array|mixed
 */
function ube_recursive_sanitize_text_field( $data ) {
	if ( is_array( $data ) ) {
		foreach ( $data as $key => $value ) {
			$data[ $key ] = ube_recursive_sanitize_text_field( $data[ $key ] );
		}
	} else if ( is_object( $data ) ) {
		foreach ( $data as $key => $value ) {
			$data->{$key} = ube_recursive_sanitize_text_field( $data->{$key} );
		}
	} else if ( is_string( $data ) ) {
		$data = sanitize_text_field( $data );
	}

	return $data;
}

function ube_kses_post( $s ) {
	$s = wp_filter_kses( $s );
	$s = str_replace( '&amp;', '&', $s );

	return $s;
}