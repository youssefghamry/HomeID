<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 07/02/2017
 * Time: 2:37 CH
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$agency_term   = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
do_action( 'g5ere_loop_agency', $agency_term );
get_header( 'ere' );
/**
 * ere_before_main_content hook.
 *
 * @hooked ere_output_content_wrapper_start - 10 (outputs opening divs for the content)
 */
do_action( 'ere_before_main_content' );
do_action( 'ere_taxonomy_agency_before_main_content' );


G5ERE()->get_template('agency/content-single.php');


do_action( 'ere_taxonomy_agency_after_main_content' );
/**
 * ere_after_main_content hook.
 *
 * @hooked ere_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'ere_after_main_content' );
get_footer( 'ere' );