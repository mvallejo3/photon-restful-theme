<?php

/**
 * Handle page routes
 */
class PRT_Contact_Form_Routes {

	public static $resource = '/contact/';

  private $namespace;

  public function __construct( $namespace ) {
    $this->namespace = $namespace;
  }

  public function register_routes() {

    register_rest_route( $this->namespace, self::$resource, array(
	  	array(
	  		'methods'  => 'POST',
	  		'callback' => array( $this, 'submit_form' ),
	  		'args'     => PRT_Contact_Form_Controller::submit_form_args(),
	  	),
	  ) );
  }

  public function submit_form( $request ) {
    $controller = new PRT_Contact_Form_Controller( $request );
    return $controller->submit_form();
  }
}

?>