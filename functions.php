<?php
/**
 * @package photon-restful-theme
 */

define( 'PRT_PATH', dirname(__FILE__) . '/' );

// autoload
require PRT_PATH . 'autoloader.php';

$api = new PRT_REST_Controller();
$api->register_routes();

new PRT_ACF();