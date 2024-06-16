<?php

namespace Zeleri\WooCommerce\Zeleripay\Blocks;
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

final class WCGatewayZeleriWebpayBlocks extends AbstractPaymentMethodType {

    use WCGatewayZeleriBlocks;

    protected $name = 'zeleri_woo_oficial_payment_gateways';

    public function __construct() {
        $this->scriptInfo = array('dependencies' => array('wc-blocks-registry', 'wc-settings', 'wp-element', 'wp-html-entities'), 'version' => '04778d2c588251b3173574f6332df1993');
        $this->paymentId = $this->name;
        $this->productName = 'zeleri';
    }
}
