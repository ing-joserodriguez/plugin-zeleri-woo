<?php
  class Template {
    const TEMPLATE_PATH = WP_PLUGIN_DIR.'/zeleri/public/';
    public function render(string $name, array $parameters): void {
        wc_get_template( $name, $parameters, null, self::TEMPLATE_PATH );
    }
}