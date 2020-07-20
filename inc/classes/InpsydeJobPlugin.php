<?php
/**
 * @package  InpsydeJobPlugin
 */
namespace Inc\classes;

//loading classes to be used in this class
use Inc\classes\Activate;
use Inc\classes\Deactivate;

/**
 * This class is the main plugin class, which holds the core functions of the plugin
 * 
 */
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
