<?php
/**
 * @package  InpsydeJobPlugin
 */
namespace Inc\endpoints\ajax;

//loading classes to be used on this page
use Inc\classes\util\InpsydeCache;
use Inc\classes\common\Constants;
use Inc\classes\exception\InpsydeExternalLinkException;
use Inc\classes\exception\JsonException;
use exception;

//loading wordpress context
$webDir = explode('wp-content', dirname(__file__));
require ($webDir[0] . '/wp-load.php');

/**
 * This method shows user detail in modal panel in a formatted way
 * 
 */
function showData($data)
{
    try {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                echo '<tr><td colspan="2"><b>' . ucfirst($key) . '</b></td></tr>';
                showData($value);
            } else {
                echo '<tr><td>' . ucfirst($key) . '</td><td>' . $value . '</td></tr>';
            }
        }
    }
    catch (exception $ex) {
        throw $ex;
    }
}
try {
    if (isset($_GET['action']) && isset($_GET['id']) && $_GET['id'] != null && $_GET['action'] ==
        'getUserDetails') {
        $data = InpsydeCache::getCachedContent(Constants::INPSYDE_USERS_ENDPOINT . "/" .
            $_GET['id'], Constants::INPSYDE_USERS_TRANSIENT_KEY . $_GET['id'], true);
        echo '<table id="userDetails" class="table table-striped table-hover">';
        showData($data);
        echo '</table>';
    }
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


//class AjaxController {
//
//    public function __construct( $action ) {
//
//        add_action( "wp_ajax_nopriv_$action", array ( $this, 'logged_out' ) );
//        add_action( "wp_ajax_$action",        array ( $this, 'logged_in' ) );
//    }
//
//    public function logged_out() {
//
//        echo 'logged_out';
//    }
//
//    public function logged_in() {
//
//        echo 'logged_in';
//    }
//}
//function getUserDetails(&$id){
//  echo 'a';
//}
//call_user_func("'" . $_GET['action'] . "'", $_GET['id']);


?>