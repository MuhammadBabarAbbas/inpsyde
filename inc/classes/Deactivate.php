<?php
/**
 * @package  InpsydeJobPlugin
 */
namespace Inc\classes;

/**
 * This class contains all the methods that are used on plugin deactivation
 */
class Deactivate
{
    
    /**
     * Method called on plugin deactivation, any action required goes here
     * 
     */
    public static function deactivate()
    {
        //updating rewrite rules
        flush_rewrite_rules();
    }
}
