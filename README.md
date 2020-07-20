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

Code is commented to help, while going through it. All the classes that are used in this plugin are in the **inc\classes** directory. This directory has been further divided into **util**, **common** and **exception** classes. Javascripts and stylesheets go into **assets** folder. **templates** folder contains the inpsyde template file. Ajax endpoint for serverside calls is in ""inc\endpoints** folder.

Following are the details of implementation for individual files.

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

# Main Class (InpsydeJobPlugin.php)

```

/**
 * @package  InpsydeJobPlugin
 * 
 * This class is the main plugin class, which holds the core functions of the plugin
 * 
 */
namespace Inc\classes;


use Inc\classes\Activate;
use Inc\classes\Deactivate;

class InpsydeJobPlugin
{

    private $plugin;
    
    /**
     * Constructor of the class
     */
    function __construct()
    {
        //getting plugin directory name for usage in the class
        $this->plugin = substr(plugin_basename(__file__), 0, strpos(plugin_basename(__file__),
            "/"));
    }

    function pluginName()
    {
        return "Inpsyde Job Plugin";
    }

    /**
     * Function called on plugin activation
     */
    function activate()
    {
        //calling the activate function, this function is called from plugin main file.
        $activate = new Activate();
        $activate->activate();
    }

    /**
     * Function called on plugin deactivation
     */
    function deactivate()
    {
        //Calling deactivation function in a static manner   
        Deactivate::deactivate();
    }

    /**
     * Adding inpsyde specific query variable
     * 
     * @param array query variables
     * 
     * @return  array updated query variables
     */
    function add_query_var($vars)
    {
        //adding specific variable for inspyde plugin, this will be used to load template
        $vars[] = 'inpsyde_id';
        return $vars;
    }

    /**
     * Adding innpsyde template
     * 
     * @param string template path
     * 
     * @return string existing or inpsyde template path
     */
    function add_template($template)
    {
        //template is only loaded when it finds inpsyde_id in query variables 
        if (get_query_var('inpsyde_id', false) !== false) {
            
            //locating the inpsyde template file, if found returned in the case of inpsyde plugin being active
            $newTemplate = WP_PLUGIN_DIR . "/" . $this->plugin . '\templates\template-inpsyde.php';
            if (file_exists($newTemplate))
                return $newTemplate;
        }
        //Fall back to original template
        return $template;
    }

    /**
     * Adding javascripts to be used on front end
     */
    function add_scripts()
    {
        
        wp_enqueue_script('jQuery', plugins_url('/' . $this->plugin .
            '/assets/jquery-3.5.1.js'));
            
        // scripts for datatable
        wp_enqueue_script('datatables', plugins_url('/' . $this->plugin .
            '/assets/jquery.dataTables.min.js'));
        wp_enqueue_script('datatables_bootstrap', plugins_url('/' . $this->plugin .
            '/assets/dataTables.bootstrap4.min.js'));
        wp_enqueue_script('bootstrap', plugins_url('/' . $this->plugin .
            '/assets/bootstrap.min.js'));

        //custom javascript file
        wp_enqueue_script('inpsydescript', plugins_url('/' . $this->plugin .
            '/assets/script.js'));
        
        //defining ajax controller link to be used in ajax calls
        wp_localize_script('inpsydescript', 'ajax_url', plugins_url() .
            '/inpsyde/inc/endpoints/ajax/AjaxController.php');

    }

    /**
     * Adding styles to be used on front end
     */
    function add_styles()
    {
        //for datatable
        wp_enqueue_style('bootstrap_style', plugins_url('/' . $this->plugin .
            '/assets/bootstrap.css'));
        wp_enqueue_style('datatables_style', plugins_url('/' . $this->plugin .
            '/assets/dataTables.bootstrap4.min.css'));
            
        //custom stylesheet file
        wp_enqueue_style('inpsydestyle', plugins_url('/' . $this->plugin .
            '/assets/style.css'));
    }

}


```

# Caching and fetching class (Inpsyde Cache)

```

/**
 * @package  InpsydeJobPlugin
 */
namespace Inc\classes\util;

//loading classes to be used in this class
use Inc\classes\common\Constants;
use \Inc\classes\util\InpsydeExternalLinkException;
use \Inc\classes\util\JsonException;

/**
 * This class contains method to cache the content acquired from inpsyde provided endpoints
 *
 */
class InpsydeCache
{

    /**
     * This method is used to fetch data from provided end point
     * 
     * @param string $url link to fetch data from
     * @param string $key against which the data is cached
     * @param boolean $returnAsArray whether to return the data as array or json
     * 
     * @return array or json cached data is returned
     */
    public static function getCachedContent($url, $key, $returnAsArray)
    {
        //Fetching if the data is cached against the key
        $cachedContent = get_transient($key);

        //Checking if the data is cached against the key
        if (empty($cachedContent)) {

            //Loading data if not cached

            $response = wp_remote_get($url);
            if (is_wp_error($response)) {
                //throwing exception in case of error
                throw new InpsydeExternalLinkException($response->getErrorMessage());
                return false;
            }
            $body = wp_remote_retrieve_body($response);
            if ($body == "{}") {
                //throwing exception in case of empty response
                $code = wp_remote_retrieve_response_code($response);
                $message = wp_remote_retrieve_response_message($response);
                throw new InpsydeExternalLinkException("Error Code :: " . $code .
                    ", Error Message :: " . $message);
            }

            //checking empty json
            $body = $body == "{}" ? "" : $body;
            // Caching the data for one hour.
            set_transient($key, $body, HOUR_IN_SECONDS);
            $cachedContent = $body;
        }

        //deciding whether to return the data as array
        if ($returnAsArray) {
            $cachedContent = json_decode($cachedContent, true);
        } else {
            $cachedContent = json_decode($cachedContent);
        }
        //checking any json error
        $errorCode = json_last_error();
        if (0 < $errorCode) {
            //throwing json exception
            throw new JsonException(sprintf('Error parsing JSON - %s', JsonException::
                getJSONErrorMessage($errorCode)));
        }
        return $cachedContent;
    }
}


```