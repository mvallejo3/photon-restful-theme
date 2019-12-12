<?php
/**
 * Global helpers class
 */
class PRT_Helpers {

	public static function true_bool( $value ) {
    if ( is_bool( $value ) ) {
    	return $value;
    }

    if ( is_string( $value ) ) {
    	$bool_value = 'false' === strtolower( trim( $value ) ) ? false : (bool) $value;
    	return $bool_value;
    }

    if ( is_numeric( $value ) ) {
    	return (bool) $value;
    }

    return (bool) $value;
  }
}


?>