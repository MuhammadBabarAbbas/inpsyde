<?php
/**
 * @package  InpsydeJobPlugin
 */
namespace Inc\classes\exception;

use Inc\classes\common\Constants;

use exception;

/**
 * This is a custom Exception class, that handles external links related errors
 * 
 * @extends Exception
 */
class InpsydeExternalLinkException extends Exception
{
    /**
     * Adding customized message to generated exception
     * 
     * @return string Custom error message
     */
    public function errorMessage()
    {
        //error message
        $errorMsg = 'An External Link Exception occurred';

        if (WP_DEBUG) {
            $errorMsg = 'An External Link Exception occurred on line ' . $this->getLine() .
                ' in ' . $this->getFile() . ': <b>' . $this->getMessage() . '</b>';
        }
        return $errorMsg;
    }
}
