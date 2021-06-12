<?php

namespace Eventbook;

use GuzzleHttp\Client;

class Api
{
    private $baseUri = 'https://dev.eventbook.ro/api/';
    private $accessToken;

    public function __construct($accessToken, $baseUri = 'https://dev.eventbook.ro/api/')
    {
        $this->baseUri = $baseUri;
        $this->accessToken = $accessToken;
    }

    public function getEventInfo(int $eventId)
    {
        try {
            $client = new Client(['base_uri' => $this->baseUri]);
            $response = $client->request('GET', 'events/' . $eventId);
            return \json_decode($response->getBody()->getContents());
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function saveClient($client)
    {
        try {
            $apiClient = new Client([ 'base_uri' => $this->baseUri ]);
            $response = $apiClient->request('POST', 'client', [
                'json' => $client,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                ]
            ]);
            $responseClient = json_decode($response->getBody()->getContents(), true);
            return $responseClient;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function addTickets($ticketOrder)
    {
        try {
            $apiClient = new Client([ 'base_uri' => $this->baseUri ]);
            $response = $apiClient->request('POST', 'tickets/add', [
                'json' => $ticketOrder,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                ]
            ]);
            $responseClient = json_decode($response->getBody()->getContents(), true);
            return $responseClient;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function addTransaction()
    {
        try {
            $apiClient = new Client([ 'base_uri' => $this->baseUri ]);
            $response = $apiClient->request('POST', 'transaction', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                ]
            ]);
            $responseClient = json_decode($response->getBody()->getContents(), true);
            return $responseClient;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
