<?php
/**
 * @package  InpsydeJobPlugin
 */
/*
Plugin Name: Inpsyde Job Plugin
Plugin URI: http://inpsyde-job-plugin.com
description: A plugin developed in partial fulfillment of the requirements for the PHP Developer position in Inpsyde.
Version: 1.0
Author: Babar
Author URI: http://inpsyde-job-plugin.com 
License: GPLv2+
*/

(defined('ABSPATH') && function_exists('add_action')) or die('Illegal access');

if (file_exists(dirname(__file__) . '/vendor/autoload.php')) {
    require_once dirname(__file__) . '/vendor/autoload.php';
}

use Inc\classes\Activate;
use Inc\classes\Deactivate;
use Inc\classes\InpsydeJobPlugin;

    $inpsydeJobPlugin = new InpsydeJobPlugin();

    //activation
    register_activation_hook(__file__, array($inpsydeJobPlugin, 'activate'));

    add_action('wp_enqueue_scripts', array($inpsydeJobPlugin,
            'add_datatables_scripts'));
    add_action('wp_enqueue_scripts', array($inpsydeJobPlugin, 'add_datatables_style'));

    add_action('query_vars', array($inpsydeJobPlugin, 'add_query_var'));

    add_action('template_include', array($inpsydeJobPlugin, 'add_template'));

    register_deactivation_hook(__file__, array('Deactivate', 'deactivate'));