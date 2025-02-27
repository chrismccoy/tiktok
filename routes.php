<?php

require_once "vendor/autoload.php";

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TikTokService
{
    private Client $client;
    private string $baseUrl = 'https://www.tikwm.com/api/';

    public function __construct()
    {
        $this->client = new Client();
    }

    private function request(string $endpoint, array $params = []): object
    {
        // Uncomment to use cURL instead of Guzzle
        //return $this->requestCurl($endpoint, $params);
        return $this->requestGuzzle($endpoint, $params);
    }

    private function requestGuzzle(string $endpoint, array $params = []): object
    {
        try {
            $response = $this->client->request('GET', $this->baseUrl . $endpoint, [
                'query' => $params,
                'timeout' => 10,
            ]);
            return json_decode($response->getBody());
        } catch (RequestException $e) {
            return (object)[
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    private function requestCurl(string $endpoint, array $params = []): object
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $endpoint . '?' . http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return (object)[
                'code' => curl_errno($ch),
                'msg' => curl_error($ch),
                'data' => null
            ];
        }
        curl_close($ch);

        return json_decode($response);
    }

    public function searchVideosByKeywords(string $keywords, int $count = 10, int $cursor = 0): object
    {
        return $this->request('feed/search', compact('keywords', 'count', 'cursor'));
    }

    public function getVideoComments(string $url, int $count = 10, int $cursor = 0): object
    {
        return $this->request('comment/list', compact('url', 'count', 'cursor'));
    }

    public function getTrendingFeed(string $region, int $count = 10): object
    {
        return $this->request('feed/list', compact('region', 'count'));
    }

    public function getUserLiked(string $unique_id, int $count = 10, int $cursor = 0): object
    {
        return $this->request('user/favorite', compact('unique_id', 'count', 'cursor'));
    }

    public function getUserFeedVideos(string $unique_id, int $count = 10, int $cursor = 0): object
    {
        return $this->request('user/posts', compact('unique_id', 'count', 'cursor'));
    }

    public function getVideoInfo(string $url, int $hd = 0): object
    {
        return $this->request('', compact('url', 'hd'));
    }

    public function getVideoWithoutWatermark(string $url, int $hd = 0): string
    {
        $response = $this->request('', compact('url', 'hd'));
        return $response->code === 0 ? $response->data->play : $response->msg;
    }
}
