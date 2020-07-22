<?php
/**
 * @package  InpsydeJobPlugin
 */

namespace Inc\Tests;

use PHPUnit\Framework\TestCase;
use Inc\classes\util\InpsydeCache;
use Inc\classes\common\Constants;
use Mockery;
use Brain\Monkey;
use Brain\Monkey\Functions;
use Inc\classes\exception\InpsydeExternalLinkException;
use Inc\classes\exception\JsonException;

class InpsydeCacheTest extends TestCase
{

    public function testgetCachedContent()
    {
        
        // We expect plugins_url to be called
        Functions\expect('get_transient')->once()->andReturn(get_transient(Constants::INPSYDE_USERS_TRANSIENT_KEY));
        $content = file_get_contents(Constants::INPSYDE_USERS_ENDPOINT);
        Functions\expect('wp_remote_get')->once()->andReturn($content);
        Functions\expect('is_wp_error')->once()->andReturn(false);
        Functions\expect('wp_remote_retrieve_body')->once()->andReturn($content);
        Functions\expect('set_transient')->once()->andReturn(set_transient(Constants::INPSYDE_USERS_TRANSIENT_KEY, $content, 3600));
        //Constants\expect('WP_DEBUG')->once()->andReturn(WP_DEBUG);
        //Constants\expect('HOURS_IN_SECONDS')->once()->andReturn(HOURS_IN_SECONDS);
        try {
            $returnedData = InpsydeCache::getCachedContent(Constants::
                INPSYDE_USERS_ENDPOINT, Constants::INPSYDE_USERS_TRANSIENT_KEY, true);
            $this->assertIsArray($returnedData, "Array is Returned");
        }
        catch (InpsydeExternalLinkException $ex) {
            echo $ex->errorMessage();
        }
        catch (JsonException $ex) {
            echo $ex->errorMessage();
        }
        catch (exception $ex) {
            echo $ex->errorMessage();
        }

    }
}
?>