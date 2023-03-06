<?php
/**
 * The template for displaying button-set.tpl.php
 *
 * @var $settings
 * @var $value
 */
$field_classes = array(
	'wpb_vc_param_value',
	$settings['param_name'],
	"{$settings['type']}_field"
);
$field_class = implode(' ', array_filter($field_classes));
$on_text = isset($settings['on_text']) ? $settings['on_text'] : esc_html__('On','g5-element');
$off_text = isset($settings['off_text']) ? $settings['off_text'] : esc_html__('Off','g5-element');
$value_inline = isset($settings['value_inline']) ? $settings['value_inline'] : true;
?>
<div class="g5element-vc-switch-wrapper">
	<div class="g5element-field-switch-inner <?php echo esc_attr($value_inline ? 'value-inline' : ''); ?>">
		<label>
			<input class="<?php echo esc_attr($field_class) ?>" type="checkbox" <?php g5element_attr_the_checked('on', $value) ?>
			       name="<?php echo esc_attr($settings['param_name']) ?>"
               value="<?php echo esc_attr($value); ?>"/>
			<div class="g5element-field-switch-button" data-switch-on="<?php echo esc_attr($on_text); ?>" data-switch-off="<?php echo esc_attr($off_text); ?>">
				<span class="g5element-field-switch-off"><?php echo esc_html($off_text); ?></span>
				<span class="g5element-field-switch-on"><?php echo esc_html($on_text); ?></span>
			</div>
		</label>
	</div>
</div>
