<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
	exit('Direct script access denied.');
}
if (!class_exists('G5Blog_Elements')) {
	class G5Blog_Elements {
		private static $_instance;
		public static function getInstance()
		{
			if (self::$_instance == NULL) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function init() {
			add_filter('ube_get_element_configs',array($this,'change_ube_get_element_configs'));
			add_filter('ube_autoload_file_dir',array($this,'change_ube_autoload_file_dir'),10,2);
			add_filter('ube_element_listing_query_args',array($this,'set_query_args'),10,2);
			add_action( 'init', array( $this, 'register_scripts' ) );
		}

		public function change_ube_get_element_configs($configs) {
			$g5_elements = isset($configs['g5_elements']) ? $configs['g5_elements'] : array(
				'title' => esc_html__('G5 Elements', 'ube'),
				'items' => array()
			);

			$g5_elements['items']['G5_Posts'] = array(
				'title' => esc_html__('G5 Posts','homeId')
			);

			$g5_elements['items']['G5_Posts_Slider'] = array(
				'title' => esc_html__('G5 Posts Slider','homeId')
			);

			$configs['g5_elements'] = $g5_elements;
			return $configs;
		}

		public function change_ube_autoload_file_dir($path, $class) {
			$prefix = 'UBE_Element_G5_';
			if (strpos($class,$prefix) === 0) {
				$file_name = substr($class,strlen($prefix));
				$file_name = str_replace( '_', '-', $file_name );
				$file_name = strtolower( $file_name );
				return  G5BLOG()->plugin_dir( "inc/elements/{$file_name}.class.php" );
			}
			return $path;
		}

		public function register_scripts() {
		}

		public function get_config_post_layout()
		{
			$config = apply_filters('g5blog_elements_post_layout', array(
				'large-image' => array(
					'label' => esc_html__('Large Image', 'g5-blog'),
					'priority' => 10,
				),
				'medium-image' => array(
					'label' => esc_html__('Medium Image', 'g5-blog'),
					'priority' => 20,
				),
				'grid' => array(
					'label' => esc_html__('Grid', 'g5-blog'),
					'priority' => 30,
				),
				'masonry' => array(
					'label' => esc_html__('Masonry', 'g5-blog'),
					'priority' => 40,
				),
			));

			uasort( $config, 'g5blog_sort_by_order_callback' );


			$result = array();
			foreach ($config as $k => $v) {
				$result[$k] = $v['label'];
			}
			return $result;

		}

		public function get_config_post_slider_layout()
		{
			$config = apply_filters('g5blog_elements_post_slider_layout', array(
				'grid' => array(
					'label' => esc_html__('Grid', 'g5-blog'),
					'priority' => 10,
				),
			));

			uasort( $config, 'g5blog_sort_by_order_callback' );


			$result = array();
			foreach ($config as $k => $v) {
				$result[$k] = $v['label'];
			}
			return $result;

		}

		public function set_query_args($query_args,$atts) {
			if ($query_args['post_type'] === 'post') {
				$query_args['orderby'] = $atts['orderby'];
				$query_args['meta_key'] = ( 'meta_value' == $atts['orderby'] || 'meta_value_num' == $atts['orderby'] ) ? $atts['meta_key'] : '';

				if (!empty($atts['cat'])) {
					$query_args['category__in'] = array_filter($atts['cat'],'absint');
				}

				if (!empty($atts['tag'])) {
					$query_args['tag__in'] = array_filter($atts['tag'],'absint');
				}

				if ( $atts['time_filter'] !== 'none' ) {
					$query_args['date_query'] = $this ->get_time_filter_query( $atts['time_filter'] );
				}

				if ( ! empty( $atts['ids']) ) {
					$query_args['post__in'] = array_filter($atts['ids'],'absint');
				}
			}

			return $query_args;
		}
		public function get_time_filter_query($time_filter = null)
		{
			$date_query = array();

			switch ($time_filter) {
				// Today posts
				case 'today':
					$date_query = array(
						array(
							'after' => '1 day ago', // should not escaped because will be passed to WP_Query
						),
					);
					break;
				// Today + Yesterday posts
				case 'yesterday':
					$date_query = array(
						array(
							'after' => '2 day ago', // should not escaped because will be passed to WP_Query
						),
					);
					break;
				// Week posts
				case 'week':
					$date_query = array(
						array(
							'after' => '1 week ago', // should not escaped because will be passed to WP_Query
						),
					);
					break;
				// Month posts
				case 'month':
					$date_query = array(
						array(
							'after' => '1 month ago', // should not escaped because will be passed to WP_Query
						),
					);
					break;
				// Year posts
				case 'year':
					$date_query = array(
						array(
							'after' => '1 year ago', // should not escaped because will be passed to WP_Query
						),
					);
					break;
			}
			return $date_query;
		}


	}
}