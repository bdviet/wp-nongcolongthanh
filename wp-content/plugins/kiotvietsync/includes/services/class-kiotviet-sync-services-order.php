<?php
require_once plugin_dir_path(dirname(__FILE__)) . '.././vendor/autoload.php';

use Kiotviet\Kiotviet\HttpClient;

class Kiotviet_Sync_Service_Order
{
    public function __construct()
    {
        $this->HttpClient = new HttpClient();
    }

    public function reSyncOrder()
    {
        $orderId = sanitize_key(kiotviet_sync_get_request('order'));
        if (!class_exists('OrderHookAction')) {
            require_once KIOTVIET_PLUGIN_PATH . 'includes/public_actions/OrderHookAction.php';
        }

        $orderHookAction = new OrderHookAction();
        $clientId = kiotviet_sync_get_data('client_id', "");
        $clientSecret = kiotviet_sync_get_data('client_secret', "");
        $retailer = kiotviet_sync_get_data('retailer', "");
        if ($clientId && $clientSecret && $retailer) {
            $response = $orderHookAction->order_processed($orderId, true);
        } else {
            $response = [
                "msg" => "Website không có kết nối với gian hàng KiotViet",
                "status" => "error",
            ];
        }

        wp_send_json($response);
    }
}
