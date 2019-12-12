<?php

/**
 * Handle page routes
 */
class PRT_Page_Routes {

	public static $resource = '/page/';

  private $namespace;

  public function __construct( $namespace ) {
    $this->namespace = $namespace;
  }

  public function register_routes() {

    register_rest_route( $this->namespace, self::$resource . '(?P<slug>[a-zA-Z0-9-_]+)', array(
	  	array(
	  		'methods'  => 'GET',
	  		'callback' => array( $this, 'get_page' ),
	  		'args'     => PRT_Page_Controller::get_page_args(),
	  	),
	  ) );
  }

  public function get_page( $request ) {
    $controller = new PRT_Page_Controller( $request );
    return $controller->get_page();
  }
}

?>