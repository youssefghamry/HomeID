<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php
	if (function_exists('wp_body_open')) {
		wp_body_open();
	}
	do_action('homeid_before_page_wrapper');
	$site_wrapper_classes = apply_filters('homeid_site_wrapper_classes', array('site-wrapper'));
	?>
	<!-- Open Wrapper -->
	<div id="site-wrapper" class="<?php echo esc_attr(join(' ', $site_wrapper_classes))?>">
		<?php
		/**
		 * @hooked homeid_template_header - 10
		 */
		do_action('homeid_before_page_wrapper_content');
		?>
        <?php $wrapper_content_classes = apply_filters('homeid_wrapper_content_classes',  array('wrapper-content', 'clearfix')); ?>

		<div id="wrapper_content" class="<?php echo esc_attr(join(' ', $wrapper_content_classes))?>">
			<?php
			/**
			 * @hooked  homeid_template_wrapper_start - 10
			 */
			do_action('homeid_main_wrapper_content_start');
			?>