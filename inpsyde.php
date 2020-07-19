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

//Following line ensures that this file is being accessed in wordpress context
(defined('ABSPATH') && function_exists('add_action')) or die('Illegal access');

//composer autoload to use namespaces and referencing files
if (file_exists(dirname(__file__) . '/vendor/autoload.php')) {
    require_once dirname(__file__) . '/vendor/autoload.php';
}

//loading classes to be used on this page
use Inc\classes\Activate;
use Inc\classes\Deactivate;
use Inc\classes\InpsydeJobPlugin;

//Main class for this plugin is being initialized
$inpsydeJobPlugin = new InpsydeJobPlugin();

//activation of plugin, registering activate function from class InpsydeJobPlugin
register_activation_hook(__file__, array($inpsydeJobPlugin, 'activate'));

//deactivation of plugin, registering deactivate function from class InpsydeJobPlugin, calling the static function
register_deactivation_hook(__file__, array('Deactivate', 'deactivate'));

//attaching javascripts for loading on frontend
add_action('wp_enqueue_scripts', array($inpsydeJobPlugin, 'add_scripts'));

//attaching stylesheets for loading by frontend
add_action('wp_enqueue_scripts', array($inpsydeJobPlugin, 'add_styles'));

//adding custom inpsyde query variable inpsyde_id
add_action('query_vars', array($inpsydeJobPlugin, 'add_query_var'));

//adding custom inpsyde page template for plugin
add_action('template_include', array($inpsydeJobPlugin, 'add_template'));
