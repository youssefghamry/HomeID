<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Checkbox')) {
	class GSF_Field_Checkbox extends GSF_Field
	{
		function enqueue() {
			wp_enqueue_style(GSF()->assetsHandle('field_checkbox'), GSF()->helper()->getAssetUrl('fields/checkbox/assets/checkbox.min.css'), array(), GSF()->pluginVer());
			wp_enqueue_script(GSF()->assetsHandle('field_checkbox'), GSF()->helper()->getAssetUrl('fields/checkbox/assets/checkbox.min.js'), array(), GSF()->pluginVer(), true);
		}
		function renderContent()
		{
			$field_value = $this->getFieldValue();
			?>
			<div class="gsf-field-checkbox-inner">
				<label>
					<input data-field-control="" type="checkbox" <?php GSF()->helper()->render_attr_iff($field_value, 'checked', 'checked'); ?>
					       name="<?php $this->theInputName(); ?>"
					       value="1"/>
                    <?php if (isset($this->_setting['desc'])): ?>
					<span><?php echo wp_kses_post($this->_setting['desc']) ?></span>
                    <?php endif; ?>
				</label>
			</div>
		<?php
		}
		function renderDescription() {}

		function getEmptyValue() {
			return '';
		}
	}
}