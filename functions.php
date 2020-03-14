<?php
/**
 * @package photon-restful-theme
 */

define( 'PRT_PATH', dirname(__FILE__) . '/' );

// autoload
require PRT_PATH . 'autoloader.php';

add_action( 'rest_api_init', array( PRT_REST_Controller::get_instance(), 'register_routes' ) );

new PRT_ACF();
