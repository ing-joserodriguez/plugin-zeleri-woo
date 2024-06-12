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

  // WP_List_Table is not loaded automatically so we need to load it in our application
	if( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	require_once( ABSPATH . 'wp-content/plugins/zeleri/includes/class-zeleri-woo-oficial-table.php' );
?>

<?php 
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<!--<div class="container menu-principal p-5 my-5">-->
	<!--<div class="container"> -->
			<div class="row">
				<div class="col-md-2">
					<ul class="nav nav-tabs flex-column">
						<li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#tabZeleriInicio">Inicio <i class="ph-bold ph-caret-right"></i></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#tabZeleriTransacciones">Transacciones <i class="ph-bold ph-caret-right"></i></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#tabZeleriConfiguracion">Configuración <i class="ph-bold ph-caret-right"></i></a>
						</li>
					</ul>
				</div>
				<div class="col-md-7">
					<div class="tab-content">
						<!--INICIO-->
						<div id="tabZeleriInicio" class="tab-pane fade show active">
							<div class="container">
								<div class="row">
									<div class="col" style="padding: 0px; margin-bottom: 20px;">
										<img src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'admin/images/logo-zeleri.webp'; ?>" alt="Logo Zeleri" height="70px" />
									</div>
								</div>
								<div class="row zeleri-row-header">
									<div class="col zeleri-header-verification-plugin">
										<h3>¡Te damos la bienvenida a Zeleri!</h3>
										<p>Asegúrate de completar todos los pasos para comenzar a operar con el plugin de Zeleri en tu comercio.</p>
									</div>
								</div> 
								<div class="row">
									<div class="col">
										<ul class="zeleri-verificacion-plugin">
											
											<li>
												<div class="row">
													<div class="col">
														<i class="ph-bold ph-number-circle-one"></i>
														<p>Plugin de Zeleri instalado</p>
													</div>
												</div>
											</li>
											
											<li>
												<div class="row">
													<div class="col">
														<i class="ph-bold ph-number-circle-two"></i>
														<p>Mi comercio está activo en Zeleri </br>
															<span class="xs">Puedes verificar el estado de tu comercio en el portal Zeleri.</span>
														</p>
													</div>
													<div class="col">
														<a href="#">Verificar</a>
													</div>
												</div>
											</li>
											
											<li>
												<div class="row">
													<div class="col">
														<i class="ph-bold ph-number-circle-three"></i>
														<p>Mis llaves de API y Zeleri están activas para operar </br> 
															<span class="xs">Solicita tus llaves en soporte@zeleri.com</span>
														</p>
													</div>
													<div class="col">
														<a href="#">Verificar</a>
													</div>
												</div>
											</li>
											
											<li>
												<div class="row">
													<div class="col">
														<i class="ph-bold ph-number-circle-four"></i>
														<p>Configuracion del plugin Zeleri</p>
													</div>
													<div class="col">
														<a id="irZeleriConfiguracion" href="#">Ir a Configuracion</a>
													</div>
												</div>
											</li>

											<li>
												<div class="row">
													<div class="col">
														<i class="ph-bold ph-number-circle-five"></i>
														<p>Haz una compra real para validar que el plugin de Zeleri funciona perfectamente</p>
													</div>
												</div>
											</li>
										</ul>
									</div>
								</div>
								<div class="row zeleri-verificacion-plugin">
									<div class="col">
										<p>Si necesitas ayuda contáctanos o <a href="#">revisa la documentación</a> del plugin</p>
									</div>
								</div>
								<div class="row zeleri-verificacion-plugin">
									<div class="col">
										<i class="ph-bold ph-envelope"></i>
										<p>soporte@zeleri.com</p>
									</div>
									<div class="col">
										<i class="ph-bold ph-phone"></i>
										<p>+023323222312</p>
									</div>
								</div>
							</div>
						</div>
						<!--TRANSACCIONES-->
						<div id="tabZeleriTransacciones" class="tab-pane fade">
							<?php 
								$tablaTransaccionesZeleri = new Tabla_Transacciones_Zeleri();
								$tablaTransaccionesZeleri->prepare_items();
							?>
							<form method="post">
								<input type="hidden" name="page" value="<?php echo admin_url('admin.php?page=wc-settings&tab=checkout&section=zeleri_woo_oficial_payment_gateways&tab_pane=tabZeleriTransacciones'); ?>" />
								<?php $tablaTransaccionesZeleri->search_box( 'Buscar' , 'search_id' ); ?>
							</form>
							
							<a href="<?php echo esc_url( admin_url('admin.php?page=wc-settings&tab=checkout&section=zeleri_woo_oficial_payment_gateways&tab_pane=tabZeleriTransacciones') ); ?>">Mostrar Todo</a>

							<form method="post">
                	<?php $tablaTransaccionesZeleri->display(); ?>
            	</form>
						</div>
						<!--CONFIGURACION-->
						<div id="tabZeleriConfiguracion" class="tab-pane fade">
							<?php $this->generate_settings_html(); ?>
							<p class="submit zeleri-button-submit">
								<button name="save" class="button-primary woocommerce-save-button" type="submit" value="Guardar los cambios">Guardar los cambios</button>
								<?php 
									$nonce = wp_create_nonce('save_option');
									$isVerify wp_verify_nonce( $nonce, "save_option" );
									var_dump($isVerify);
								?>
								<input type="hidden" id="_wpnonce" name="_wpnonce" value="52818129b0"/>
								<input type="hidden" name="_wp_http_referer" value="/wp-admin/admin.php?page=wc-settings&tab=checkout&section=zeleri_woo_oficial_payment_gateways&tab_pane=tabZeleriConfiguracion"/>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!--</div>-->
<!--</div>-->

