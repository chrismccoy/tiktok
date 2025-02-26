<?php

class TikTok_API
{
    private $client;

    private $headers;

    public function __construct()
    {
    	$this->headers = [
        	'Keep-Alive' => '300',
        	'Connection' => 'keep-alive',
        	'Cache-Control' => 'max-age=0',
        	'Accept-Charset' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
        	'Accept-Language' => 'en-us,en;q=0.5',
        	'Pragma' => ' ',
        	'Accept' => 'text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5',
        	'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/81.0',
        	'Referer' => 'https://www.tikwm.com',
    	];

        $this->client = new GuzzleHttp\Client([
            'base_uri'    => 'https://www.tikwm.com',
	    'http_errors' => false,
	    'verify'      => false,
	    'headers'     => $this->headers,
	    'debug'    => false,
        ]);
    }

    public function getMedia($id)
    {
        try {
                $res = $this->client->request('GET', '/api', [
                        'query' => ['url' => $id],
                ]);
        } catch (GuzzleException $e) {
            return $e->getResponse();
        }

        $response = json_decode($res->getBody());
        return $response->data->play;
    }

}
