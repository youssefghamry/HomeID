<?php
/**
 * The template for displaying post-like.php
 *
 */
if (in_array('g5plus-post-like/g5plus-post-like.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    echo '<li class="meta-like">';
    echo do_shortcode('[g5plus-post-like]');
    echo '</li>';
}

