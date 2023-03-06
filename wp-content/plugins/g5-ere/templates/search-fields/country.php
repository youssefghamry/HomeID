<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
/**
 * @var $prefix
 * @var $css_class_field
 */
$value = isset($_REQUEST['country']) ? wp_filter_kses(wp_unslash($_REQUEST['country'] )) : '';
$taxonomy = 'property-city';
$wrapper_classes = array(
	'form-group',
	'g5ere__search-field',
	'g5ere__sf-country'
);
if (isset($css_class_field)) {
	$wrapper_classes[] = $css_class_field;
}
$wrapper_class = implode(' ', $wrapper_classes);
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
	<label class="g5ere__s-label" for="<?php echo esc_attr($prefix)?>_country"><?php esc_html_e('Countries','g5-ere') ?></label>
	<select id="<?php echo esc_attr($prefix)?>_country" data-toggle="g5ere__select_location_filter" data-hide-disabled="true" data-target=".g5ere__sf-state" name="country" class="form-control selectpicker" data-live-search="true">
		<option value='' <?php selected($value,'')?>>
			<?php esc_html_e('All Countries', 'g5-ere') ?>
		</option>
		<?php
		$countries = ere_get_selected_countries();
		foreach ($countries as $key => $val) {
			echo '<option value="' . esc_attr($key)  . '" '. selected($key,$value,false) .'>' . esc_html($val)  . '</option>';
		}
		?>
	</select>
</div>
