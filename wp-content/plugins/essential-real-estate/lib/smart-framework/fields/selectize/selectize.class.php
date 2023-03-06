<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Selectize')) {
    class GSF_Field_Selectize extends GSF_Field
    {
        public function enqueue()
        {
            wp_enqueue_style('selectize');
            wp_enqueue_script('selectize');

            wp_enqueue_script(GSF()->assetsHandle('field_selectize'), GSF()->helper()->getAssetUrl('fields/selectize/assets/selectize.min.js'), array(), GSF()->pluginVer(), true);
        }

        function renderContent()
        {
            $create_link = '';
            $edit_link = '';
            if (isset($this->_setting['data'])) {
                switch ($this->_setting['data']) {
                    case 'preset':
                        if (isset($this->_setting['data-option'])) {
                            $this->_setting['options'] = GSF()->adminThemeOption()->getPresetOptionKeys($this->_setting['data-option']);
                        }

                        if (isset($this->_setting['create_link']) && !empty($this->_setting['create_link'])) {
                            $create_link = $this->_setting['create_link'];
                        }

                        if (isset($this->_setting['edit_link']) && !empty($this->_setting['edit_link'])) {
                            $edit_link =  $this->_setting['edit_link'] . '&_gsf_preset={{value}}';
                        }
                        break;
                    case 'sidebar':
                        $this->_setting['options'] = GSF()->helper()->getSidebars();
                        if (!isset($this->_setting['create_link']) || ($this->_setting['create_link'] !== false)) {
                            $create_link = admin_url('widgets.php');
                        }
                        break;
                    case 'menu':
                        $this->_setting['options'] = GSF()->helper()->getMenus();

                        if (!isset($this->_setting['create_link']) || ($this->_setting['create_link'] !== false)) {
                            $create_link = admin_url('nav-menus.php?action=edit&menu=0');
                        }

                        if (!isset($this->_setting['edit_link']) || ($this->_setting['edit_link'] !== false)) {
                            $edit_link = admin_url('nav-menus.php?menu={{value}}');
                        }
                        break;
                    case 'taxonomy':
                        $this->_setting['options'] = GSF()->helper()->getTaxonomies(isset($this->_setting['data_args']) ? $this->_setting['data_args'] : array());
                        $tax_name = isset($this->_setting['data_args']['taxonomy']) ? $this->_setting['data_args']['taxonomy'] : 'category';
                        $post_type = isset($this->_setting['data-post-type']) ? '&post_type=' . $this->_setting['data-post-type'] : '';

                        if (!isset($this->_setting['create_link']) || ($this->_setting['create_link'] !== false)) {
                            $create_link = admin_url(sprintf('edit-tags.php?taxonomy=%s%s',
                                $tax_name,
                                $post_type));
                        }

                        if (!isset($this->_setting['edit_link']) || ($this->_setting['edit_link'] !== false)) {
                            $edit_link = admin_url(sprintf('term.php?taxonomy=%s%s&tag_ID={{value}}',
                                $tax_name,
                                $post_type));
                        }

                        break;
                    default:
                        if (isset($this->_setting['data_args']) && !isset($this->_setting['data_args']['post_type'])) {
                            $this->_setting['data_args']['post_type'] = $this->_setting['data'];
                        }
                        $link_post_type = '';
                        if (isset($this->_setting['data_args']) && isset($this->_setting['data_args']['post_type'])) {
                            $link_post_type = $this->_setting['data_args']['post_type'];
                        }
                        if (!isset($this->_setting['create_link']) || ($this->_setting['create_link'] !== false)) {
                            $create_link = admin_url(sprintf('post-new.php%s', empty($link_post_type) ? '' : '?post_type=' . $link_post_type));
                        }

                        if (!isset($this->_setting['edit_link']) || ($this->_setting['edit_link'] !== false)) {
                            $edit_link = admin_url(sprintf('post.php?action=edit&post={{value}}'));
                        }


	                    if (isset($this->_setting['create_link']) && !empty($this->_setting['create_link'])) {
		                    $create_link = $this->_setting['create_link'];
	                    }

	                    if (isset($this->_setting['edit_link']) && !empty($this->_setting['edit_link'])) {
		                    $edit_link =  $this->_setting['edit_link'] . '&post={{value}}';
	                    }

                        $this->_setting['options'] = GSF()->helper()->getPosts(isset($this->_setting['data_args']) ? $this->_setting['data_args'] : array('post_type' => $this->_setting['data']));
                        break;
                }
            }

            if (!isset($this->_setting['options']) || !is_array($this->_setting['options'])) {
                $this->_setting['options'] = array();
            }
            $field_value = $this->getFieldValue();

            $is_deselect = isset($this->_setting['allow_clear']) ? $this->_setting['allow_clear'] : false;
            $multiple = isset($this->_setting['multiple']) ? $this->_setting['multiple'] : false;
            $is_link_target = isset($this->_setting['link_target']) ? $this->_setting['multiple'] : true;

            if ($multiple) {
	            $edit_link = '';
            }


            $attr = array();
            if (isset($this->_setting['width'])) {
                $attr['style'] = "width:{$this->_setting['width']}";
            }

            if (isset($this->_setting['tags']) && $this->_setting['tags']) {
                $attr['data-tags'] = 'true';
                if (is_array($this->_setting['options'])) {
                    $attr['multiple'] = 'multiple';
                    $multiple = true;

                    if (is_array($field_value)) {
                        foreach($field_value as $value) {
                            $this->_setting['options'][$value] = $value;
                        }
                    }
                }
            } else if ($multiple) {
                $attr['multiple'] = 'multiple';
            }
	        if (isset($this->_setting['drag']) && $this->_setting['drag']) {
		        $attr['data-drag'] = 'true';
            }

            ?>
            <div class="gsf-field-selectize-inner">
                <?php if (isset($this->_setting['tags']) && $this->_setting['tags'] && count($this->_setting['options']) == 0 ): ?>
                    <input data-field-control=""
                           data-field-no-change="true"
                           data-field-set-value="true"
                           type="text" name="<?php $this->theInputName(); ?>"
                           class="gsf-selectize" value="<?php echo esc_attr($field_value); ?>"
                        <?php GSF()->helper()->render_html_attr($attr); ?>/>
                <?php else: ?>
                    <?php
	                if ($is_deselect) {
                        $attr['data-allow-clear'] = 'true';
	                }
	                if (isset($this->_setting['placeholder'])) {
		                $attr['data-placeholder'] = $this->_setting['placeholder'];
	                }
	                $attr['data-value'] = $field_value;
                    ?>
                    <select data-field-control="" class="gsf-selectize"
                            data-field-no-change="true"
                            data-field-set-value="true"
                            name="<?php $this->theInputName(); ?><?php echo esc_attr($multiple ? '[]' : ''); ?>"
	                    <?php GSF()->helper()->render_html_attr($attr); ?>>
                        <option class="empty-select" value="" <?php GSF()->helper()->theSelected('', $field_value); ?>></option>
                        <?php foreach ($this->_setting['options'] as $key => $value): ?>
                            <?php if (is_array($value)): ?>
                                <optgroup label="<?php echo esc_attr($key); ?>">
                                    <?php foreach ($value as $opt_key => $opt_value): ?>
                                        <option
                                            value="<?php echo esc_attr($opt_key); ?>" <?php GSF()->helper()->theSelected($opt_key, $field_value); ?>><?php echo esc_html($opt_value); ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php else:; ?>
                                <option value="<?php echo esc_attr($key); ?>" <?php GSF()->helper()->theSelected($key, $field_value); ?>><?php echo esc_html($value); ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($edit_link)): ?>
                        <a <?php GSF()->helper()->render_attr_iff($is_link_target, 'target', '_blank'); ?> href="#" class="gsf-selectize-edit-link button button-primary" data-link="<?php echo esc_url($edit_link); ?>"><?php esc_html_e('Edit', 'smart-framework') ?></a>
                    <?php endif; ?>
                    <?php if (!empty($create_link)): ?>
                        <a <?php GSF()->helper()->render_attr_iff($is_link_target, 'target', '_blank'); ?> class="gsf-selectize-create-link button button-primary" href="<?php echo esc_url($create_link) ?>" target="_blank" title="<?php esc_attr_e('Add New', 'smart-framework') ?>"><?php esc_html_e('Add New', 'smart-framework'); ?></a>
                    <?php endif; ?>
                <?php endif;?>
            </div>
            <?php
        }

        /**
         * Get default value
         *
         * @return array | string
         */
        function getDefault() {
            $default = '';
            $inherit = isset($this->_setting['inherit']) ? $this->_setting['inherit'] : false;
            if ($inherit) {
                $default = '-1';
            }
            if (isset($this->_setting['multiple']) && $this->_setting['multiple']) {
                $default = array();
            }
            $field_default = isset($this->_setting['default']) ? $this->_setting['default'] : $default;
            return $field_default;
        }
    }
}