<?php
/**
 * @package  InpsydeJobPlugin
 */
namespace Inc\classes\util;

use Inc\classes\common\Constants;

use exception;

/**
 * This is a custom Exception class, that handles JSON related errors
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
        $errorMsg = 'A JSON Exception occurred';

        if (WP_DEBUG) {
            $errorMsg = 'An JSON occurred on line ' . $this->getLine() . ' in ' . $this->
                getFile() . ': <b>' . $this->getMessage() . '</b>';
        }
        return $errorMsg;
    }

    /**
     * Translates JSON_ERROR_* constant into meaningful message.
     *
     * @param int $errorCode
     *
     * @return string Message string
     */
    public static function getJSONErrorMessage(int $errorCode)
    {
        switch ($errorCode) {
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch';
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON';
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
            default:
                return 'Unknown error';
        }
    }
}
