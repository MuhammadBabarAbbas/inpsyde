<?php
/**
 * @package  InpsydeJobPlugin
 */

defined('WP_UNINSTALL_PLUGIN') or die('Illegal access');

$posts = get_posts(array('post_type' => 'inpsyde', 'numberposts' => -1));

foreach ($posts as $post) {
    wp_delete_post($post->ID, true);
}