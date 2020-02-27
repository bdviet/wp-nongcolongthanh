<?php
/**
 * Created by PhpStorm.
 * User: tuyenvv
 * Date: 10/7/18
 * Time: 6:18 PM
 */

namespace Kiotviet;


class KiotvietConfig
{
    protected $clientId;
    protected $clientSecret;
    protected $retailer;

    public function __construct($clientId = null, $clientSecret = null, $retailer = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->retailer = $retailer;
    }

    public function getConfig()
    {
        return [
            $this->clientId,
            $this->clientSecret,
            $this->retailer,
        ];
    }

    public function getClientID()
    {
        return $this->clientId;
    }

    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    public function getRetailer()
    {
        return $this->retailer;
    }
}