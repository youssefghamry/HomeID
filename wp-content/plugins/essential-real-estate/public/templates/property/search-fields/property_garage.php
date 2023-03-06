<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 7/15/2017
 * Time: 11:20 PM
 * @var $css_class_field
 * @var $request_garage
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$garage_list = ere_get_option('garage_list','1,2,3,4,5,6,7,8,9,10');
$garage_array = explode( ',', $garage_list );
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="garage" title="<?php esc_attr_e('Property Garages', 'essential-real-estate') ?>"
            class="search-field form-control" data-default-value="">
        <option value="">
            <?php esc_html_e('Any Garages', 'essential-real-estate') ?>
        </option>
	    <?php if (is_array($garage_array) && !empty($garage_array) ): ?>
		    <?php foreach ($garage_array as $n) : ?>
			    <option <?php selected($request_garage,$n) ?> value="<?php echo esc_attr($n)?>"><?php echo esc_html($n)?></option>
		    <?php endforeach; ?>
	    <?php endif; ?>
    </select>
</div>