<?php
require_once plugin_dir_path(dirname(__FILE__)) . '.././vendor/autoload.php';

use Kiotviet\Kiotviet\HttpClient;

class Kiotviet_Sync_Service_Config
{
    public function __construct()
    {
        $this->HttpClient = new HttpClient();
    }

    public function getConfig()
    {
        $clientId = kiotviet_sync_get_data('client_id', "");
        $clientSecret = kiotviet_sync_get_data('client_secret', "");
        $retailer = kiotviet_sync_get_data('retailer', "");
        $autoSyncOrder = kiotviet_sync_get_data('auto_sync_order', "");
        $productSync = kiotviet_sync_get_data('product_sync', []);
        return wp_send_json($this->HttpClient->responseSuccess(array(
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'retailer' => $retailer,
            'auto_sync_order' => $autoSyncOrder === "true" ? true : false,
            'product_sync' => $productSync
        )));
    }

    public function saveConfig()
    {
        $request = kiotviet_sync_get_request('data', []);
        $productSync = !empty($request['product_sync']) ? $request['product_sync'] : [];
        if (!empty($request['auto_sync_order'])) {
            kiotviet_sync_set_data('auto_sync_order', $request['auto_sync_order']);
        }

        kiotviet_sync_set_data('product_sync', $productSync);

        wp_send_json($this->HttpClient->responseSuccess(true));
    }

    public function removeConfig()
    {
        kiotviet_sync_delete_data("client_id");
        kiotviet_sync_delete_data("client_secret");
        kiotviet_sync_delete_data("retailer");
        kiotviet_sync_delete_data("auto_sync_order");
        return wp_send_json($this->HttpClient->responseSuccess(true));
    }
}
