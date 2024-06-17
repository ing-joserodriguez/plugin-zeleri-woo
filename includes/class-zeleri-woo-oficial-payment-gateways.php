<?php

require_once( ABSPATH . 'wp-content/plugins/zeleri/includes/class-zeleri-woo-oficial-api.php' );
require_once( ABSPATH . 'wp-content/plugins/zeleri/includes/class-zeleri-woo-oficial-signature.php' );

class Zeleri_Woo_Oficial_Payment_Gateways extends WC_Payment_Gateway {

    const ID = 'zeleri_woo_oficial_payment_gateways';
    const PAYMENT_GW_DESCRIPTION = 'Permite el pago de productos y/o servicios, con tarjetas de crédito, débito y prepago a través de Zeleri';
    const WOOCOMMERCE_API_SLUG = 'zeleri_woo_oficial_payment_gateways';

    public function __construct() {
        $this->id = self::ID;
        $this->icon = plugin_dir_url(dirname(dirname(__FILE__))) . 'zeleri/admin/images/logo-zeleri-80x22.webp';
        $this->method_title = __('Zeleri', 'zeleri_woo_oficial_payment_gateways');
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
                'title'     => __('Activar/Desactivar plugin:', 'zeleri_woo_oficial_payment_gateways'),
                'type'      => 'checkbox',
                'label'     =>  __('Activar/Desactivar:', 'zeleri_woo_oficial_payment_gateways'),
                'desc_tip'  => __('Title displayed during checkout.', 'zeleri_woo_oficial_payment_gateways'),
                'default'   => 'yes',
            ),
            'zeleri_payment_gateway_secret' => array(
                'title'     => __('API Key (llave secreta) Produccion:', 'zeleri_woo_oficial_payment_gateways'),
                'type'      => 'text',
                'desc_tip'  => __($apiKeyDescription, 'zeleri_woo_oficial_payment_gateways'),
                'default'   => '',
            ),
            'zeleri_payment_gateway_token' => array(
                'title'     => __('Zeleri Key:', 'zeleri_woo_oficial_payment_gateways'),
                'type'      => 'text',
                'desc_tip'  => __($zeleriKeyDescription, 'zeleri_woo_oficial_payment_gateways'),
                'default'   => '',
            ),
            'zeleri_payment_gateway_order_status' => array(
                'title'     => __('Estado de la orden', 'zeleri'),
                'type'      => 'select',
                'desc_tip'  => __('Define el estado de la orden luego del pago exitoso.', 'zeleri_woo_oficial_payment_gateways'),
                'options'   => [
                    ''           => 'Default',
                    'processing' => 'Processing',
                    'completed'  => 'Completed',
                ],
                'default'   => '',
            ),
            'zeleri_payment_gateway_description' => array(
                'title'     => __('Descripcion medio de pago:', 'zeleri_woo_oficial_payment_gateways'),
                'type'      => 'textarea',
                'desc_tip'  => __('Description displayed during checkout.', 'zeleri_woo_oficial_payment_gateways'),
                'default'   => '',
            ),
        );
    }

    public function process_payment($order_id) {
        try {
            $secret = $this->get_option('zeleri_payment_gateway_secret');
            $token = $this->get_option('zeleri_payment_gateway_token');
            $order = wc_get_order( $order_id );
            $apiZeleri = new Zeleri_Woo_Oficial_API();
            $signatureZeleri = new Zeleri_Woo_Oficial_Signature($secret);
            $argsSuccess = array(
                'wc-api' => static::WOOCOMMERCE_API_SLUG,
                'status' => 'success'
            );
            $argsFailure = array(
                'wc-api' => static::WOOCOMMERCE_API_SLUG,
                'status' => 'failure'
            );
            $payload = array(
                "amount" => (int) number_format($order->get_total(), 0, ',', ''),
                "gateway_id" => 1,
                "title" => "prueba checkout order",
                "description" => "pago por checkout",
                "currency_id" => 1,
                "customer" => [
                    "email" => $order->get_billing_email(), // Use order billing email
                    "name" => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(), // Use order billing name
                ],
                "success_url" => esc_url('http://localhost:8080/success'),
                "failure_url" => esc_url('http://localhost:8080/failure')
            );
            
            $signature = $signatureZeleri->generate($payload);
            $payload["signature"] = $signature;

    
            $createResponse = $apiZeleri->crear_orden_zeleri($payload, $token);
            if( is_wp_error($createResponse) ) {
                throw new Exception($createResponse->get_error_code().' - '.$createResponse->get_error_message());
            }

            return [
                'result' => 'success',
                'redirect' => $createResponse->data->url, // Assuming successful response has a redirect URL
            ];
    
        } catch (Exception  $ex) {
            wc_add_notice('Response Error: '.$ex, 'error');
            throw new Exception('Payment processing failed.', 0, $ex); // Re-throw exception with more context
        }
    }
    
    /**
     * Opciones panel de administración.
     **/
    public function admin_options() {
        include_once __DIR__ . '/../admin/partials/zeleri-admin-display.php';
    }

    /**
     * Comprueba configuración de moneda (Peso Chileno).
     **/
    public static function is_valid_for_use() {
        return in_array(get_woocommerce_currency(), ['CLP']);
    }

}