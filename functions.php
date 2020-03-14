<?php
/**
 * @package photon-restful-theme
 */

define( 'PRT_PATH', dirname(__FILE__) . '/' );

// autoload
require PRT_PATH . 'autoloader.php';

if ( class_exists( 'PRT_ACF' ) ) {
  new PRT_ACF();
}


add_action(
  'rest_api_init',
  array(
    PRT_REST_Controller::get_instance(),
    'register_hooks'
  )
);
