<?php
/**
 * @package  InpsydeJobPlugin
 */

namespace Inc\classes;

/**
 * This class contains all the methods that are used on plugin activation
 */
class Activate
{
    /**
     * Constructor of the class
     * 
     */
    public function __construct()
    {
        //attaching method to be called on wordpress initialization
        add_action('init', array($this, 'add_endpoint'));
    }

    /**
     * Method called on activation, it is used to add endpoint on activation
     * 
     */
    public function activate()
    {
        //explicitly calling the method once the activate method is called 
        $this->add_endpoint();
        
        //updating rewrite rules
        flush_rewrite_rules();
    }

    /**
     * This method adds inpsyde endpoint
     * 
     */
    public function add_endpoint()
    {
        //Adding inpsyde rewrite rule
        add_rewrite_rule('inpsyde/?([^/]*)',
            'index.php?inpsydepage=inpsyde&inpsyde_id=$matches[1]', 'top');
    }
}
