<?php
/**
 * @package  InpsydeJobPlugin
 */
namespace Inc\classes;


use Inc\classes\Activate;
use Inc\classes\Deactivate;

class InpsydeJobPlugin
{

    private $plugin;

    function __construct()
    {
        $this->plugin = substr(plugin_basename(__file__), 0, strpos(plugin_basename(__file__),
            "/"));
    }

    function pluginName()
    {
        return "Inpsyde Job Plugin";
    }

    function activate()
    {
        $activate = new Activate();
        $activate->activate();
    }

    function deactivate()
    {
        Deactivate::deactivate();
    }

    function add_query_var($vars)
    {
        $vars[] = 'inpsyde_id';
        return $vars;
    }

    function add_template($template)
    {
        if (get_query_var('inpsyde_id', false) !== false) {
            //Check theme directory first
            $newTemplate = locate_template(array('template-inpsyde.php'));
            if ('' != $newTemplate)
                return $newTemplate;
                
            //Check plugin directory next
            $newTemplate = WP_PLUGIN_DIR . "/" . $this->plugin . '\templates\template-inpsyde.php';
            if (file_exists($newTemplate))
                return $newTemplate;
        }

        //Fall back to original template
        return $template;
    }

    function add_datatables_scripts()
    {
        wp_enqueue_script('jQuery', plugins_url('/' . $this->plugin .
            '/assets/jquery-3.5.1.js'));
        wp_enqueue_script('datatables', plugins_url('/' . $this->plugin .
            '/assets/jquery.dataTables.min.js'));
        wp_enqueue_script('datatables_bootstrap', plugins_url('/' . $this->plugin .
            '/assets/dataTables.bootstrap4.min.js'));
        wp_enqueue_script('bootstrap', plugins_url('/' . $this->plugin .
            '/assets/bootstrap.min.js'));

        wp_enqueue_script('inpsydescript', plugins_url('/' . $this->plugin .
            '/assets/script.js'));
        wp_localize_script('inpsydescript', 'ajax_url', plugins_url() .
            '/inpsyde/inc/endpoints/ajax/AjaxController.php');

    }

    function add_datatables_style()
    {
        wp_enqueue_style('bootstrap_style', plugins_url('/' . $this->plugin .
            '/assets/bootstrap.css'));
        wp_enqueue_style('datatables_style', plugins_url('/' . $this->plugin .
            '/assets/dataTables.bootstrap4.min.css'));
        wp_enqueue_style('inpsydestyle', plugins_url('/' . $this->plugin .
            '/assets/style.css'));
    }

}
