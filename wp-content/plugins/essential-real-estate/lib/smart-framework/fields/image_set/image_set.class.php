<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Image_Set')) {
	class GSF_Field_Image_Set extends GSF_Field
	{
		function enqueue() {
			wp_enqueue_style(GSF()->assetsHandle('field_image_set'));
			wp_enqueue_script(GSF()->assetsHandle('field_image_set'), GSF()->helper()->getAssetUrl('fields/image_set/assets/image-set.min.js'), array(), GSF()->pluginVer(), true);
		}
		function renderContent()
		{
			if (!isset($this->_setting['options']) || !is_array($this->_setting['options'])) {
				return;
			}

			$field_value = $this->getFieldValue();

			$is_multiple = isset($this->_setting['multiple']) && $this->_setting['multiple'];
			if ($is_multiple && !is_array($field_value)) {
				$field_value = (array)$field_value;
			}
			$allowClear = (!isset($this->_setting['multiple']) || !$this->_setting['multiple'])
				&& (isset($this->_setting['allow_clear']) && $this->_setting['allow_clear']);

			$img_attr = array();
			if (isset($this->_setting['width']) && !empty($this->_setting['width'])) {
				$img_attr['width'] = $this->_setting['width'];
			}
			if (isset($this->_setting['height']) && !empty($this->_setting['height'])) {
				$img_attr['height'] = $this->_setting['height'];
			}

			$field_attrs = array();
			if ($is_multiple) {
                $field_attrs['data-multiple'] = 'true';
			}
			?>
			<div class="gsf-field-image_set-inner" <?php GSF()->helper()->render_html_attr($field_attrs); ?>>
				<?php foreach ($this->_setting['options'] as $key => $value): ?>
					<?php $key = (string)$key; ?>
					<label>
						<?php if (isset($this->_setting['multiple']) && $this->_setting['multiple']): ?>
							<input data-field-control="" type="checkbox" name="<?php $this->theInputName(); ?>[]" value="<?php echo esc_attr($key); ?>" <?php GSF()->helper()->theChecked($key, $field_value) ?>/>
						<?php else: ?>
							<input data-field-control="" type="radio" name="<?php $this->theInputName(); ?>" value="<?php echo esc_attr($key); ?>" <?php GSF()->helper()->theChecked($key, $field_value) ?>/>
						<?php endif;?>
						<?php
							$label = isset($value['label']) ? $value['label'] : '';
							$img_alt = isset($value['label']) ? $value['label'] : $key;
							$img_src = isset($value['img']) ? $value['img'] : $value;
						?>
						<img class="<?php echo esc_attr($allowClear ? 'gsf-allow-clear' : ''); ?>"
						     src="<?php echo esc_url($img_src); ?>"
							<?php GSF()->helper()->render_html_attr($img_attr); ?>
							 alt="<?php echo esc_attr($img_alt); ?>"/>
						<?php if ($label !== ''): ?>
							<p><?php echo esc_html($label) ?></p>
						<?php endif; ?>

					</label>
				<?php endforeach;?>
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
			if (isset($this->_setting['multiple']) && $this->_setting['multiple']) {
				$default = array();
			}
			$field_default = isset($this->_setting['default']) ? $this->_setting['default'] : $default;
			return $field_default;
		}
	}
}