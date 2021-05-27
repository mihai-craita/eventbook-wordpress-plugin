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
        $client = new Client(['base_uri' => $this->baseUri]);
        $response = $client->request('GET', 'events/' . $eventId);
        return \json_decode($response->getBody()->getContents());
    }

    public function saveClient($client)
    {
        $apiClient = new Client([ 'base_uri' => $this->baseUri ]);
        $response = $apiClient->request('POST', 'client', [
            'json' => $client,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ]
        ]);
        $responseClient = json_decode($response->getBody()->getContents(), true);
        return $responseClient;
    }

    public function addTickets($ticketOrder)
    {
        $apiClient = new Client([ 'base_uri' => $this->baseUri ]);
        $response = $apiClient->request('POST', 'tickets/add', [
            'json' => $ticketOrder,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ]
        ]);
        $responseClient = json_decode($response->getBody()->getContents(), true);
        return $responseClient;
    }
}
