<?php
/**
 * Created by PhpStorm.
 * User: tuyenvv
 * Date: 2/11/19
 * Time: 2:31 PM
 */

namespace Kiotviet;


use Kiotviet\Kiotviet\Authentication;
use Kiotviet\Kiotviet\HttpClient;

/**
 * Class Kiotviet
 * @package Kiotviet
 */
class Kiotviet
{
    /**
     *
     */
    const VERSION = '1.0.0';

    /**
     * @var
     */
    protected $accessToken;

    /**
     * @var
     */
    protected $retailer;

    /**
     * @var HttpClient
     */
    protected $client;


    /**
     * Kiotviet constructor.
     * @param KiotvietConfig|null $config
     * @throws \Exception
     */
    public function __construct()
    {
        $this->client = new HttpClient();
    }

    /**
     * @return mixed
     */
    public function getAccessToken(KiotvietConfig $config)
    {
        list($clientId, $clientSecret, $retailer) = $config->getConfig();
        $accessToken = Authentication::getAccessToken($clientId, $clientSecret, $retailer);
        return $accessToken;
    }

    /**
     * @return mixed
     */
    public function getRetailer()
    {
        return Authentication::$retailer;
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \Exception
     */
    public function get($url, array $params, $accessToken, $retailer, $headers = [])
    {
        return $this->client->doRequest('GET', $url, $params, $accessToken, $retailer, $headers);
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \Exception
     */
    public function post($url, array $params, $accessToken, $retailer, $headers = [])
    {
        return $this->client->doRequest('POST', $url, $params, $accessToken, $retailer, $headers);
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \Exception
     */
    public function put($url, array $params, $accessToken, $retailer, $headers = [])
    {
        return $this->client->doRequest('PUT', $url, $params, $accessToken, $retailer, $headers);
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \Exception
     */
    public function delete($url, array $params, $accessToken, $retailer, $headers = [])
    {
        return $this->client->doRequest('DELETE', $url, $params, $accessToken, $retailer, $headers);
    }

    /**
     * @param $method
     * @param $url
     * @param array $params
     * @param $accessToken
     * @param $retailer
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     */
    public function raw($method, $url, array $params, $accessToken, $retailer, $headers = [])
    {
        return $this->client->doRequest($method, $url, $params, $accessToken, $retailer, $headers, 'json');
    }

}