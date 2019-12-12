<?php

class PRT_Media_Uploader {

  private $world_id;

  public function __construct( $world_id ) {
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $this->world_id = $world_id;
  }

  public function try_cube_image_upload() {
    try {
      if ( $this->valid_cube_exists_in_FILES() ) {
        $ids = [];

        foreach (PRT_Cube_Image::$sides as $side) {
          $ids[$side] = media_handle_upload( $side, $world_id );
        }

        return new PRT_Cube_Image( $ids );
      }

    } catch (Exception $e) {
      return false;
    }
  }

  public function try_upload_by_FILES_key( $files_key ) {
    if ( isset( $_FILES[$files_key] ) ) {
      /**
       * media_handle_upload calls global var $_FILES directly
       * so there is no need for us to call it, other than to check
       * it exists. On success media_handle_upload returns an attachment ID
       *
       * @var int
       */
      return media_handle_upload( $files_key, $this->world_id );
    }

    return false;
  }

  private function valid_cube_exists_in_FILES() {
    return ! PRT_Array_Helpers::array_any( PRT_Cube_Image::$sides, function($side) {
      return ! isset($_FILES[$side]) || $_FILES[$side] === '';
    });
  }
}

?>
