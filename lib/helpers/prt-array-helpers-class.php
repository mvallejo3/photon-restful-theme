<?php

class PRT_Array_Helpers {

  public static function array_any(array $array, callable $fn) {
      foreach ($array as $value) {
          if($fn($value)) {
              return true;
          }
      }
      return false;
  }

  public static function array_every(array $array, callable $fn) {
      foreach ($array as $valu ) {
          if(!$fn($value)) {
              return false;
          }
      }
      return true;
  }

  public static function array_clean( $del_val, array &$array ) {
    if (($key = array_search($del_val, $array)) !== false) {
        unset($array[$key]);
    }
  }
}

?>
