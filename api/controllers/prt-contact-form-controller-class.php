<?php

/**
 * Handle a contact form request
 */
class PRT_Contact_Form_Controller {

	private $data = array();

	public static function submit_form_args() {
		return array(
			'first_name' => array(
				'validate_callback' => array( 'PRT_Validate', 'string' ),
			),
			'title' => array(
				'validate_callback' => array( 'PRT_Validate', 'string' ),
			),
			'company' => array(
				'validate_callback' => array( 'PRT_Validate', 'string' ),
			),
			'phone' => array(
				'validate_callback' => array( 'PRT_Validate', 'string' ),
			),
			'email' => array(
				'validate_callback' => array( 'PRT_Validate', 'string' ),
			),
			'lead_source' => array(
				'validate_callback' => array( 'PRT_Validate', 'string' ),
			),
		);
	}

	protected $request = null;

	function __construct( $request ) {

		$this->request = $request;
	}

	public function submit_form() {
		$parms = $this->request->get_params();

		// prep and validate data
		$this->prep_data( $params );

		//
		$this->send_data_to_salesforce();

		if ( $this->send_email() ) {
			return rest_ensure_response( array(
				'success' => true,
			) );
		} else {
			return rest_ensure_response( array(
				'success' => false,
			) );
		}
	}

	protected function send_email() {
		// Headers
		$headers = "MIME-Version: 1.0\r\n";
		$headers.= "Content-type: text/html; charset=UTF-8\r\n";
		$headers.= "From: <" . $this->data['email'] . ">";

		$subject = 'Web Enquiry | Lilypads';
		$to = "mario@vallgroup.com";

		// data
		$body = '';
		$body .= 'Hi,<br><br>You have just received a new web enquiry via Lilypads.';
		$body .= '<br><br>';
		foreach ($this->data as $key => $value) {
			$body .= "<strong>$key:</strong> $value.<br />";
		}
		$body .= '<br><br>';

		if ( mail( $to, $subject, $body, $headers ) ) {
			return true;
		} else {
			return false;
		}
	}

	private function prep_data( $params ) {
		$data = array();
		// save first_name if exists
		$data['first_name'] = isset( $params['first_name'] )
			&& ! empty( $params['first_name'] )
				? strip_tags( $params['first_name'] )
				: '';
		// save title if exists
		$data['title'] = isset( $params['title'] )
			&& ! empty( $params['title'] )
				? strip_tags( $params['title'] )
				: '';
		// save company if exists
		$data['company'] = isset( $params['company'] )
			&& ! empty( $params['company'] )
				? strip_tags( $params['company'] )
				: '';
		// save phone if exists
		$data['phone'] = isset( $params['phone'] )
			&& ! empty( $params['phone'] )
				? strip_tags( $params['phone'] )
				: '';
		// save email if exists
		$data['email'] = isset( $params['email'] )
			&& ! empty( $params['email'] )
				? strip_tags( $params['email'] )
				: '';
		// save lead_source if exists
		$data['lead_source'] = isset( $params['lead_source'] )
			&& ! empty( $params['lead_source'] )
				? strip_tags( $params['lead_source'] )
				: '';
		// save the data
		$this->data = $data;
	}

	private function send_data_to_salesforce() {
		// add SF specific params
		$this->data["oid"] = "00D1U000000DWQ2";
		$this->data["retURL"] = "https://lilypads.com/#thank-you";
		// our SF URL
		$sf_url = 'https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
		// init curl
		$ch = curl_init();
		// prep request
		curl_setopt($ch, CURLOPT_URL, $sf_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $this->data ) );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// make request
		$server_output = curl_exec($ch);
		// close curl
		curl_close ($ch);
		// handle response
		if ($server_output == "OK") {
			return true;
		} else {
			return false;
		}
	}
}

?>