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

				if ($response[self::RESPONSE_KEY][self::CODE_KEY] == 201) {
			   		
			   	$result = json_decode($response['body']);
					return $result;

				} else if ($response[self::RESPONSE_KEY][self::CODE_KEY] == 400) {

					$json_response = json_decode($response['body']);
					$result = new WP_Error('zeleri-woo-oficial', $json_response->code.'-'. $json_response->message);

			  } else {

						$result = new WP_Error("zeleri-woo-oficial","Invalid Request");
				}
			}
			return $result;
		}

		private function do_remote_post($url, $token, $signedPayload){
			
			// Convertir el signedPayload a JSON
			$signedPayload = json_encode($signedPayload, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
			// Reemplazar las comillas dobles diferentes por comillas dobles estándar
			$signedPayload = str_replace('"', '"', $signedPayload);
			$signedPayload = str_replace('"', '"', $signedPayload);

			return wp_remote_post( $url, array(
					'method' 			=> 'POST',
					'timeout' 		=> 90,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(
						'Content-Type' => 'application/json',
						'Authorization' => 'Bearer '.$token
					),
					'body' 				=> $signedPayload,
					'cookies' 		=> array(),
					'sslverify' 	=> FALSE
			  )
			);
		}

		private function do_remote_get($url, $token){
			return wp_remote_get( $url, array(
					'method' 			=> 'GET',
					'timeout' 		=> 90,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(
						'Content-Type' 					 => 'application/json',
						'Authorization: Bearer ' => $token
					),
					'body' 				=> '',
					'cookies' 		=> array(),
					'sslverify' 	=> FALSE
			  )
			);
		}
		
	}
}
