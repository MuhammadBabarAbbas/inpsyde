<?php
/**
 * @package  InpsydeJobPlugin
 */
namespace Inc\classes\util\exception;

use Inc\classes\common\Constants;

use exception;

class InpsydeExternalLinkException extends Exception
{
    public function errorMessage()
    {
        //error message
        $errorMsg = 'An External Link Exception occurred';
     
       if (WP_DEBUG) {
            $errorMsg = 'An External Link Exception occurred on line ' . $this->
                getLine() . ' in ' . $this->getFile() . ': <b>' . $this->getMessage() .
                '</b>';
        }
        return $errorMsg;
    }
}