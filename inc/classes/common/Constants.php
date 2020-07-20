<?php
/**
 * @package  InpsydeJobPlugin
 */
namespace Inc\classes\common;

/**
 * This class contains all the constants needed over the plugin
 * 
 */
class Constants
{

    //inpsyde provided link
    const INPSYDE_USERS_ENDPOINT = 'https://jsonplaceholder.typicode.com/users';

    //key to cache user list and details
    const INPSYDE_USERS_TRANSIENT_KEY = 'inpsyde-users';
}
