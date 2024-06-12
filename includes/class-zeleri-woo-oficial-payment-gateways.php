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

        $this->zeleriAPI = new Zeleri_Woo_Oficial_API();
    }

    public function init_form_fields() {
        
        $zeleriKeyDescription = 'Puedes solicitar la Zeleri Key en soporte@zeleri.com.';
        $apiKeyDescription = 'Puedes solicitar la API Key en soporte@zeleri.com.';

        $this->form_fields = array(
            'enabled' => array(
                'title'     => 'Activar/Desactivar plugin:',
                'type'      => 'checkbox',
                'label'     =>  __('Activar/Desactivar:', 'zeleri'),
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
                'desc_tip'  => __('Selecciona el estado que tendrá la orden por defecto al finalizar una compra.', 'zeleri'),
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
                'desc_tip'  => __('Describe el medio de pago que verá el usuario en la pantalla de pago.', 'zeleri'),
                'default'   => '',
            ),
            'btn_save_changes' => array(
                'title'   => '',
                'type'    => 'submit',
                'default' => __( 'Guardar Cambios', 'zeleri' ),
                'class'   => 'button-primary woocommerce-save-button'
            )
        );
    }

    public function process_admin_options() {
        parent::process_admin_options();

        var_dump($_POST);
    
        if ( isset( $_POST['btn_save_changes'] ) ) {

            $options = array(
                'enabled'                             => sanitize_text_field( $_POST['enabled'] ),
                'zeleri_payment_gateway_apikey'       => sanitize_text_field( $_POST['enabled'] ),
                'zeleri_payment_gateway_key'          => sanitize_text_field( $_POST['enabled'] ),
                'zeleri_payment_gateway_order_status' => sanitize_text_field( $_POST['enabled'] ),
                'zeleri_payment_gateway_description'  => sanitize_text_field( $_POST['enabled'] )
            );

            foreach ($options as $option => $value) {
                update_option( $option, $value ); // Guardar el nombre en la opción personalizada
            }
        }
    }
    

    public function process_payment($order_id) {
        global $woocommerce;
        $order = new WC_Order( $order_id );
        // Lógica para procesar el pago
        // Puedes redirigir al usuario a una página de confirmación o procesar el pago directamente
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