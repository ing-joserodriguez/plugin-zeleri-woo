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
		}

		public function init(){
			// Init is required by wordpress
		}

		public function crear_orden_zeleri($payload, $customer_token) {

			$url = $this->api_production_base_url.'/checkout/orders';
			$response = $this->do_remote_post($url, $customer_token, $payload );
			if ( is_wp_error( $response ) ) {
				
				$result = $response;

			} else {

				if ($response[self::RESPONSE_KEY][self::CODE_KEY] == 200) {
			   		
			   	$result = json_decode($response['body']);
					return $result;

				} else if ($response[self::RESPONSE_KEY][self::CODE_KEY] == 400) {
					
					$json_response = json_decode($response['body']);
					$result = new WP_Error("zeleri-woo-oficial","$json_response->statusDescription");

			    } else {
						$result = $response;
					//$result = new WP_Error("zeleri-woo-oficial","Invalid Request");

				}
			}
			return $result;
		}

		private function do_remote_post($url, $token, $payload){

			/*$prueba = array(
				'url'=> $url,
				'request'=> [
					'method' 			=> 'POST',
					'timeout' 		=> 90,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => [
						'Content-Type' 					 => 'application/json',
						'Authorization: Bearer ' => $token
					],
					'body' 				=> json_encode($payload),
					'cookies' 		=> array(),
					'sslverify' 	=> FALSE
				]
			);

			return $prueba;*/

			return wp_remote_post( $url, array(
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
