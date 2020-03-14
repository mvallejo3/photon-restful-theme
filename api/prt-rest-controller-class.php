<?php

define( 'PRT_API_ROOT', dirname(__FILE__) . '/' );

/**
* The API for boxapp
*/
class PRT_REST_Controller {

  private $namespace = '/prt/v1';

  public static $instance = null;

  public static function get_instance() {
    self::$instance === null && self::$instance = new self();
    return self::$instance;
  }

  // Here initialize our namespace and resource name.
  public function __construct() {}

  public static function register_hooks() {
    PRT_ACF_REST_Controller::register_page_modules();
    $this->register_routes();
  }

  // Register our routes.
  public function register_routes() {
    // regiseter contact form endpoint
    $contact_form_routes = new PRT_Contact_Form_Routes( $this->namespace );
    $contact_form_routes->register_routes();
  }
}
