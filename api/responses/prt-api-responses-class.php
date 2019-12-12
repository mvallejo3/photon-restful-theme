<?php

class PRT_API_Responses {

  static public function Success_Response( $data = null ) {
    $response = array(
      'success' => true
    );

    if ( $data && ! empty($data) ) {
      $response = array_merge( $response, $data );
    }

    return rest_ensure_response( $response );
  }

  static public function Failure_Response( $data = null ) {
    $response = array(
      'success' => false
    );

    if ( $data && ! empty($data) ) {
      $response = array_merge( $response, $data );
    }

    return rest_ensure_response( $response );
  }

  static public function Error_Response( $error ) {
    if ($error instanceof WP_Error) {
      return rest_ensure_response( $error );

    } else {
      if ( is_string( $error ) ) {
        error_log($error);
      }
      $code = is_array($error) ? $error['code'] : $error->getCode();
      $code = $code && $code !== 0 ? $code : 500;

      $message = ( is_array( $error ) )
        ? $error['message']
        : $error->getMessage();
      $message = $message && $message !== '' ? $message : 'API Error';

      return new WP_Error($code, $message);
    }
  }
}

?>
