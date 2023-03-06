<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 18/11/16
 * Time: 5:46 PM
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $hide_property_fields;
$measurement_units           = ere_get_measurement_units();
$measurement_units_land_area = ere_get_measurement_units_land_area();
?>
<div class="card property-fields-wrap">
    <div class="card-body">
        <div class="card-title property-fields-title">
            <h2><?php esc_html_e( 'Property Details', 'g5-ere' ); ?></h2>
        </div>
        <div class="property-fields property-detail row">
			<?php if ( ! in_array( "property_size", $hide_property_fields ) ) { ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="property_size"><?php echo wp_kses_post( sprintf( __( 'Size (%s) %s', 'g5-ere' ), $measurement_units, ere_required_field( 'property_size' ) ) ); ?></label>
                        <input type="number" id="property_size" class="form-control" name="property_size" value="">
                    </div>
                </div>
			<?php } ?>

			<?php if ( ! in_array( "property_land", $hide_property_fields ) ) { ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label
                                for="property_land"><?php echo wp_kses_post( sprintf( __( 'Land Area (%s) %s', 'g5-ere' ), $measurement_units_land_area, ere_required_field( 'property_land' ) ) ); ?></label>
                        <input type="number" id="property_land" class="form-control" name="property_land" value="">
                    </div>
                </div>
			<?php } ?>
			<?php if ( ! in_array( "property_rooms", $hide_property_fields ) ) { ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label
                                for="property_rooms"><?php echo esc_html__( 'Rooms', 'g5-ere' ) . ere_required_field( 'property_rooms' ); ?></label>
                        <input type="number" id="property_rooms" class="form-control" name="property_rooms"
                               value="">
                    </div>
                </div>
			<?php } ?>
			<?php if ( ! in_array( "property_bedrooms", $hide_property_fields ) ) { ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label
                                for="property_bedrooms"><?php echo esc_html__( 'Bedrooms', 'g5-ere' ) . ere_required_field( 'property_bedrooms' ); ?></label>
                        <input type="number" id="property_bedrooms" class="form-control" name="property_bedrooms"
                               value="">
                    </div>
                </div>
			<?php } ?>

			<?php if ( ! in_array( "property_bathrooms", $hide_property_fields ) ) { ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label
                                for="property_bathrooms"><?php echo esc_html__( 'Bathrooms', 'g5-ere' ) . ere_required_field( 'property_bathrooms' ); ?></label>
                        <input type="number" id="property_bathrooms" class="form-control" name="property_bathrooms"
                               value="">
                    </div>
                </div>
			<?php } ?>

			<?php if ( ! in_array( "property_garage", $hide_property_fields ) ) { ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label
                                for="property_garage"><?php echo esc_html__( 'Garages', 'g5-ere' ) . ere_required_field( 'property_garage' ); ?></label>
                        <input type="number" id="property_garage" class="form-control" name="property_garage"
                               value="">
                    </div>
                </div>
			<?php } ?>

			<?php if ( ! in_array( "property_garage_size", $hide_property_fields ) ) { ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label
                                for="property_garage_size"><?php echo wp_kses_post( sprintf( __( 'Garages Size (%s)', 'g5-ere' ), $measurement_units ) ); ?></label>
                        <input type="number" id="property_garage_size" class="form-control"
                               name="property_garage_size"
                               value="">
                    </div>
                </div>
			<?php } ?>
			<?php if ( ! in_array( "property_year", $hide_property_fields ) ) { ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label
                                for="property_year"><?php echo esc_html__( 'Year Built', 'g5-ere' ) . ere_required_field( 'property_year' ); ?></label>
                        <input type="number" id="property_year" class="form-control" name="property_year" value="">
                    </div>
                </div>
			<?php } ?>
			<?php if ( ! in_array( "property_identity", $hide_property_fields ) ) { ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="property_identity"><?php esc_html_e( 'Property ID', 'g5-ere' ); ?></label>
                        <input type="text" class="form-control" name="property_identity" id="property_identity">
                    </div>
                </div>
			<?php } ?>
			<?php
			$additional_fields = ere_render_additional_fields();
			if ( count( $additional_fields ) > 0 ) {
				$required_fields = ere_get_option('required_fields', array('property_title', 'property_type', 'property_price', 'property_map_address'));
				foreach ( $additional_fields as $key => $field ) {
					$field_attributes = array();
					if (is_array($required_fields) && in_array($field['id'], $required_fields)) {
						$field_attributes[] = 'data-toggle="ere-property-additional-field-required"';
					}
					switch ( $field['type'] ) {
						case 'text':
							?>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['title'] . ere_required_field($field['id']) ); ?></label>
                                    <input <?php echo implode(' ', $field_attributes)?> type="text" id="<?php echo esc_attr( $field['id'] ); ?>"
                                           class="form-control"
                                           name="<?php echo esc_attr( $field['id'] ); ?>"
                                           value="">
                                </div>
                            </div>
							<?php
							break;
						case 'textarea':
							?>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['title']  . ere_required_field($field['id']) ); ?></label>
                                    <textarea <?php echo implode(' ', $field_attributes)?>
                                            name="<?php echo esc_attr( $field['id'] ); ?>"
                                            rows="3"
                                            id="<?php echo esc_attr( $field['id'] ); ?>"
                                            class="form-control"></textarea>
                                </div>
                            </div>
							<?php
							break;
						case 'select':
							?>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['title']  . ere_required_field($field['id']) ); ?></label>
                                    <select <?php echo implode(' ', $field_attributes)?> name="<?php echo esc_attr( $field['id'] ); ?>"
                                            id="<?php echo esc_attr( $field['id'] ); ?>"
                                            class="form-control">
										<?php
										foreach ( $field['options'] as $opt_value ):?>
                                            <option value="<?php echo esc_attr( $opt_value ); ?>"><?php echo esc_html( $opt_value ); ?></option>
										<?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
							<?php
							break;
						case 'checkbox_list':
							$field_attributes[] = sprintf('data-name="%s"', $field['id'].'[]');
							?>
                            <div class="col-12">
                                <div class="form-group">
                                    <label><?php echo esc_html( $field['title']  . ere_required_field($field['id']) ); ?> </label>
	                                <div <?php echo implode(' ', $field_attributes)?>  class="ere-field-<?php echo esc_attr($field['id']); ?>">
		                                <?php
		                                foreach ( $field['options'] as $i => $opt_value ):?>
			                                <div class="custom-control custom-checkbox custom-control-inline">
				                                <input class="custom-control-input" type="checkbox" name="<?php echo esc_attr( $field['id'] ); ?>[]"
				                                       id="<?php echo esc_attr( $field['id'] . '_' . $i ); ?>"
				                                       value="<?php echo esc_attr( $opt_value ); ?>"/>
				                                <label class="custom-control-label"
				                                       for="<?php echo esc_attr( $field['id'] . '_' . $i ); ?>">
					                                <?php echo esc_html( $opt_value ); ?>
				                                </label>

			                                </div>
		                                <?php endforeach; ?>
	                                </div>
                                </div>
                            </div>
							<?php
							break;
						case 'radio':
							$field_attributes[] = sprintf('data-name="%s"', $field['id']);
							?>
                            <div class="col-12">
                                <div class="form-group">
                                    <label><?php echo esc_html( $field['title'] . ere_required_field($field['id']) ); ?></label>
	                                <div <?php echo implode(' ', $field_attributes)?> class="ere-field-<?php echo esc_attr($field['id']); ?>">
		                                <?php
		                                foreach ( $field['options'] as $i => $opt_value ):?>
			                                <div class="custom-control custom-radio custom-control-inline">
				                                <input class="custom-control-input" type="radio"
				                                       id="<?php echo esc_attr( $field['id'] . '_' . $i ); ?>"
				                                       name="<?php echo esc_attr( $field['id'] ); ?>"
				                                       value="<?php echo esc_attr( $opt_value ); ?>">
				                                <label class="custom-control-label"
				                                       for="<?php echo esc_attr( $field['id'] . '_' . $i ); ?>"><?php echo esc_html( $opt_value ); ?></label>
			                                </div>
		                                <?php endforeach; ?>
	                                </div>
                                </div>
                            </div>
							<?php
							break;
					}
				}
			}
			?>
        </div>
		<?php if ( ! in_array( "additional_details", $hide_property_fields ) ) { ?>
            <div class="add-tab-row mt-3">
                <h4><?php esc_html_e( 'Additional details', 'g5-ere' ); ?></h4>
                <table class="additional-block mb-3">
                    <thead>
                        <tr>
                            <td class="ere-column-action"></td>
                            <td><label><?php esc_html_e( 'Title', 'g5-ere' ); ?></label></td>
                            <td><label><?php esc_html_e( 'Value', 'g5-ere' ); ?></label></td>
                            <td class="ere-column-action"></td>
                        </tr>
                    </thead>
                    <tbody id="ere_additional_details">
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td colspan="3">
                                <button type="button" data-increment="-1" class="add-additional-feature"><i
                                            class="fa fa-plus"></i> <?php esc_html_e( 'Add New', 'g5-ere' ); ?>
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>

            </div>
		<?php } ?>
    </div>
</div>
