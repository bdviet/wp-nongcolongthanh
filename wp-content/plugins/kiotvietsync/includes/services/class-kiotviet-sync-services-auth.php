<?php

/**
 * Created by KiotvietSync.
 *
 * Name: khanht
 * Email: khanh.t@citigo.com.vn
 * Date: 15/02/19
 */
require_once plugin_dir_path(dirname(__FILE__)) . '.././vendor/autoload.php';

use Kiotviet\Kiotviet;
use Kiotviet\KiotvietConfig;
use Kiotviet\KiotvietEndpoint;
use Kiotviet\Kiotviet\HttpClient;

class Kiotviet_Sync_Service_Auth
{
    private $Kiotviet;
    private $KiotvietEndpoint;
    private $HttpClient;

    public function __construct()
    {
        $this->Kiotviet = new Kiotviet();
        $this->KiotvietEndpoint = new KiotvietEndpoint();
        $this->HttpClient = new HttpClient();
    }

    public function getAccessToken()
    {
        $clientId = kiotviet_sync_get_request('client_id', kiotviet_sync_get_data('client_id'));
        $clientSecret = kiotviet_sync_get_request('client_secret', kiotviet_sync_get_data('client_secret'));
        $retailer = kiotviet_sync_get_request('retailer', kiotviet_sync_get_data('retailer'));
        $json = kiotviet_sync_get_request('json', false);
        $kiotVietConfig = new KiotvietConfig($clientId, $clientSecret, $retailer);
        try {
            $accessToken = $this->Kiotviet->getAccessToken($kiotVietConfig);
        } catch (Exception $e) {
            wp_send_json($this->HttpClient->responseError($e->getMessage(), "Thông tin đăng nhập chưa chính xác !", 100));
        }

        $this->saveAccessToken($kiotVietConfig, $accessToken);
        if ($json) {
            wp_send_json($this->HttpClient->responseSuccess($accessToken));
        }
    }

    private function saveAccessToken(KiotvietConfig $kiotVietConfig, $accessToken)
    {
        kiotviet_sync_set_data('client_id', $kiotVietConfig->getClientID());
        kiotviet_sync_set_data('client_secret', $kiotVietConfig->getClientSecret());
        kiotviet_sync_set_data('access_token', $accessToken['access_token']);
        kiotviet_sync_set_data('expires_in', time() + $accessToken['expires_in']);
    }

    public function saveConfigRetailer()
    {
        $retailer = kiotviet_sync_get_request('retailer', "");
        if ($retailer) {
            kiotviet_sync_set_data('retailer', $retailer);
        }
        wp_send_json($this->HttpClient->responseSuccess(true));
    }

    private function checkAccessToken()
    {
        // Check token exits & expires_in
        if (!empty(kiotviet_sync_get_data('access_token')) && !empty(kiotviet_sync_get_data('expires_in'))) {
            if (time() > (kiotviet_sync_get_data('expires_in') - 3600)) {
                return $this->getAccessToken();
            }
        } else {
            return $this->getAccessToken();
        }
    }

    public function doRequest()
    {
        $method = kiotviet_sync_get_request('method');
        $url = kiotviet_sync_get_request('url');
        $params = kiotviet_sync_get_request('params', []);
        $response = $this->request($method, $url, $params);
        wp_send_json($response);
    }

    public function request($method, $url, $params, $bodyType = '', $headers = [])
    {
        $method = strtolower($method);
        $this->checkAccessToken();
        $accessToken = kiotviet_sync_get_request("accessToken", kiotviet_sync_get_data('access_token'));
        $retailer = kiotviet_sync_get_request("retailer", kiotviet_sync_get_data('retailer'));
        $response = null;
        try {
            
            if ($bodyType == 'json') {
                $response = $this->Kiotviet->raw($method, $url, $params, $accessToken, $retailer, $headers);
            } else {
                switch ($method) {
                    case 'get':
                        $response = $this->Kiotviet->get($url, $params, $accessToken, $retailer, $headers);
                        break;
                    case 'post':
                        $response = $this->Kiotviet->post($url, $params, $accessToken, $retailer, $headers);
                        break;
                    case 'put':
                        $response = $this->Kiotviet->put($url, $params, $accessToken, $retailer, $headers);
                        break;
                    case 'delete':
                        $response = $this->Kiotviet->delete($url, $params, $accessToken, $retailer, $headers);
                        break;
                    default:
                        break;
                }
            }
        } catch (Exception $exception) {
            $response = $exception->getMessage();
        }

        if (!empty($response['error']) && $response['error']['responseStatus']['errorCode'] == "TokenException") {
            $this->getAccessToken();
            $this->doRequest();
        }
        return $response;
    }
}
