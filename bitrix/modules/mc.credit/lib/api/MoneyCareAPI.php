<?php

namespace Mc\Credit\Api;

use Mc\Credit\Client\CurlHttpClient;

class MoneyCareAPI
{
    public $httpClient;

    public $host;

    const CREATE_URL = '/broker/api/v2/orders/create';

    const CREDIT_TYPE_CLASSIC = 'classic';
    const CREDIT_TYPE_INSTALLMENT = 'installment';

    const COEFFICIENT = 1.25;

    public function __construct($host, $login, $password)
    {
        $this->host = $host;

        $headers = array('Authorization: Basic ' . base64_encode($login . ':' . $password));
        $this->httpClient = new CurlHttpClient($headers);
    }

    public function create($post)
    {
        $url = $this->buildUrl(self::CREATE_URL);

        return $this->httpClient->getContent($url, $post);
    }

    public static function priceInMonth($price, $month = 20)
    {
        return ceil($price * self::COEFFICIENT / $month);
    }

    private function buildUrl($url)
    {
        return $this->host . $url;
    }
}