<?php 
/**
 * @package  InpsydeJobPlugin
 */

namespace Inc\Tests;

use PHPUnit\Framework\TestCase;
use Inc\classes\InpsydeJobPlugin;

use Mockery;

class InpsydeJobPluginTest extends TestCase
{
    public function testadd_query_var()
    {
        $vars = array();
        $inpsydeJobPlugin = new InpsydeJobPlugin();
        $this->assertContains("inpsyde_id", $inpsydeJobPlugin->add_query_var($vars));
    }
    
}

namespace Inc\classes;

function plugin_basename(){
    return "";
}
?>