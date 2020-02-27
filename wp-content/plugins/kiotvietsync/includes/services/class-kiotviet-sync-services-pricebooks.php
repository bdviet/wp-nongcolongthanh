<?php
require_once plugin_dir_path(dirname(__FILE__)) . '.././vendor/autoload.php';

use Kiotviet\Kiotviet\HttpClient;

class Kiotviet_Sync_Service_PriceBook
{
    public function __construct()
    {
        $this->HttpClient = new HttpClient();
    }

    public function save()
    {
        $data = kiotviet_sync_get_request('data', []);
        if (!empty($data['regularPrice'])) {
            kiotviet_sync_set_data('regular_price', $data['regularPrice']);
        }

        if (!empty($data['salePrice'])) {
            kiotviet_sync_set_data('sale_price', $data['salePrice']);
        }

        wp_send_json($this->HttpClient->responseSuccess(true));
    }

    public function get()
    {
        $regularPrice = kiotviet_sync_get_data('regular_price');
        $salePrice = kiotviet_sync_get_data('sale_price');

        wp_send_json($this->HttpClient->responseSuccess([
            "regular_price" => json_decode(html_entity_decode(stripslashes($regularPrice)), true),
            "sale_price" => json_decode(html_entity_decode(stripslashes($salePrice)), true),
        ]));
    }
}
