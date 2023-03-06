<?php
if (!class_exists('XMenuWalker')) {
	class XMenuWalker extends Walker_Nav_Menu {
		/**
		 * What the class handles.
		 *
		 * @since 3.0.0
		 * @access public
		 * @var string
		 *
		 * @see Walker::$tree_type
		 */
		public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

		private $_parent_menu_id;

		/**
		 * Database fields to use.
		 *
		 * @since 3.0.0
		 * @access public
		 * @todo Decouple this.
		 * @var array
		 *
		 * @see Walker::$db_fields
		 */
		public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

		public function __construct() {
		}

		/**
		 * Starts the list before the elements are added.
		 *
		 * @since 3.0.0
		 *
		 * @see Walker::start_lvl()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   An array of wp_nav_menu() arguments.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$sub_menu_class = array('sub-menu');
			$sub_menu_style = array();


            $transition = apply_filters('xmenu_submenu_transition', '',$args);

			/**
			 * Get XMENU Meta
			 */
			$xmenu_meta = get_post_meta($this->_parent_menu_id, '_menu_item_xmenu_config', true );
			if ($xmenu_meta) {
				$xmenu_meta = json_decode($xmenu_meta, true);
				if (is_array($xmenu_meta)) {
					if (isset($xmenu_meta['menu-submenu-width'])) {
						if ($xmenu_meta['menu-submenu-width'] === 'custom') {
							$sub_menu_style[] = 'width:' . esc_attr($xmenu_meta['menu-submenu-custom-width']);
							$sub_menu_class[] = 'x-submenu-custom-width';
						}
					}

                    if (isset($xmenu_meta['menu-submenu-transition']) && !empty($xmenu_meta['menu-submenu-transition'])) {
                        $transition = esc_attr($xmenu_meta['menu-submenu-transition']);
                    }
				}
			}

            if (!empty($transition)) {
                $sub_menu_class[] = 'x-animated';
                $sub_menu_class[] = $transition;
            }


			$sub_menu_class = apply_filters('xmenu_submenu_class',$sub_menu_class,$args);

			$indent = str_repeat("\t", $depth);
			$output .= sprintf("\n$indent<ul class=\"%s\" style=\"%s\">\n", join(' ', $sub_menu_class), join(';', $sub_menu_style));
		}

		/**
		 * Ends the list of after the elements are added.
		 *
		 * @since 3.0.0
		 *
		 * @see Walker::end_lvl()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   An array of wp_nav_menu() arguments.
		 */
		public function end_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat("\t", $depth);
			$output .= "$indent</ul>\n";
		}

		/**
		 * Starts the element output.
		 *
		 * @since 3.0.0
		 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
		 *
		 * @see Walker::start_el()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item   Menu item data object.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   An array of wp_nav_menu() arguments.
		 * @param int    $id     Current item ID.
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			if (($depth === 0) && isset($args->logo_id) && !empty($args->logo_id) && ($item->ID === $args->logo_id['first'])) {
				$customize_classes = isset($args->before_menu_classes) ? $args->before_menu_classes : '';
				ob_start();
				G5CORE()->get_template( 'header/customize.php', array(
					'type'     => 'desktop',
					'location' => 'before_menu',
				) );
				$logo_html = ob_get_clean();
				$customize_classes .= ' menu-item menu-item-customize menu-item-before-menu ' . $args->customize_left_classes;
				$output .= "<li class=\"{$customize_classes}\">\n";
				$output .= $logo_html;
				$output .= "</li>\n";
			}

			$xmenu_meta = get_post_meta($item->ID, '_menu_item_xmenu_config', true );
			if ($xmenu_meta) {
				$xmenu_meta = json_decode($xmenu_meta, true);
			}

			$this->_parent_menu_id = $item->ID;

			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;

			if (is_array($xmenu_meta)) {
				if (isset($xmenu_meta['menu-submenu-position'])) {
					if (empty($xmenu_meta['menu-submenu-position'])) {
						$data_position = apply_filters('xmenu_submenu_position', '');
					}
					else {
						$data_position = esc_attr($xmenu_meta['menu-submenu-position']);
					}
					$classes[] = 'x-submenu-position-' . $data_position;
				}

				if (isset($xmenu_meta['menu-submenu-width']) && ($xmenu_meta['menu-submenu-width'] !== 'custom')) {
					$classes[] = 'x-submenu-width-' . esc_attr($xmenu_meta['menu-submenu-width']);
				}
			}

			if (($depth > 0) && ($item->object === G5CORE()->cpt()->get_xmenu_mega_post_type())) {
				$classes[] = 'x-is-mega-menu';
			}

			/**
			 * Filters the arguments for a single nav menu item.
			 *
			 * @since 4.4.0
			 *
			 * @param array  $args  An array of arguments.
			 * @param object $item  Menu item data object.
			 * @param int    $depth Depth of menu item. Used for padding.
			 */
			$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

			/**
			 * Filters the CSS class(es) applied to a menu item's list item element.
			 *
			 * @since 3.0.0
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
			 * @param object $item    The current menu item.
			 * @param array  $args    An array of wp_nav_menu() arguments.
			 * @param int    $depth   Depth of menu item. Used for padding.
			 */
			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			/**
			 * Filters the ID applied to a menu item's list item element.
			 *
			 * @since 3.0.1
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
			 * @param object $item    The current menu item.
			 * @param array  $args    An array of wp_nav_menu() arguments.
			 * @param int    $depth   Depth of menu item. Used for padding.
			 */
			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

            $data_transition = apply_filters('xmenu_submenu_transition', '',$args);

			if (is_array($xmenu_meta)) {
				if (isset($xmenu_meta['menu-submenu-transition']) && !empty($xmenu_meta['menu-submenu-transition'])) {
                    $data_transition = esc_attr($xmenu_meta['menu-submenu-transition']);
				}
			}
			$data_transition = $data_transition ? ' data-transition="' . esc_attr( $data_transition ) . '"' : '';

			$output .= $indent . '<li' . $id . $class_names . $data_transition .'>';

			$atts = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
			$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
			$atts['href']   = ! empty( $item->url )        ? $item->url        : '';


			/**
			 * Filters the HTML attributes applied to a menu item's anchor element.
			 *
			 * @since 3.6.0
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param array $atts {
			 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
			 *
			 *     @type string $title  Title attribute.
			 *     @type string $target Target attribute.
			 *     @type string $rel    The rel attribute.
			 *     @type string $href   The href attribute.
			 * }
			 * @param object $item  The current menu item.
			 * @param array  $args  An array of wp_nav_menu() arguments.
			 * @param int    $depth Depth of menu item. Used for padding.
			 */
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			/** This filter is documented in wp-includes/post-template.php */
			$title = apply_filters( 'the_title', $item->title, $item->ID );

			/**
			 * Filters a menu item's title.
			 *
			 * @since 4.4.0
			 *
			 * @param string $title The menu item's title.
			 * @param object $item  The current menu item.
			 * @param array  $args  An array of wp_nav_menu() arguments.
			 * @param int    $depth Depth of menu item. Used for padding.
			 */
			$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

			/**
			 * If Menu Item is Mega Menu
			 */
			if (($item->object === G5CORE()->cpt()->get_xmenu_mega_post_type())) {
				$object_id = $item->object_id;

				$content = g5core_get_content_block($object_id, G5CORE()->cpt()->get_xmenu_mega_post_type());

				if ($depth > 0) {
					$output .= '<div class="x-mega-sub-menu">';
					$output .= $content;
					$output .= '</div>';
				}
				else {
					$output .= $content;
				}
			}
			else {
				$item_output = $args->before;
				$item_output .= '<a class="x-menu-link" '. $attributes .'>';

				/**
				 * Menu Icon
				 */
				if (is_array($xmenu_meta)) {
					if (isset($xmenu_meta['menu-item-icon']) && !empty($xmenu_meta['menu-item-icon'])) {
						$item_output .= '<i class="' . esc_attr($xmenu_meta['menu-item-icon']) . '"></i> ';
					}
				}

				$caret_icon = '';
				if (in_array('menu-item-has-children', $item->classes)) {
					$caret_icon = "<span class='x-caret'></span>";
				}

				$item_output .= $args->link_before . "<span class='x-menu-link-text'>$title</span>" . $caret_icon . $args->link_after;


				if (is_array($xmenu_meta)) {
					if (isset($xmenu_meta['menu-item-featured-style']) && ($xmenu_meta['menu-item-featured-style'] != '')) {
						$menu_featured_text = '';

						switch ($xmenu_meta['menu-item-featured-style']) {
							case 'hot': {
								$menu_featured_text = esc_html__('Hot','g5-core');
								break;
							}
							case 'new': {
								$menu_featured_text = esc_html__('New','g5-core');
								break;
							}
							default: {
								$menu_featured_text = esc_html__('Featured','g5-core');
							}
						}

                        if (isset($xmenu_meta['menu-item-featured-text']) && ($xmenu_meta['menu-item-featured-text'] != '')) {
                            $menu_featured_text = $xmenu_meta['menu-item-featured-text'];
                        }

						$item_output .= sprintf('<span class="x-menu-link-featured x-menu-link-featured-%s">%s</span>',
							esc_attr($xmenu_meta['menu-item-featured-style']),
							esc_html($menu_featured_text));
					}
				}
				$item_output .= '</a>';

				/**
				 * Menu Description
				 */
				if (!empty($item->description)) {
					$item_output .= '<p class="x-description">' . wp_kses_post($item->description) . '</p>';
				}

				$item_output .= $args->after;

				/**
				 * Filters a menu item's starting output.
				 *
				 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
				 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
				 * no filter for modifying the opening and closing `<li>` for a menu item.
				 *
				 * @since 3.0.0
				 *
				 * @param string $item_output The menu item's starting HTML output.
				 * @param object $item        Menu item data object.
				 * @param int    $depth       Depth of menu item. Used for padding.
				 * @param array  $args        An array of wp_nav_menu() arguments.
				 */
				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}
		}

		/**
		 * Ends the element output, if needed.
		 *
		 * @since 3.0.0
		 *
		 * @see Walker::end_el()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item   Page data object. Not used.
		 * @param int    $depth  Depth of page. Not Used.
		 * @param array  $args   An array of wp_nav_menu() arguments.
		 */
		public function end_el( &$output, $item, $depth = 0, $args = array() ) {
			$output .= "</li>\n";

			if (($depth === 0) && isset($args->logo_id) && !empty($args->logo_id) && ($item->ID === $args->logo_id['center'])) {
				ob_start();
				G5CORE()->get_template( 'header/desktop/logo.php', array('classes' => 'logo-center') );
				$logo_html = ob_get_clean();
				$output .= '</ul>' . PHP_EOL;
				$output .= $logo_html;

				$customize_classes = isset($args->after_menu_classes) ? $args->after_menu_classes : '';
				$menu_class = str_replace(array('content-left', 'content-right', 'content-center', 'content-fill'),
					'',
					$args->menu_class);
				$menu_class = preg_replace('/\s{2,}/', ' ', trim($menu_class));
				if (!empty($customize_classes)) {
					$menu_class .= ' ' . $customize_classes;
				}

				$output .= '<ul id="xmenu_right" class="' . $menu_class . '">' . PHP_EOL;
			}
			if (($depth === 0) && isset($args->logo_id) && !empty($args->logo_id) && ($item->ID === $args->logo_id['last'])) {
				ob_start();
				G5CORE()->get_template( 'header/customize.php', array(
					'type'     => 'desktop',
					'location' => 'after_menu',
					'classes' => ''
				) );
				$logo_html = ob_get_clean();
				$output .= '<li class="menu-item menu-item-customize menu-item-after-menu ' . $args->customize_right_classes . '">' . PHP_EOL;
				$output .= $logo_html;
				$output .= '</li>' . PHP_EOL;
			}
		}
	}
}