<?php

/**
 * Handle a page request
 */
class PRT_Page_Controller {

	public static function get_page_args() {
		return array(
  		'slug'     => array(
        'validate_callback' => array( 'PRT_Validate', 'string' ),
      ),
		);
	}

	protected $request = null;

	function __construct( $request ) {

		$this->request = $request;
	}

	public function get_page() {
		$parms = $this->request->get_params();
		$slug  = strip_tags( $parms['slug'] );

		if ( '' !== $slug ) {
			$args = array(
			  'name'        => $slug,
			  'post_type'   => 'page',
			  'post_status' => 'publish',
			  'numberposts' => 1,
			);
			$pages = get_posts( $args );
			$the_page = $pages[0];

			if( $the_page ) {
				return rest_ensure_response( array(
					'success' => true,
					'page' => $this->build_page( $the_page ),
				) );
			} else {
				return rest_ensure_response( array(
					'success' => false,
					'page' => null,
				) );
			}
		}
	}

	protected function build_page( $page ) {
		$sections = array();

		if( have_rows('page_sections', $page->ID) ) {
			while( have_rows('page_sections', $page->ID) ) {
				the_row();

				// Get the row layout.
				$layout = get_row_layout();
				// Do something...
				switch( $layout ) {

					case "full_width_content" :
						// Add this section
						$sections[] = array(
							'section_name' => $layout,
							'wysiwyg'        => get_sub_field( 'wysiwyg' ),
							'bg_start'       => get_sub_field( 'bg_start' ),
							'bg_middle'      => get_sub_field( 'bg_middle' ),
							'bg_end'         => get_sub_field( 'bg_end' ),
							'padding_top'    => get_sub_field( 'padding_top' ),
							'padding_bottom' => get_sub_field( 'padding_bottom' ),
						);
						break;

					case "video" :
						// Add this section
						$sections[] = array(
							'section_name' => $layout,
							'title'          => get_sub_field( 'title' ),
							'description'    => get_sub_field( 'description' ),
							'video_id'       => get_sub_field( 'video_id' ),
							'video_snapshot' => get_sub_field( 'video_snapshot' ),
						);
						break;

					case "tiled_cta" :
						$tiles = get_sub_field( 'tiles' );
						foreach ( (array) $tiles as $key => $tile ) {
							$tiles[$key]['content_formatted'] = nl2br( $tile['content'] );
							$tiles[$key]['icon_url'] = wp_get_attachment_url( $tile['icon'] );
						}
						// Add this section
						$sections[] = array(
							'section_name' => $layout,
							'tiles' => $tiles,
						);
						break;

					case "list_cta" :
						// Add this section
						$sections[] = array(
							'section_name' => $layout,
							'title' => get_sub_field( 'title' ),
							'items' => get_sub_field( 'items' ),
						);
						break;

					case "contact_form" :
						// Add this section
						$sections[] = array(
							'section_name' => $layout,
							'title' => get_sub_field( 'title' ),
							'to_email' => get_sub_field( 'to_email' ),
						);
						break;
				}
			}
		}

		if ( isset( $sections['tiled_cta'] )
			&& isset( $sections['tiled_cta']['tiles'] )
			&& ! empty( $sections['tiled_cta']['tiles'] ) ) {
			foreach ( $sections['tiled_cta']['tiles'] as $key => $tile ) {
				$sections['tiled_cta']['tiles'][$key]['icon_url'] = wp_get_attachment_url( $tile['icon'] );
			}
		}

		return array(
			'id' => $page->ID,
			'title' => $page->post_title,
			'sections' => $sections,
		);
	}
}

?>
