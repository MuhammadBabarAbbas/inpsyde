<?php
/**
 * @package  InpsydeJobPlugin
 */
namespace Inc\classes;

class Activate
{
    function __construct()
    {
        add_action('init', array($this, 'add_endpoint'));
    }

    public function activate()
    {
        //set_transient('vpt_flush', 1, 60);
        $this->add_endpoint();
        flush_rewrite_rules();
    }

    public function add_endpoint()
    {
        add_rewrite_tag('%inpsyde%', '([^&]+)');
        add_rewrite_rule('inpsyde/?([^/]*)',
            'index.php?inpsydepage=inpsyde&inpsyde_id=$matches[1]', 'top');
    }

    function add_endpoint_()
    {
        add_rewrite_endpoint('inpsyde', EP_PERMALINK);

        if (get_transient('vpt_flush')) {
            delete_transient('vpt_flush');
            flush_rewrite_rules();
        }
    }
}
