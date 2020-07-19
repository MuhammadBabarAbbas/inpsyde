## Introduction

 Inpsyde Job Plugin is a plugin developed in partial fulfillment of the requirements for the PHP Developer position in Inpsyde GmBH

## Minimum Requirements and Dependencies

* PHP 7.3.12+
* WordPress latest

## Installation

Inpsyde Job Plugin can be downoaded from [https://github.com/MuhammadBabarAbbas/inpsyde](https://github.com/MuhammadBabarAbbas/inpsyde), and can be installed like any regular plugin, by unzipping it in the **wp-content/plugins** directory. It can also be installed via composer using the following commands. You can run these commands at the wordpress root directory. The inpsyde plugin will be created inside **wp-content/plugins** folder.

```
$ composer config repositories.wp-plugins vcs https://github.com/MuhammadBabarAbbas/inpsyde
$ composer config minimum-stability dev
$ composer require muhammadbabarabbas/inpsyde
```

## Getting Started

Once installed, **Inpsyde Job Plugin** appears in the Wordpress Admin Plugins Section. It can be activated by clicking on the Activate link below the plugin name. Once activated it creates an endpoint with the url **http://WORDPRESS_SITE_URL/inpsyde**. If the user moves to this link, he finds a datatable showing a user list populated by fetching the data from [https://jsonplaceholder.typicode.com/users](https://jsonplaceholder.typicode.com/users).

Each row in the table shows a clickable ID, NAME and USERNAME of the user. On clicking any of these links, respective user's detail is populated in a modal panel. This detail is fetched from [https://jsonplaceholder.typicode.com/users/$ID](https://jsonplaceholder.typicode.com/users/$ID), i.e. based on user id.

## Implementation Details

Following is the directory tree for inpsyde plugin.

```
inpsyde
¦   .gitattributes
¦   .gitignore
¦   composer.json
¦   index.php
¦   inpsyde.php
¦   LICENSE
¦   README.md
¦   uninstall.php
¦
+---assets
¦       bootstrap.css
¦       bootstrap.min.js
¦       dataTables.bootstrap4.min.css
¦       dataTables.bootstrap4.min.js
¦       jquery-3.5.1.js
¦       jquery.dataTables.min.js
¦       README.md
¦       script.js
¦       style.css
¦
+---inc
¦   +---classes
¦   ¦   ¦   Activate.php
¦   ¦   ¦   Deactivate.php
¦   ¦   ¦   InpsydeJobPlugin.php
¦   ¦   ¦
¦   ¦   +---common
¦   ¦   ¦       Constants.php
¦   ¦   ¦
¦   ¦   +---exception
¦   ¦   ¦       InpsydeExternalLinkException.php
¦   ¦   ¦       JsonException.php
¦   ¦   ¦
¦   ¦   +---util
¦   ¦           InpsydeCache.php
¦   ¦
¦   +---endpoints
¦       +---ajax
¦               AjaxController.php
¦
+---templates
¦       template-inpsyde.php
¦
+---vendor
    ¦   autoload.php
    ¦
    +---composer
            autoload_classmap.php
            autoload_namespaces.php
            autoload_psr4.php
            autoload_real.php
            autoload_static.php
            ClassLoader.php
            installed.json
            LICENSE
```

Code is commented to help, while going through it. Following are the details of implementation for individual files.

# Main File (inpsyde.php)

```

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

```
