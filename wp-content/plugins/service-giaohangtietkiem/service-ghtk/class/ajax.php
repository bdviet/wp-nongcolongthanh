<?php

if ( !class_exists( 'SVW_Ajax' ) ) {
	class SVW_Ajax {

		function __construct() {
			add_action( 'wp_ajax_update_checkout_district', array( $this, 'update_checkout_district' ) );
			add_action( 'wp_ajax_nopriv_update_checkout_district', array( $this, 'update_checkout_district' ) );

			add_action( 'wp_ajax_admin_update_shipping_method_district', array( $this, 'admin_update_shipping_method_district' ) );
			add_action( 'wp_ajax_nopriv_admin_update_shipping_method_district', array( $this, 'admin_update_shipping_method_district' ) );

			add_action( 'wp_ajax_update_checkout_ward', array( $this, 'update_checkout_ward' ) );
			add_action( 'wp_ajax_nopriv_update_checkout_ward', array( $this, 'update_checkout_ward' ) );

			add_action( 'wp_ajax_admin_update_shipping_method_ward', array( $this, 'admin_update_shipping_method_ward' ) );
			add_action( 'wp_ajax_nopriv_admin_update_shipping_method_ward', array( $this, 'admin_update_shipping_method_ward' ) );

			add_action( 'wp_ajax_create_order_ghtk', array( $this, 'create_order_ghtk' ) );
			add_action( 'wp_ajax_nopriv_create_order_ghtk', array( $this, 'create_order_ghtk' ) );

			add_action( 'wp_ajax_get_status_order_ghtk', array( $this, 'get_status_order_ghtk' ) );
			add_action( 'wp_ajax_nopriv_get_status_order_ghtk', array( $this, 'get_status_order_ghtk' ) );

            add_action( 'wp_ajax_cancel_package_ghtk', array( $this, 'cancel_package_ghtk' ) );
            add_action( 'wp_ajax_nopriv_cancel_package_ghtk', array( $this, 'cancel_package_ghtk' ) );

            add_action( 'wp_ajax_print_package_ghtk', array( $this, 'print_package_ghtk' ) );
            add_action( 'wp_ajax_nopriv_print_package_ghtk', array( $this, 'print_package_ghtk' ) );
		}

		function update_checkout_district() {
			if ( isset( $_POST['city_id'] ) ) {
				$city_id          = $_POST['city_id'];
				WC()->session->set( 'city_id', $city_id );
				SVW_Ultility::show_districts_option_by_city_id( $city_id );
			}
			die();
		}

		function admin_update_shipping_method_district() {
			if ( isset( $_POST['city_id'] ) ) {
				$city_id          = sanitize_key($_POST['city_id']);
				SVW_Ultility::show_districts_option_by_city_id( $city_id );
			}
			die();
		}

		function update_checkout_ward() {
			if ( isset( $_POST['district_id'] ) ) {
				$district_id          = sanitize_key($_POST['district_id']);
				WC()->session->set( 'district_id', $district_id );
				SVW_Ultility::show_wards_option_by_district_id( $district_id );
			}
			die();
		}

		function admin_update_shipping_method_ward() {
			if ( isset( $_POST['district_id'] ) ) {
				$district_id          = sanitize_key($_POST['district_id']);
				SVW_Ultility::show_wards_option_by_district_id( $district_id );
			}
			die();
		}

		function create_order_ghtk() {
			$products    = array();
			$order       = wc_get_order( sanitize_key($_POST['order_id']) );
			$weight_unit = get_option('woocommerce_weight_unit'); 
			foreach ( $order->get_items() as $item_id => $item_data ) {
				$product      = $item_data->get_product();
				$total_weight = $product->get_weight()*$item_data->get_quantity();
				if ( $weight_unit == 'g' ) {
	                $total_weight = $total_weight/1000;
	            } else {
	                $total_weight = $total_weight;
	            }


				$products[] = array(
					'name'     => $product->get_name(),
					'weight'   => $total_weight,
					'quantity' => $item_data->get_quantity(),
				);
			}

			$info_order = array(
				'products' => $products,
				'order' => array (
					'id'            => (int) $_POST['order_id'],
					'pick_name'     => sanitize_text_field($_POST['sender_name']),
					'pick_address'  => sanitize_text_field($_POST['sender_address']),
					'pick_province' => sanitize_text_field($_POST['sender_city']),
					'pick_district' => $_POST['sender_district'],
					'pick_ward'     => $_POST['sender_ward'],
					'pick_tel'      => $_POST['sender_phone'],
					'tel'           => $_POST['recipient_phone'],
					'name'          => $_POST['recipient_name'],
					'address'       => $_POST['recipient_address'],
					'province'      => $_POST['recipient_city'],
					'district'      => $_POST['recipient_district'],
					'ward'          => $_POST['recipient_ward'],
					'is_freeship'   => 1,
					'pick_money'    => $_POST['cod_fee'],
					'note'          => $_POST['recipient_note_extra'],
			    )
			);

			$response_service = wp_remote_post( SVW_API_GHTK_URL."/services/shipment/order", array(
				'method'  => 'POST',
				'timeout' => 5000,
				'body'    => json_encode( $info_order ),
				'headers' => array( 'Content-Type' => 'application/json; charset=utf-8', 'Token' => $_POST['sender_token'] ),
	            )
	        );
	        if ( is_wp_error( $response_service ) ) {
	            $error_message = $response_service->get_error_message();
	            echo "Lỗi: $error_message";
	        } else {
	            $success = json_decode( $response_service['body'] )->success;
	            if ( $success ) {
	            	$order = json_decode( $response_service['body'] )->order;
	            	echo 'Đăng đơn hàng thành công! Mã đơn hàng GHTK: '.$order->label;
	            	update_post_meta( $_POST['order_id'], '_ghtk_code', $order->label );
	            } else {
	            	echo json_decode( $response_service['body'] )->message;
	            }
	        }

			die();
		}

		function get_status_order_ghtk() {
			$ghtk_code = sanitize_text_field($_POST['ghtk_code']);
			$token     = sanitize_text_field($_POST['token']);
			$response_status = wp_remote_post( SVW_API_GHTK_URL."/services/shipment/v2/".$ghtk_code, array(
				'method'  => 'POST',
				'headers' => array( 'Content-Type' => 'application/json; charset=utf-8', 'Token' => $token ),
	            )
	        );

	        if ( is_wp_error( $response_status ) ) {
	            $error_message = $response_status->get_error_message();
	            echo "Lỗi: $error_message";
	        } else {
	            $order = json_decode( $response_status['body'] )->order;
	            if ( $order ) {
	            	echo wp_kses_post( $order->status_text );
	            } else {
	            	esc_html_e( 'Đơn hàng không tồn tại hoặc đã bị xoá trên hệ thống', 'svw' );
	            }
	        }
			
			die();
		}

		function cancel_package_ghtk() {
            $ghtk_code = sanitize_text_field($_POST['ghtk_code']);
            $token     = sanitize_text_field($_POST['token']);
            $response_status = wp_remote_post( SVW_API_GHTK_URL."/services/shipment/cancel/".$ghtk_code, array(
                    'method'  => 'POST',
                    'headers' => array( 'Token' => $token ),
                )
            );

            if ( is_wp_error( $response_status ) ) {
                $error_message = $response_status->get_error_message();
                echo "Lỗi: $error_message";
            } else {
                $success = json_decode( $response_status['body'] )->success;
                if ( $success ) {
                    echo esc_html_e( 'Hủy thành công mã đơn ' . $ghtk_code , 'svw');
                } else {
                    esc_html_e( 'Đơn hàng không tồn tại hoặc đã bị xoá trên hệ thống', 'svw' );
                }
            }

            die();
        }

        function print_package_ghtk() {
            $ghtk_code = sanitize_text_field($_GET['ghtk_code']);
            $token     = sanitize_text_field($_GET['token']);
            $response_status = wp_remote_post( SVW_API_GHTK_URL."/services/label/".$ghtk_code, array(
                    'method'  => 'POST',
                    'headers' => array(
                        'Token' => $token
                    )
                )
            );
            $filename = $ghtk_code . '.pdf';
            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            echo $response_status['body'];
            die();
        }
	}

	new SVW_Ajax();
}