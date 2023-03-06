<?php
/**
 * Setup theme install demo
 */
if (!defined('ABSPATH')) {
	exit('Direct script access denied.');
}
if (!class_exists('G5ThemeAddons_Setup_Install_Demo')) {
	class G5ThemeAddons_Setup_Install_Demo {
		private static $_instance;
		public static function getInstance()
		{
			if (self::$_instance == NULL) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function init() {
			add_filter('gid_demo_list', array($this, 'theme_demo_list'));
		}

		public function theme_demo_list($demo_list) {

			return array(
				'main' => array(
					'name' => esc_html__( 'Main (Use With WPBakery Page Builder)', 'homeId-addons' ),
					'thumbnail' => GTA()->plugin_url('assets/demo-data/main/preview.jpg'),
					'preview' => 'https://homeid.g5plus.net/',
					//'preview' => 'http://dev.g5plus.net/homeid/',
					'dir' => GTA()->plugin_dir('assets/demo-data/main/'),
					'theme' => 'homeid',
					'builder' => 'vc',
				),
				'elementor-main' => array(
					'name' => esc_html__( 'Main (Use With Elementor)', 'homeId-addons' ),
					'thumbnail' => GTA()->plugin_url('assets/demo-data/elementor-main/preview.jpg'),
					'preview' => 'https://homeid-elementor.g5plus.net/',
					//'preview' => 'http://dev.g5plus.net/homeid-elementor/',
					'dir' => GTA()->plugin_dir('assets/demo-data/elementor-main/'),
					'theme' => 'homeid',
					'builder' => 'elementor',
				),
				'demo-01' => array(
					'name' => esc_html__( 'Demo 01 (Use With WPBakery Page Builder)', 'homeId-addons' ),
					'thumbnail' => GTA()->plugin_url('assets/demo-data/demo-01/preview.jpg'),
					'preview' => 'https://demo1-homeid.g5plus.net/',
					//'preview' => 'http://dev.g5plus.net/homeid-demo1/',
					'dir' => GTA()->plugin_dir('assets/demo-data/demo-01/'),
					'theme' => 'homeid',
					'builder' => 'vc',
				),
				'elementor-demo-01' => array(
					'name' => esc_html__( 'Demo 01 (Use With Elementor)', 'homeId-addons' ),
					'thumbnail' => GTA()->plugin_url('assets/demo-data/elementor-demo-01/preview.jpg'),
					'preview' => 'https://homeid-elementor-demo1.g5plus.net/',
					//'preview' => 'http://dev.g5plus.net/homeid-elementor-demo1/',
					'dir' => GTA()->plugin_dir('assets/demo-data/elementor-demo-01/'),
					'theme' => 'homeid',
					'builder' => 'elementor',
				),
				'demo-02' => array(
					'name' => esc_html__( 'Demo 02 (Use With WPBakery Page Builder)', 'homeId-addons' ),
					'thumbnail' => GTA()->plugin_url('assets/demo-data/demo-02/preview.jpg'),
					'preview' => 'https://demo2-homeid.g5plus.net/',
					//'preview' => 'http://dev.g5plus.net/homeid-demo2/',
					'dir' => GTA()->plugin_dir('assets/demo-data/demo-02/'),
					'theme' => 'homeid',
					'builder' => 'vc',
				),
				'elementor-demo-02' => array(
					'name' => esc_html__( 'Demo 02 (Use With Elementor)', 'homeId-addons' ),
					'thumbnail' => GTA()->plugin_url('assets/demo-data/elementor-demo-02/preview.jpg'),
					'preview' => 'https://homeid-elementor-demo2.g5plus.net/',
					//'preview' => 'http://dev.g5plus.net/homeid-elementor-demo2/',
					'dir' => GTA()->plugin_dir('assets/demo-data/elementor-demo-02/'),
					'theme' => 'homeid',
					'builder' => 'elementor',
				),
				'demo-03' => array(
					'name' => esc_html__( 'Demo 03 (Use With WPBakery Page Builder)', 'homeId-addons' ),
					'thumbnail' => GTA()->plugin_url('assets/demo-data/demo-03/preview.jpg'),
					'preview' => 'https://demo3-homeid.g5plus.net/',
					//'preview' => 'http://dev.g5plus.net/homeid-demo3/',
					'dir' => GTA()->plugin_dir('assets/demo-data/demo-03/'),
					'theme' => 'homeid',
					'builder' => 'vc',
				),
				'elementor-demo-03' => array(
					'name' => esc_html__( 'Demo 03 (Use With Elementor)', 'homeId-addons' ),
					'thumbnail' => GTA()->plugin_url('assets/demo-data/elementor-demo-03/preview.jpg'),
					'preview' => 'https://homeid-elementor-demo3.g5plus.net/',
					//'preview' => 'http://dev.g5plus.net/homeid-elementor-demo3/',
					'dir' => GTA()->plugin_dir('assets/demo-data/elementor-demo-03/'),
					'theme' => 'homeid',
					'builder' => 'elementor',
				),
				'demo-04' => array(
					'name' => esc_html__( 'Demo 04 (Use With WPBakery Page Builder)', 'homeId-addons' ),
					'thumbnail' => GTA()->plugin_url('assets/demo-data/demo-04/preview.jpg'),
					'preview' => 'https://demo4-homeid.g5plus.net/',
					//'preview' => 'http://dev.g5plus.net/homeid-demo4/',
					'dir' => GTA()->plugin_dir('assets/demo-data/demo-04/'),
					'theme' => 'homeid',
					'builder' => 'vc',
				),
				'elementor-demo-04' => array(
					'name' => esc_html__( 'Demo 04 (Use With Elementor)', 'homeId-addons' ),
					'thumbnail' => GTA()->plugin_url('assets/demo-data/elementor-demo-04/preview.jpg'),
					'preview' => 'https://homeid-elementor-demo4.g5plus.net/',
					//'preview' => 'http://dev.g5plus.net/homeid-elementor-demo4/',
					'dir' => GTA()->plugin_dir('assets/demo-data/elementor-demo-04/'),
					'theme' => 'homeid',
					'builder' => 'elementor',
				),
				'demo-05' => array(
					'name' => esc_html__( 'Demo 05 (Use With WPBakery Page Builder)', 'homeId-addons' ),
					'thumbnail' => GTA()->plugin_url('assets/demo-data/demo-05/preview.jpg'),
					'preview' => 'https://demo5-homeid.g5plus.net/',
					//'preview' => 'http://dev.g5plus.net/homeid-demo5/',
					'dir' => GTA()->plugin_dir('assets/demo-data/demo-05/'),
					'theme' => 'homeid',
					'builder' => 'vc',
				),
				'elementor-demo-05' => array(
					'name' => esc_html__( 'Demo 05 (Use With Elementor)', 'homeId-addons' ),
					'thumbnail' => GTA()->plugin_url('assets/demo-data/elementor-demo-05/preview.jpg'),
					'preview' => 'https://homeid-elementor-demo5.g5plus.net/',
					//'preview' => 'http://dev.g5plus.net/homeid-elementor-demo5/',
					'dir' => GTA()->plugin_dir('assets/demo-data/elementor-demo-05/'),
					'theme' => 'homeid',
					'builder' => 'elementor',
				),
				'demo-06' => array(
					'name' => esc_html__( 'Demo 06 (Use With WPBakery Page Builder)', 'homeId-addons' ),
					'thumbnail' => GTA()->plugin_url('assets/demo-data/demo-06/preview.jpg'),
					'preview' => 'https://demo6-homeid.g5plus.net/',
					//'preview' => 'http://dev.g5plus.net/homeid-demo6/',
					'dir' => GTA()->plugin_dir('assets/demo-data/demo-06/'),
					'theme' => 'homeid',
					'builder' => 'vc',
				),
				'elementor-demo-06' => array(
					'name' => esc_html__( 'Demo 06 (Use With Elementor)', 'homeId-addons' ),
					'thumbnail' => GTA()->plugin_url('assets/demo-data/elementor-demo-06/preview.jpg'),
					'preview' => 'https://homeid-elementor-demo6.g5plus.net/',
					//'preview' => 'http://dev.g5plus.net/homeid-elementor-demo6/',
					'dir' => GTA()->plugin_dir('assets/demo-data/elementor-demo-06/'),
					'theme' => 'homeid',
					'builder' => 'elementor',
				),
			);
		}
	}
}