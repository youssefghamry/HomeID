<?php
/**
 * @var $isRTL
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$the_post = get_post( $invoice_id );

if ( $the_post->post_type != 'invoice' ) {
	esc_html_e( 'Posts ineligible to print!', 'g5-ere' );

	return;
}
wp_enqueue_script( 'jquery' );
wp_add_inline_script( 'jquery', 'jQuery(window).load(function(){ print(); });' );
wp_enqueue_style( ERE_PLUGIN_PREFIX . 'single-invoice' );

if ( $isRTL == 'true' ) {
	wp_enqueue_style( ERE_PLUGIN_PREFIX . 'invoice-print-rtl' );
}


// Actions
remove_action( 'wp_head', '_wp_render_title_tag', 1 );
remove_action( 'wp_head', 'wp_resource_hints', 2 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
remove_action( 'publish_future_post', 'check_and_publish_future_post', 10 );
remove_action( 'wp_head', 'noindex', 1 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
remove_action( 'wp_head', 'wp_custom_css_cb', 101 );
remove_action( 'wp_head', 'wp_site_icon', 99 );


add_action( 'wp_enqueue_scripts', 'ere_dequeue_assets_print_invoice', 9999 );
function ere_dequeue_assets_print_invoice() {
	foreach ( wp_styles()->registered as $k => $v ) {
		if ( ! in_array( $k, array(
			'bootstrap',
			ERE_PLUGIN_PREFIX . 'single-invoice',
			ERE_PLUGIN_PREFIX . 'invoice-print-rtl'
		) ) ) {
			unset( wp_styles()->registered[ $k ] );
		}
	}
}


$print_logo = ere_get_option( 'print_logo', '' );
$attach_id  = '';
if ( is_array( $print_logo ) && count( $print_logo ) > 0 ) {
	$attach_id = $print_logo['id'];
}
$image_size = ere_get_option( 'print_logo_size', '200x100' );
$image_src  = '';
$width      = '';
$height     = '';
if ( $attach_id ) {
	if ( preg_match( '/\d+x\d+/', $image_size ) ) {
		$image_sizes = explode( 'x', $image_size );
		$image_src   = ere_image_resize_id( $attach_id, $image_sizes[0], $image_sizes[1], true );
	} else {
		if ( ! in_array( $image_size, array( 'full', 'thumbnail' ) ) ) {
			$image_size = 'full';
		}
		$image_src = wp_get_attachment_image_src( $attach_id, $image_size );
		if ( $image_src && ! empty( $image_src[0] ) ) {
			$image_src = $image_src[0];
		}
	}
}
if ( ! empty( $image_src ) ) {
	list( $width, $height ) = getimagesize( $image_src );
}
$page_name = get_bloginfo( 'name', '' );

$ere_invoice = new ERE_Invoice();
$invoice_meta = $ere_invoice->get_invoice_meta( $invoice_id );
$invoice_date = $invoice_meta['invoice_purchase_date'];
$user_id = $invoice_meta['invoice_user_id'];
$agent_id = get_the_author_meta( ERE_METABOX_PREFIX . 'author_agent_id', $user_id );
$agent_status = get_post_status( $agent_id );
if ( $agent_status == 'publish' ) {
	$agent_email = get_post_meta( $agent_id, ERE_METABOX_PREFIX . 'agent_email', true );
	$agent_name  = get_the_title( $agent_id );
} else {
	$user_firstname = get_the_author_meta( 'first_name', $user_id );
	$user_lastname  = get_the_author_meta( 'last_name', $user_id );
	$user_email     = get_the_author_meta( 'user_email', $user_id );
	if ( empty( $user_firstname ) && empty( $user_lastname ) ) {
		$agent_name = get_the_author_meta( 'user_login', $user_id );
	} else {
		$agent_name = $user_firstname . ' ' . $user_lastname;
	}
	$agent_email = $user_email;
}

$company_address = ere_get_option( 'company_address', '' );
$company_name    = ere_get_option( 'company_name', '' );
$company_phone   = ere_get_option( 'company_phone', '' );
$item_name       = get_the_title( $invoice_meta['invoice_item_id'] );
$payment_type    = ERE_Invoice::get_invoice_payment_type( $invoice_meta['invoice_payment_type'] );
$payment_method  = ERE_Invoice::get_invoice_payment_method( $invoice_meta['invoice_payment_method'] );
$total_price     = ere_get_format_money( $invoice_meta['invoice_item_price'] );

?>
<html <?php language_attributes(); ?>>
<head>
	<?php wp_head(); ?>
</head>
<body>
    <div class="single-invoice-wrap g5ere__invoice-details">
        <div class="card card-body g5ere__invoice-details-body">
            <div class="g5ere__page-info mb-2 text-center">
				<?php if ( ! empty( $image_src ) ): ?>
                    <img src="<?php echo esc_url( $image_src ) ?>" alt="<?php echo esc_attr( $page_name ) ?>"
                         width="<?php echo esc_attr( $width ) ?>" height="<?php echo esc_attr( $height ) ?>">
				<?php endif; ?>
                <div class="invoice-info mb-4">
                    <h3 class="invoice-title">
						<?php printf( __( 'Invoice  from %s', 'g5-ere' ), get_bloginfo( 'name', 'display' ) ); ?>
                    </h3>
                    <p class="invoice-id">
						<?php printf( __( 'Invoice #%s', 'g5-ere' ), $invoice_id ); ?>
                    </p>
                </div>
            </div>
            <!-- Begin Agent Info -->
            <div class="g5ere__single-invoice-info row mb-4">
                <div class="agent-company-info col-md-6 mb-4 mb-md-0">
                    <p class="invoice-info-label"><?php esc_html_e( 'Invoice from', 'g5-ere' ) ?></p>
					<?php if ( ! empty( $company_name ) ): ?>
                        <div class="invoice-name">
							<?php echo esc_html( $company_name ); ?>
                        </div>
					<?php endif; ?>
					<?php if ( ! empty( $company_address ) ): ?>
                        <div class="invoice-details company-address">
							<?php echo esc_html( $company_address ); ?>
                        </div>
					<?php endif; ?>
					<?php if ( ! empty( $company_phone ) ): ?>
                        <div class="invoice-details company-phone">
							<?php echo esc_html( $company_phone ); ?>
                        </div>
					<?php endif; ?>


                </div>
                <div class="agent-main-info col-md-6 text-md-right">
                    <p class="invoice-info-label"><?php esc_html_e( 'Invoice to', 'g5-ere' ) ?></p>
					<?php if ( ! empty( $agent_name ) ): ?>
                        <div class="invoice-name">
							<?php echo esc_html( $agent_name ); ?>
                        </div>
					<?php endif; ?>
					<?php if ( ! empty( $agent_email ) ): ?>
                        <div class="invoice-details agent-email">
							<?php echo esc_html( $agent_email ); ?>
                        </div>
					<?php endif; ?>

                </div>
            </div>
            <div class="g5ere__single-invoice-info row mb-5">
                <div class="col-md-6">
                    <p class="invoice-info-label"><?php esc_html_e( 'Invoice id', 'g5-ere' ) ?></p>
                    <p><?php echo esc_html( $invoice_id ) ?></p>

                </div>
                <div class="col-md-6 text-md-right">
                    <p class="invoice-info-label"><?php esc_html_e( 'Date', 'g5-ere' ) ?></p>
                    <p><?php echo date_i18n( get_option( 'date_format' ), strtotime( $invoice_date ) ); ?></p>
                </div>

            </div>
            <!-- End Agent Info -->
            <div class="billing-info">
                <table class="table bg-white">
                    <tbody>
                        <tr>
                            <th><?php esc_html_e( 'Item Name:', 'g5-ere' ); ?></th>
                            <td><?php echo esc_html( $item_name ); ?></td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Payment Type:', 'g5-ere' ); ?></th>
                            <td><?php echo esc_html( $payment_type ); ?></td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Payment Method:', 'g5-ere' ); ?></th>
                            <td><?php echo esc_html( $payment_method ); ?></td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Total Price:', 'g5-ere' ); ?></th>
                            <td><?php echo esc_html( $total_price ); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>