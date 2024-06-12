<?php

require_once( ABSPATH . 'wp-content/plugins/zeleri/includes/class-zeleri-woo-oficial-api.php' );

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
        global $woocommerce;
        $order = new WC_Order( $order_id );

        $apiZeleri = new Zeleri_Woo_Oficial_API();

        $payload = array(
            "amount" => 1000,
            "gateway_id"=> 1,
            "title"       => "prueba checkout order",
            "description" => "pago por checkout",
            "currency_id" => 1,
            "customer"    => [
                "email" => "correo@correo.com",
                "name"  => "customer prueba"
            ],
            "success_url" => "http://localhost:8080/success",
            "failure_url" => "http://localhost:8080/failure",
        );

        $secret = $this->get_option('zeleri_payment_gateway_apikey');
        $signedPayload = getSignedObject($object, $payload);

        var_dump($signedPayload);

        //$createZeleriOrder = $apiZeleri->crear_orden_zeleri($signedPayload);
        
        
        // Lógica para procesar el pago
        // Puedes redirigir al usuario a una página de confirmación o procesar el pago directamente
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