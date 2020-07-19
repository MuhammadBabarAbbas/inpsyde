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

Code is commented to help, while going through it. Following are the details of implementation for individual files.

# Main File (inpsyde.php)

```
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
```
