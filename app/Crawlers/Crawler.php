<?php

namespace App\Crawlers;

abstract class Crawler
{
    private $ch;
    public function __construct()
    {
        $this->ch = curl_init();
        
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST , 'GET');
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
    }

    public function getResponse($url)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);

        return curl_exec($this->ch);
    }
}