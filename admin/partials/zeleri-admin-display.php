<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://https://zeleri.com/
 * @since      1.0.0
 *
 * @package    Zeleri
 * @subpackage Zeleri/admin/partials
 */
?>

<?php 
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	var_dump($_GET);
	if ( isset( $_GET['tab_active'] ) ) {
		$tab = 'settings';
	}
	else{
			$tab = 'home';
	}

	function zeleri_is_nav_active($tab, $val, $sec = '') {
			if ($tab === $val && $sec === '') {
					echo 'active';
			}

			if ($tab === $val && $sec !== '') {
				echo 'show active';
		}
	}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<!--<div class="container menu-principal p-5 my-5">-->
	<!--<div class="container"> -->
			<div class="row">
				<div class="col-md-2">
					<ul class="nav nav-tabs flex-column">
				<li class="nav-item">
					<a class="nav-link <?php zeleri_is_nav_active($tab, 'home'); ?>" data-toggle="tab" href="#inicio">Inicio <i class="ph-bold ph-caret-right"></i></a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?php zeleri_is_nav_active($tab, 'transactions'); ?>" data-toggle="tab" href="#transacciones">Transacciones <i class="ph-bold ph-caret-right"></i></a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?php zeleri_is_nav_active($tab, 'settings'); ?>" data-toggle="tab" href="#configuracion">Configuración <i class="ph-bold ph-caret-right"></i></a>
				</li>
					</ul>
				</div>
				<div class="col-md-7">
					<div class="tab-content">
						<div id="inicio" class="tab-pane fade  <?php zeleri_is_nav_active($tab, 'home', 'content'); ?>">
							<p>Texto de prueba para la sección "Inicio".</p>
						</div>

						<div id="transacciones" class="tab-pane fade <?php zeleri_is_nav_active($tab, 'transactions', 'content'); ?>">
							<p>Texto de prueba para la sección "Transacciones".</p>
						</div>

						<div id="configuracion" class="tab-pane fade <?php zeleri_is_nav_active($tab, 'settings', 'content'); ?>">
							<?php $this->generate_settings_html(); ?>

							<p class="submit zeleri-button-submit">
								<button name="save" class="button-primary woocommerce-save-button" type="submit" value="Guardar los cambios">Guardar los cambios</button>
								<input type="hidden" id="_wpnonce" name="_wpnonce" value="3e1ecd8a35">
								<input type="hidden" name="_wp_http_referer" value="/wp-admin/admin.php?page=wc-settings&amp;tab=checkout&amp;section=zeleri_woo_oficial_payment_gateways&amp;tab_active=settings">		
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

	<!--</div>-->
<!--</div>-->

