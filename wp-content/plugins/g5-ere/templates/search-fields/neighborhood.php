<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
/**
 * @var $prefix
 * @var $css_class_field
 */
$value = isset($_REQUEST['neighborhood']) ? wp_filter_kses(wp_unslash($_REQUEST['neighborhood'] )) : '';
$taxonomy = 'property-neighborhood';
$wrapper_classes = array(
	'form-group',
	'g5ere__search-field',
	'g5ere__sf-neighborhood'
);
if (isset($css_class_field)) {
	$wrapper_classes[] = $css_class_field;
}
$wrapper_class = implode(' ', $wrapper_classes);
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
	<label class="g5ere__s-label" for="<?php echo esc_attr( $prefix ) ?>neighborhood"><?php esc_html_e('Neighborhoods','g5-ere') ?></label>
	<select id="<?php echo esc_attr( $prefix ) ?>neighborhood" name="neighborhood" class="form-control selectpicker" data-live-search="true">
		<option value='' <?php selected($value,'')?>>
			<?php esc_html_e('All Neighborhoods', 'g5-ere') ?>
		</option>
		<?php
		$prop_terms = get_terms(array(
				'taxonomy' => $taxonomy,
				'orderby' => 'name',
				'order' => 'ASC',
				'hide_empty' => false,
				'parent' => 0
			)
		);
		g5ere_hirarchical_options($taxonomy, $prop_terms, $value);
		?>
	</select>
</div>
