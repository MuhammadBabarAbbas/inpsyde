<?php
/**
 * @package  InpsydeJobPlugin
 */
namespace Inc\classes\util;

//loading classes to be used in this class
use Inc\classes\common\Constants;
use Inc\classes\exception\InpsydeExternalLinkException;
use Inc\classes\exception\JsonException;

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
                foreach($response->get_error_codes() as $code){
                    foreach($response->get_error_messages($code) as $message){
                        throw new InpsydeExternalLinkException($message);    
                    }                       
                }
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
            set_transient($key, $body, 3600);
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
