<?php
class Zeleri_Woo_Oficial_Payment_Gateways extends WC_Payment_Gateway {
    public function __construct() {
        $this->id = 'zeleri_woo_oficial_payment_gateways';
        $this->method_title = 'Zeleri';
        $this->title = 'Zeleri';
        $this->has_fields = false;

        $options = get_option( 'zeleri_settings' );

        $this->enabled = ( isset($options['zeleri_checkbox_field_0']) ) ? $options['zeleri_checkbox_field_0'] : 'no';
        $this->method_description  = ( isset($options['zeleri_textarea_field_4']) ) ? $options['zeleri_textarea_field_4'] : '';

         // Initialize settings
         $this->init_form_fields();
         $this->init_settings();
    }

    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'   => __('Enable/Disable', 'textdomain'),
                'type'    => 'checkbox',
                'label'   => __('Enable Custom Payment Gateway', 'textdomain'),
                'default' => 'yes',
            ),
            'title' => array(
                'title'       => __('Title', 'textdomain'),
                'type'        => 'text',
                'description' => __('Title displayed during checkout.', 'textdomain'),
                'default'     => __('Custom Payment', 'textdomain'),
                'desc_tip'    => true,
            ),
            'description' => array(
                'title'       => __('Description', 'textdomain'),
                'type'        => 'textarea',
                'description' => __('Description displayed during checkout.', 'textdomain'),
                'default'     => __('Pay with our custom payment gateway.', 'textdomain'),
                'desc_tip'    => true,
            ),
        );
    }

    public function admin_options() {
        ?>
        <h3><?php _e('Configuración de Tu Pasarela de Pago', 'tu-textdomain'); ?></h3>
        <p><?php _e('Personaliza los campos de tu pasarela de pago.', 'tu-textdomain'); ?></p>
        <form action='options.php' method='post'>
            <?php
                settings_fields('pluginZeleriPage');
                do_settings_sections('pluginZeleriPage');
                submit_button();
            ?>
        </form>
        <?php
    }
  
    public function process_payment($order_id) {
        global $woocommerce;
        $order = new WC_Order( $order_id );
        // Lógica para procesar el pago
        // Puedes redirigir al usuario a una página de confirmación o procesar el pago directamente
    }
}