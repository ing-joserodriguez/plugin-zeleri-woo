<?php 

if ( ! class_exists( 'Zeleri_Woo_Oficial_API' ) ) {
	class Zeleri_Woo_Oficial_API {

		const RESPONSE_KEY = 'response';
		const CODE_KEY = 'code';
		/**
		 * Constructor for your shipping class 
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->id = 'zeleri_woo_oficial_payment_gateways';
			$this->init();

			$this->api_production_base_url = 'https://zeleri-v2.dev.ionix.cl/';
			$this->zeleri_payment_gateway_apikey = isset( get_option( 'zeleri_payment_gateway_apikey' ) )? get_option( 'zeleri_payment_gateway_apikey' ) : '';
			$this->zeleri_payment_gateway_key = isset( get_option( 'zeleri_payment_gateway_key' ) )? get_option( 'zeleri_payment_gateway_key' ) : '';
		}

		public function init(){
			// Init is required by wordpress
		}

		private function do_remote_put($url, $api_key, $payload){
			return wp_remote_post( $url, array(
				'method' => 'PUT',
				'timeout' => 90,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(
					'Content-Type' => 'application/json',
	    			'Ocp-Apim-Subscription-Key' => $api_key
				),
				'body' => json_encode($payload),
				'cookies' => array(),
				'sslverify' => FALSE
			    )
			);
		}

		private function do_remote_post($url, $api_key, $payload){
			return wp_remote_post( $url, array(
				'method' => 'POST',
				'timeout' => 90,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(
					'Content-Type' => 'application/json',
	    			'Ocp-Apim-Subscription-Key' => $api_key
				),
				'body' => json_encode($payload),
				'cookies' => array(),
				'sslverify' => FALSE
			    )
			);
		}

		private function do_remote_get($url, $api_key)
		{
			return wp_remote_post( $url, array(
				'method' => 'GET',
				'timeout' => 90,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(
					'Content-Type' => 'application/json',
	    			'Ocp-Apim-Subscription-Key' => $api_key
				),
				'body' => '',
				'cookies' => array(),
				'sslverify' => FALSE
			    )
			);
		}
		
	}
}
