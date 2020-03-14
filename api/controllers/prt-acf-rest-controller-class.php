<?php

/**
* The API for boxapp
*/
class PRT_ACF_REST_Controller {

  // Here initialize our namespace and resource name.
  public function __construct() {}

  // Register ACF in REST responses
  public static function register_page_modules() {
    register_rest_field(
      array('page'),
      'prt_acf_modules',
      array(
        'get_callback' => function( $post_arr ) {
          return get_field( 'prt_acf_modules', $post_arr['id'] );
        }
      )
    );
  }
}
