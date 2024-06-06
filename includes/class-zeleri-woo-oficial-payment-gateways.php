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
         //$this->init_form_fields();
         //$this->init_settings();
    }

    /*public function init_form_fields() {
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
    }*/

    public function admin_options() {
        ?>
        <div class="container menu-principal p-5 my-5">
            <!--<div class="container"> -->
                    <div class="row">
                        <div class="col-md-3">
                            <ul class="nav nav-tabs flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#inicio">Inicio <i class="ph-bold ph-caret-right"></i></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#transacciones">Transacciones <i class="ph-bold ph-caret-right"></i></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#configuracion">Configuración <i class="ph-bold ph-caret-right"></i></a>
                        </li>
                            </ul>
                        </div>
                        <div class="col-md-9">
                            <div class="tab-content">
                                <div id="inicio" class="tab-pane fade show active">
                                    <p>Texto de prueba para la sección "Inicio".</p>
                                </div>

                                <div id="transacciones" class="tab-pane fade">
                                    <p>Texto de prueba para la sección "Transacciones".</p>
                                </div>

                                <div id="configuracion" class="tab-pane fade">
                                    <form action='options.php' method='post'>
                                        <?php
                                            settings_fields( 'pluginZeleriPage' );
                                            do_settings_sections( 'pluginZeleriPage' );
                                            submit_button();
                                        ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!--</div>-->
        </div>
<?php
    }
  
    public function process_payment($order_id) {
        global $woocommerce;
        $order = new WC_Order( $order_id );
        // Lógica para procesar el pago
        // Puedes redirigir al usuario a una página de confirmación o procesar el pago directamente
    }
}