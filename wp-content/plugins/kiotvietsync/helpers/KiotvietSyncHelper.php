<?php
/**
 * Created by PhpStorm.
 * User: pafon
 * Date: 3/7/19
 * Time: 12:38 AM
 */

class KiotvietSyncHelper
{
    public static function registerWebhook()
    {
        $randomStr = get_option('webhook_key');
        if (empty($randomStr)) {
            //  Create webhook key
            $randomStr = substr(md5(time()), 2, 10);
            add_option('webhook_key', $randomStr, '', 'yes');
        }

        $prefixEndPoint = 'kiotviet-sync/v1/' . $randomStr;

        //  Build url to register webhook kiotviet.
        $kiotvietApi = new Kiotviet_Sync_Service_Auth();
        $webhookUrl = get_rest_url(null, $prefixEndPoint . '/webhook/') . '?noecho';

        $types = ['product.update', 'stock.update', 'order.update', 'product.delete'];

        $webhooks = $kiotvietApi->request('get', 'https://public.kiotapi.com/webhooks', []);
        $webhooks = $webhooks['data']['data'];

        //  Delete all old webhooks
        foreach ($webhooks as $webhook) {
            $type = $webhook['type'];
            if (in_array($type, $types)) {
                $kiotvietApi->request('delete', 'https://public.kiotapi.com/webhooks/' . $webhook['id'], []);
            }
        }

        foreach ($types as $type) {
            $payload = [
                'Webhook' => [
                    'Type' => $type,
                    'Url' => $webhookUrl,
                    'IsActive' => true,
                    'Description' => 'Webhook for update product'
                ]
            ];
            $kiotvietApi->request('post', 'https://public.kiotapi.com/webhooks', $payload, 'json');
        }

    }

    public static function removeWebhook()
    {
        $kiotvietApi = new Kiotviet_Sync_Service_Auth();
        $webhooks = $kiotvietApi->request('get', 'https://public.kiotapi.com/webhooks', []);
        $webhooks = $webhooks['data']['data'];

        $removeTypes = ['product.delete', 'product.update', 'stock.update', 'order.update'];

        foreach ($webhooks as $webhook) {
            $type = $webhook['type'];
            if (in_array($type, $removeTypes)) {
                $kiotvietApi->request('delete', 'https://public.kiotapi.com/webhooks/' . $webhook['id'], []);
            }
        }
        delete_option('webhook_key');
    }
}
