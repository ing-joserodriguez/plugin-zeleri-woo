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

			$this->api_production_base_url = 'https://zeleri.dev.ionix.cl/integration-kit/v1';
      
      $zeleri_payment_gateway_apikey = get_option( 'zeleri_payment_gateway_apikey' );
      $zeleri_payment_gateway_key = get_option( 'zeleri_payment_gateway_key' );

			$this->_token_customer = isset( $zeleri_payment_gateway_apikey ) ? $zeleri_payment_gateway_apikey : '';
		}

		public function init(){
			// Init is required by wordpress
		}

		public function crear_orden_zeleri($payload) {

			$url = $this->api_production_base_url."/checkout/orders";
			$response = $this->do_remote_post($url, $this->_token_customer, $payload );
			/*if ( is_wp_error( $response ) ) {
				
				$result = $response;
				//$result = 'Error en respuesta de la API';

			} else {

				if ($response[self::RESPONSE_KEY][self::CODE_KEY] == 200) {
			   		
			   	$result = json_decode($response['body']);
					return $result;

				} else if ($response[self::RESPONSE_KEY][self::CODE_KEY] == 400) {
					
					$json_response = json_decode($response['body']);
					$result = new WP_Error("zeleri-woo-oficial","$json_response->statusDescription");

			    } else {

					$result = new WP_Error("zeleri-woo-oficial","Invalid Request");

				}
			}*/
			return $response;
		}

		private function do_remote_post($url, $token, $payload){
			/*return wp_remote_post( 'https://jsonplaceholder.typicode.com/users/1', array(
				'method' 			=> 'GET',
				'headers'     => array(
					'Content-Type' 					 => 'application/json'
				),
				'body' 				=> json_encode($payload)
			)
		);*/
			return wp_remote_post( 'https://zeleri.dev.ionix.cl/integration-kit/v1/checkout/orders', array(
					'method' 			=> 'POST',
					'timeout' 		=> 90,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(
						'Content-Type' 					 => 'application/json',
						'Authorization: Bearer ' => $token
					),
					'body' 				=> json_encode($payload),
					'cookies' 		=> array(),
					'sslverify' 	=> FALSE
			  )
			);
		}
		
	}
}
