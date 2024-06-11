<?php 
	class Tabla_Transacciones_Zeleri extends WP_List_Table
	{
	    /**
	     * Prepare the items for the table to process
	     *
	     * @return Void
	     */
	    public function prepare_items()
	    {
	        $columns = $this->get_columns();
	        $hidden = $this->get_hidden_columns();
	        $sortable = $this->get_sortable_columns();

	        $data = $this->table_data();
	        usort( $data, array( &$this, 'sort_data' ) );

	        $i = 0;
		    foreach ($data AS $key) {
		        if (isset($key['fecha'])) {
		            $data[$i]['fecha'] = date("d M, Y", strtotime($key['fecha']));
		        }
		        $i++;
		    }

	        $perPage = 10;
	        $currentPage = $this->get_pagenum();
	        $totalItems = count($data);

	        $this->set_pagination_args( array(
	            'total_items' => $totalItems,
	            'per_page'    => $perPage
	        ) );

	        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

	        $this->_column_headers = array($columns, $hidden, $sortable);
	        $this->items = $data;
	    }

	    /**
	     * Override the parent columns method. Defines the columns to use in your listing table
	     *
	     * @return Array
	     */
	    public function get_columns()
	    {
	        $columns = array(
	        	//'cb'           => '<input type="checkbox" />',
	            'pedido_id'    => 'Nro Pedido',
	            'destinatario' => 'Destinatario',
	            'fecha'        => 'Fecha Pedido',
	            'numero_ot'    => 'Orden Transporte',
	            'numero_ce'    => 'N° Certificado',
	            'servicio'     => 'Servicio',
	            'costo'        => 'Costo del Envío',
	            'etiqueta'     => 'Etiqueta'
	        );

	        return $columns;
	    }

	    /**
	     * Define which columns are hidden
	     *
	     * @return Array
	     */
	    public function get_hidden_columns()
	    {
	        return array("ID");
	    }

	    /**
	     * Define the sortable columns
	     *
	     * @return Array
	     */
	    public function get_sortable_columns()
	    {
	    	$columns = array(
	    		'pedido_id' => array('pedido_id', true),
	    		'fecha'     => array('fecha', false),
	    	);
	        return $columns;
	    }

	    /**
	     * Get the table data
	     *
	     * @return Array
	     */
	    private function table_data()
	    {
	        global $wpdb;

	        $query = "
	        	SELECT
	        		post.ID AS orden_id,
				  	DATE_FORMAT(post.post_date, '%d-%m-%Y') AS fecha,
				  	CONCAT(metaFirstName.meta_value, ' ', metaLastName.meta_value) AS destinatario,
				  	metaOTNumber.meta_value AS numero_ot,
				  	metaCNumber.meta_value AS numero_certificado,
                    metaCostoEnvio.meta_value AS costo_envio
				FROM {$wpdb->prefix}posts AS post
				    LEFT JOIN (
				      SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = '_shipping_first_name'
				    ) AS metaFirstName
				ON post.ID = metaFirstName.post_id
				    LEFT JOIN (
				      SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = '_shipping_last_name'
				    ) AS metaLastName
				ON post.ID = metaLastName.post_id
					LEFT JOIN (
				      SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = 'transportOrderNumbers'
				    ) AS metaOTNumber
				ON post.ID = metaOTNumber.post_id
					LEFT JOIN (
				      SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = 'certificateNumber'
				    ) AS metaCNumber
				ON post.ID = metaCNumber.post_id
                LEFT JOIN (
				      SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = '_shipping_total'
				    ) AS metaCostoEnvio
				ON post.ID = metaCostoEnvio.post_id

				WHERE post.post_type = 'shop_order'
				AND metaOTNumber.meta_value != ''
                -- AND post.post_status = 'wc-completed'
	        ";

	        if( isset($_GET['s']) )
			{
				$str = $_GET['s'];
				$query = $query." 
					AND (
						post.ID LIKE '%".$str."%' OR 
						DATE_FORMAT(post.post_date, '%d-%m-%Y') LIKE '%".$str."%' OR 
						metaOTNumber.meta_value LIKE '%".$str."%' OR 
						metaCNumber.meta_value LIKE '%".$str."%'
					)
				";
	    	}

	    	$query = $query." ORDER BY post.ID DESC";

			$results = $wpdb->get_results( $query , OBJECT );

			$data = array();

			foreach ($results as $key => $item) 
			{
				$order = new WC_Order( intval($item->orden_id) );

				$existe = strpos($order->get_shipping_method(), 'Chilexpress');

				if( ($key = $existe) !== false )
				{
					$actions = $this->order_status_actions_button( $order );
					$tracking = $this->get_tracking( $order->get_id() );
					$certificate = $this->get_certificate( $order->get_id() );
					$user_order = $order->get_user();
					$pedido_id = '<a href="'.admin_url('post.php?post='.$order->get_id().'&action=edit').'"> #'.$order->get_id().'</a>';

					$fecha = new DateTime($order->get_date_created());

					$data[] = array(
						//'ID'           => $order->get_id(),
			            'pedido_id'    => $pedido_id,
			            'destinatario' => $item->destinatario,
			            'fecha'        => $fecha->format('d-m-Y'), //$fecha->format('d M, Y'),
			            'numero_ot'    => $tracking,
			            'numero_ce'    => $certificate,
			            'servicio'     => '<small>'.$order->get_shipping_method().'</small>',
			            'costo'        => wc_price( $order->get_shipping_total() ),
			            'etiqueta'     => '<a class="button wc-action-button wc-action-button-'.$actions["action"].' '.$actions["action"].' " href="'.$actions["url"].'" aria-label="'.$actions["name"].'"></a>'
			        );
				}
			}

	        return $data;
	    }

	    /**
	     * Define what data to show on each column of the table
	     *
	     * @param  Array $item        Data
	     * @param  String $column_name - Current column name
	     *
	     * @return Mixed
	     */
	    public function column_default( $item, $column_name )
	    {
	        switch( $column_name ) {
	            case 'pedido_id':
	            case 'destinatario':
	            case 'fecha':
	            case 'numero_ot':
	            case 'numero_ce':
	            case 'servicio':
	            case 'costo':
	            case 'etiqueta':
	                return $item[ $column_name ];

	            default:
	                return print_r( $item, true ) ;
	        }
	    }

	    /**
	     * Allows you to sort the data by the variables set in the $_GET
	     *
	     * @return Mixed
	     */
	    private function sort_data( $a, $b )
	    {
	        // Set defaults
	        $orderby = 'pedido_id';
	        $order = 'desc';

	        // If orderby is set, use this as the sort column
	        if(!empty($_GET['orderby']))
	        {
	            $orderby = $_GET['orderby'];
	        }

	        // If order is set use this as the order
	        if(!empty($_GET['order']))
	        {
	            $order = $_GET['order'];
	        }

	        if($orderby == 'pedido_id')
	        {
	        	$_orderID1 = $this->get_order_id( $a[$orderby] );
	        	$_orderID2 = $this->get_order_id( $b[$orderby] );
	        	$result = ($_orderID1 > $_orderID2) ? +1 : -1;
	        }

	        if($orderby == 'fecha')
	        {
	        	$_fecha1 = strtotime( $a[$orderby] );
	        	$_fecha2 = strtotime( $b[$orderby] );
	        	$result = ($_fecha1 > $_fecha2) ? +1 : -1;
	        }

	        
	        if($order === 'asc')
	        {
	            return $result;
	        }

	        return -$result;
	    }


	    /*public function get_bulk_actions() {
			$actions = array(
		    	'generar_multiples_ot' => 'Generar OT'
		  	);
		  	return $actions;
		}*/

		public function get_order_id( $str ){
			$_str = strip_tags($str);
			$_str = trim($_str);
			$order_id = substr($_str, 1);
			return intval($order_id);
		}


		/*public function column_cb($item) {
			return sprintf(
		    	'<input type="checkbox" name="pedidos[]" value="%s" />', $item['ID']
		    );    
		}*/

	}