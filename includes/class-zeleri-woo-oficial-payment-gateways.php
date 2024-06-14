<?php

require_once( ABSPATH . 'wp-content/plugins/zeleri/includes/class-zeleri-woo-oficial-api.php' );
require_once( ABSPATH . 'wp-content/plugins/zeleri/includes/class-zeleri-woo-oficial-signature.php' );

class Zeleri_Woo_Oficial_Payment_Gateways extends WC_Payment_Gateway {

    const ID = 'zeleri_woo_oficial_payment_gateways';
    const PAYMENT_GW_DESCRIPTION = 'Permite el pago de productos y/o servicios, con tarjetas de crédito, débito y prepago a través de Zeleri';

    public function __construct() {
        $this->id = self::ID;
        $this->icon = plugin_dir_url(dirname(dirname(__FILE__))) . 'zeleri/admin/images/logo-zeleri.webp';
        $this->method_title = __('Zeleri', 'zeleri');
        $this->title = 'Zeleri';
        $this->method_description  = $this->get_option('zeleri_payment_gateway_description', self::PAYMENT_GW_DESCRIPTION);
        $this->description  = $this->get_option('zeleri_payment_gateway_description', self::PAYMENT_GW_DESCRIPTION);

         /**
         * Carga configuración y variables de inicio.
         **/
        $this->init_form_fields();
        $this->init_settings();

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);

        if (!$this->is_valid_for_use()) {
            $this->enabled = false;
        }
    }

    public function init_form_fields() {
        
        $zeleriKeyDescription = 'Indica tu código de comercio para el ambiente de producción. <br/><br/>' .
            'Este se te entregará al completar el proceso de afiliación comercial. <br /><br />' .
            'Siempre comienza con 5970 y debe tener 12 dígitos. Si el tuyo tiene 8, antepone 5970.';

        $apiKeyDescription = 'Esta llave privada te la entregará Transbank luego de que completes el proceso ' .
            'de validación (link más abajo).<br/><br/>No la compartas con nadie una vez que la tengas. ';

        $this->form_fields = array(
            'enabled' => array(
                'title'     => 'Activar/Desactivar plugin:',
                'type'      => 'checkbox',
                'label'     =>  __('Activar/Desactivar:', 'zeleri'),
                'desc_tip'  => __('Title displayed during checkout.', 'zeleri'),
                'default'   => 'yes',
            ),
            'zeleri_payment_gateway_apikey' => array(
                'title'     => __('API Key (llave secreta) Produccion:', 'zeleri'),
                'type'      => 'text',
                'desc_tip'  => __($apiKeyDescription, 'zeleri'),
                'default'   => '',
            ),
            'zeleri_payment_gateway_key' => array(
                'title'     => __('Zeleri Key:', 'zeleri'),
                'type'      => 'text',
                'desc_tip'  => __($zeleriKeyDescription, 'zeleri'),
                'default'   => '',
            ),
            'zeleri_payment_gateway_order_status' => array(
                'title'     => __('Estado de la orden', 'zeleri'),
                'type'      => 'select',
                'desc_tip'  => __('Define el estado de la orden luego del pago exitoso.', 'zeleri'),
                'options'   => [
                    ''           => 'Default',
                    'processing' => 'Processing',
                    'completed'  => 'Completed',
                ],
                'default'   => '',
            ),
            'zeleri_payment_gateway_description' => array(
                'title'     => __('Descripcion medio de pago:', 'zeleri'),
                'type'      => 'textarea',
                'desc_tip'  => __('Description displayed during checkout.', 'zeleri'),
                'default'   => '',
            ),
        );
    }
    

    public function process_payment($order_id) {
        try {
            global $woocommerce;
            $order = new WC_Order($order_id);
    
            $apiZeleri = new Zeleri_Woo_Oficial_API();
            $signatureZeleri = new Zeleri_Woo_Oficial_Signature();
    
            $payload = array(
                "amount" => (int) number_format($order->get_total(), 0, ',', ''),
                "gateway_id" => 1, // Replace with appropriate value
                "title" => "prueba checkout order",
                "description" => "pago por checkout",
                "currency_id" => get_woocommerce_currency(), // Use WooCommerce currency
                "customer" => [
                    "email" => $order->get_billing_email(), // Use order billing email
                    "name" => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(), // Use order billing name
                ],
                "success_url" => "http://localhost:8080/success",
                "failure_url" => "http://localhost:8080/failure",
            );
    
            $secret = $this->get_option('zeleri_payment_gateway_apikey');
            $customer_token = $this->get_option('zeleri_payment_gateway_key');
            $signedPayload = $signatureZeleri->getSignedObject($payload, $secret);
    
            // Add logging for debugging
            wc_add_notice("Zeleri Signed Payload: " . json_encode($signedPayload), 'notice');
    
            $createResponse = $apiZeleri->crear_orden_zeleri($signedPayload, $customer_token);
            var_dump($createResponse);
            if( is_wp_error($createResponse) ) {
                throw new Exception($createResponse->get_error_code().' - '.$createResponse->get_error_message());
            }

            wc_add_notice("Create Response Zeleri: " . json_encode($createResponse), 'notice');

            return [
                'result' => 'success',
                //'redirect' => $createResponse['redirect_url'], // Assuming successful response has a redirect URL
            ];
    
        } catch (Exception  $ex) {
            wc_add_notice('Response Error: '.$ex, 'error');
            throw new Exception('Payment processing failed.', 0, $ex); // Re-throw exception with more context
        }
    }

    /**
     * Opciones panel de administración.
     **/
    public function admin_options()
    {
        include_once __DIR__ . '/../admin/partials/zeleri-admin-display.php';
    }

    /**
     * Comprueba configuración de moneda (Peso Chileno).
     **/
    public static function is_valid_for_use()
    {
        return in_array(get_woocommerce_currency(), ['CLP']);
    }

}