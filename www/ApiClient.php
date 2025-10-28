<?php
require_once __DIR__ . '/vendor/autoload.php';
use GuzzleHttp\Client;

class ApiClient {
    private Client $client;

    public function __construct() {
    $this->client = new Client([
        'headers' => ['User-Agent' => 'PHP-App']
    ]);
}

    public function request(string $url): array {
        try {
            $response = $this->client->get($url);
            $body = $response->getBody()->getContents();
            return json_decode($body, true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}