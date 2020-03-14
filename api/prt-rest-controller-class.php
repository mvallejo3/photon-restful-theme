<?php

define( 'PRT_API_ROOT', dirname(__FILE__) . '/' );

/**
* The API for boxapp
*/
class PRT_REST_Controller {

  public static $instance = null;

  // Here initialize our namespace and resource name.
  public function __construct() {
    $this->namespace     = '/prt/v1';
  }

  public static function get_instance() {
    self::$instance === null && self::$instance = new self();
    return self::$instance;
  }

  // Register our routes.
  public static function register_routes() {

    $page_routes = new PRT_Page_Routes( $this->namespace );
    $page_routes->register_routes();

    $contact_form_routes = new PRT_Contact_Form_Routes( $this->namespace );
    $contact_form_routes->register_routes();
  }
}
