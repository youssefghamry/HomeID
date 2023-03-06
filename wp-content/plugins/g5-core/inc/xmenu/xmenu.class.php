<?php
if (!defined('ABSPATH')) exit;
if (!class_exists('G5Core_XMenu')) {
	final class G5Core_XMenu
	{
		private static $_instance;
		public static function getInstance()
		{
			if (self::$_instance == NULL) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Plugin construct
		 */
		public function init()
		{
			$this->add_actions();
			$this->add_filters();
		}


		private function add_actions()
		{
			/**
			 * Load admin assets for xmenu
			 */
			add_action('admin_print_styles-nav-menus.php', array($this, 'admin_load_assets'));

			/**
			 * Load frontend assets for xmenu
			 */
			add_action('wp_enqueue_scripts', array($this, 'frontend_load_assets'));

			/**
			 * XMenu Ajax
			 */
			add_action('wp_ajax_nopriv_xmenu_get_xmenu_panel', array($this, 'xmenu_panel'));
			add_action('wp_ajax_xmenu_get_xmenu_panel', array($this, 'xmenu_panel'));

			add_action('wp_ajax_nopriv_xmenu_save_menu', array($this, 'save_menu'));
			add_action('wp_ajax_xmenu_save_menu', array($this, 'save_menu'));

		}

		private function add_filters()
		{
			/**
			 * Filter for xmenu walker
			 */
			add_filter('wp_nav_menu_args', array($this, 'menu_walker'), 100);
		}

		public function admin_load_assets()
		{
			if (isset($_GET['action']) && ($_GET['action'] == 'locations')) {
				return;
			}

			GSF_Core_Icons_Popup::getInstance()->enqueue();

			wp_enqueue_style(G5CORE()->assets_handle('xmenu_admin'), G5CORE()->asset_url('inc/xmenu/assets/css/xmenu-admin.min.css'), array(), G5CORE()->plugin_ver());
			wp_enqueue_script(G5CORE()->assets_handle('xmenu_admin'), G5CORE()->asset_url('inc/xmenu/assets/js/xmenu-admin.min.js'), array(), G5CORE()->plugin_ver(), true);

			wp_localize_script(G5CORE()->assets_handle('xmenu_admin'), 'xmenu_meta', array(
				'data'     => $this->get_menu_data(),
				'ajax_url' => admin_url('admin-ajax.php')
			));
		}

		public function frontend_load_assets() {
			wp_enqueue_style(G5CORE()->assets_handle('xmenu-animate'), G5CORE()->asset_url('inc/xmenu/assets/css/animate.min.css'), array(), '3.5.1');
			wp_enqueue_script(G5CORE()->assets_handle('xmenu'), G5CORE()->asset_url('inc/xmenu/assets/js/xmenu.min.js'), array(), G5CORE()->plugin_ver(), true);
		}

		public function menu_walker($args)
		{
			if (isset($args['main_menu']) && ($args['main_menu'])) {
				require_once G5CORE()->plugin_dir('inc/xmenu/inc/xmenu-walker.php');
				if (isset($args['logo_center']) && ($args['logo_center'] === true)) {
					$args['logo_id'] = $this->get_location_logo($args);
				}
				$args['walker'] = new XMenuWalker();
				$args['menu_class'] .= ' x-nav-menu';
			}

			return $args;
		}

		public function get_location_logo($args) {
			// Get all locations
			$locations = get_nav_menu_locations();

			// Get object id by location
			$object = wp_get_nav_menu_object( $locations[$args['theme_location']] );

			// Get menu items by menu name
			$menu_items = wp_get_nav_menu_items( $object->name, $args );
			$arr_depth = array();
			foreach ($menu_items as $item) {
				if ($item->menu_item_parent == '0') {
					$arr_depth[] = $item->ID;
				}
			}
			$total_menu = count($arr_depth);
			if ($total_menu === 0) {
				return array();
			}
			$center = floor(($total_menu - 1)/2);

			return array(
				'first' => $arr_depth[0],
				'center' => $arr_depth[$center],
				'last' => $arr_depth[$total_menu - 1]
			);
		}



		/**
		 * Get XMENU Data
		 *
		 * @return array
		 */
		public function get_menu_data()
		{
			global $nav_menu_selected_id;
			$menu_items = wp_get_nav_menu_items($nav_menu_selected_id, array('post_status' => 'any'));
			$xmenu_data = array();
			if (is_array($menu_items)) {
				foreach ($menu_items as $key => $item) {
					$menu = array(
						'type_label'            => $item->type_label,
						'type'                  => $item->type,
						'menu-item-url'         => $item->url,
						'menu-item-title'       => $item->title,
						'menu-item-attr-title'  => $item->attr_title,
						'menu-item-target'      => $item->target,
						'menu-item-classes'     => join(' ', $item->classes),
						'menu-item-xfn'         => $item->xfn,
						'menu-item-description' => $item->description,
					);
					$menu_item_meta = get_post_meta($item->ID, $this->get_menu_meta_key(), true);
					if ($menu_item_meta) {
						$menu_item_meta = json_decode($menu_item_meta, true);
						$menu = array_merge($menu_item_meta, $menu);
					}
					$xmenu_data ['menu_' . $item->ID] = $menu;
				}
			}

			return $xmenu_data;
		}

		/**
		 * Get XMenu Panel
		 */
		public function xmenu_panel()
		{
			include_once G5CORE()->plugin_dir('inc/xmenu/templates/xmenu-panel.php');
			die();
		}

		/**
		 * Save Menu Items
		 */
		public function save_menu()
		{
			$nonce = $_POST['nonce'];
			if (!wp_verify_nonce($nonce, "XMENU_SAVE")) {
				echo 0;
				die();
			}
			$data = $_POST['data'];
			foreach ($data as $item) {
				$menu_id = $item['menu_id'];
				$term = wp_get_object_terms($menu_id, 'nav_menu');
				if (!is_array($term)) {
					continue;
				}
				$term = $term[0];

				$menu_list = wp_get_nav_menu_items($term->term_id, array('post_status' => 'any'));
				$menu_obj = null;
				foreach ($menu_list as $key => $menu_value) {
					if ($menu_value->ID == $menu_id) {
						$menu_obj = $menu_list[$key];
						break;
					}
				}
				if (!$menu_obj) {
					continue;
				}

				$args = array(
					'menu-item-db-id'       => $menu_id,
					'menu-item-object-id'   => $menu_obj->object_id,
					'menu-item-object'      => $menu_obj->object,
					'menu-item-parent-id'   => $menu_obj->menu_item_parent,
					'menu-item-position'    => $menu_obj->menu_order,
					'menu-item-type'        => $menu_obj->type,
					'menu-item-title'       => $item['data']['menu-item-title'],
					'menu-item-url'         => $menu_obj->type == 'custom' ? $item['data']['menu-item-url'] : $menu_obj->url,
					'menu-item-description' => $item['data']['menu-item-description'],
					'menu-item-attr-title'  => $item['data']['menu-item-attr-title'],
					'menu-item-target'      => $item['data']['menu-item-target'],
					'menu-item-classes'     => $item['data']['menu-item-classes'],
					'menu-item-xfn'         => $item['data']['menu-item-xfn'],
					'menu-item-status'      => $menu_obj->post_status,
				);

				/**
				 * Update menu item data
				 */
				$id = wp_update_nav_menu_item($term->term_id, $menu_id, $args);


				$xmenu_meta = array(
					'menu-item-featured-style'  => $item['data']['menu-item-featured-style'],
					'menu-item-featured-text'   => $item['data']['menu-item-featured-text'],
					'menu-item-icon'            => $item['data']['menu-item-icon'],
					'menu-submenu-width'        => $item['data']['menu-submenu-width'],
					'menu-submenu-custom-width' => $item['data']['menu-submenu-custom-width'],
					'menu-submenu-position'     => $item['data']['menu-submenu-position'],
					'menu-submenu-transition'   => $item['data']['menu-submenu-transition']
				);
				/**
				 * Update xmenu meta
				 */
				update_post_meta((int)$menu_id, $this->get_menu_meta_key(), wp_slash(json_encode($xmenu_meta)));
			}
			echo 1;
			die();
		}


		/**
		 * Get Menu Meta Key
		 */
		private function get_menu_meta_key() {
			return apply_filters('xmenu_meta_key', '_menu_item_xmenu_config');
		}
	}
}