<?php
/**
 * @package  InpsydeJobPlugin
 */
namespace Inc\classes\util;

use Inc\classes\common\Constants;
use \Inc\classes\util\InpsydeExternalLinkException;
use \Inc\classes\util\JsonException;

class InpsydeCache
{

    function __construct()
    {

    }

    public static function getCachedContent($url, $key, $returnAsArray)
    {
        $cachedContent = get_transient($key);
        if (empty($cachedContent)) {
            $response = wp_remote_get($url);
            if (is_wp_error($response)) {
                throw new InpsydeExternalLinkException($response->getErrorMessage());
                return false;
            }
            $body = wp_remote_retrieve_body($response);
            if ($body == "{}") {
                $code = wp_remote_retrieve_response_code($response);
                $message = wp_remote_retrieve_response_message($response);
                throw new InpsydeExternalLinkException("Error Code :: " . $code .
                    ", Error Message :: " . $message);
            }
            $body = $body == "{}" ? "" : $body;
            // Save the API response so we don't have to call again until tomorrow.
            set_transient($key, $body, DAY_IN_SECONDS);
            $cachedContent = $body;
        }
        if ($returnAsArray) {
            $cachedContent = json_decode($cachedContent, true);
        } else {
            $cachedContent = json_decode($cachedContent);
        }
        $errorCode = json_last_error();
        if (0 < $errorCode) {
            throw new JsonException(sprintf('Error parsing JSON - %s', JsonException::
                getJSONErrorMessage($errorCode)));
        }
        return $cachedContent;
    }
}
