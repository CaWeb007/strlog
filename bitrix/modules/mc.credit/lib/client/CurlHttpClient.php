<?php

namespace Mc\Credit\Client;

use Exception;

class CurlHttpClient
{
    public $ch;

    public $defaultOpts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_TIMEOUT => 30,
    ];

    public function __construct($headers)
    {
        if (!function_exists('curl_init')) {
            throw new Exception('You must have CURL enabled in order to use this extension.');
        }

        $this->defaultOpts[CURLOPT_HTTPHEADER] = array_merge($this->defaultOpts[CURLOPT_HTTPHEADER], $headers);
        $this->ch = curl_init();
    }

    public function getContent($url, $post = false)
    {
        $this->setOpt($this->defaultOpts);
        $this->setOpt(array(CURLOPT_URL => $url));

        if ($post) {
            $this->setOpt(array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $post
            ));
        }
        $response = curl_exec($this->ch);
        $contentType = curl_getinfo($this->ch, CURLINFO_CONTENT_TYPE);
        $httpCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        return $response;
    }

    /**
     * @param $opts array
     */
    private function setOpt($opts)
    {
        foreach ($opts as $opt => $value) {
            curl_setopt($this->ch, $opt, $value);
        }
    }

}