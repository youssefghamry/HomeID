<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Color')) {
	class GSF_Field_Color extends GSF_Field
	{
		public function enqueue()
		{
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_script('wp-color-picker-alpha');

			wp_enqueue_style(GSF()->assetsHandle('field_color'), GSF()->helper()->getAssetUrl('fields/color/assets/color.min.css'), array(), GSF()->pluginVer());
			wp_enqueue_script(GSF()->assetsHandle('field_color'), GSF()->helper()->getAssetUrl('fields/color/assets/color.min.js'), array(), GSF()->pluginVer(), true);
		}

		function renderContent()
		{
			$field_value = $this->getFieldValue();

			$alpha = isset($this->_setting['alpha']) ? $this->_setting['alpha'] : false;
			$validate = array(
				'maxlength' => 11,
				'pattern'   => '^(#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})|(transparent))$'
			);
			if ($alpha) {
				$validate = array(
					'maxlength' => 22,
					'pattern'   => '^(transparent|(#(([a-fA-F0-9]{6})|([a-fA-F0-9]{3})))|(rgba\(\d+,\d+,\d+,\d?(\.\d+)*\)))$'
				);
			}
			?>
			<div class="gsf-field-color-inner">
				<input data-field-control=""
					<?php GSF()->helper()->render_attr_iff($alpha, 'data-field-no-change', 'true'); ?>
					<?php GSF()->helper()->render_attr_iff($alpha, 'data-alpha', 'true'); ?>
                       type="text"
                       maxlength="<?php echo esc_attr($validate['maxlength']); ?>"
                       pattern="<?php echo esc_attr($validate['pattern']); ?>"
                       name="<?php $this->theInputName(); ?>"
                       value="<?php echo esc_attr($field_value); ?>"/>
			</div>
		<?php
		}
	}
}