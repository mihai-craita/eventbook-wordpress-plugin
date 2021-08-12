<?php

namespace Eventbook;

use GuzzleHttp\Client;

class Api
{
    private $baseUri;
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

    public function getPerformance(int $performanceId)
    {
        try {
            $client = new Client(['base_uri' => $this->baseUri]);
            $response = $client->request('GET', 'performances/' . $performanceId);
            return \json_decode($response->getBody()->getContents());
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function saveClient($client)
    {
        try {
            $apiClient = new Client(['base_uri' => $this->baseUri]);
            $response = $apiClient->request('POST', 'client', [
                'json' => $client,
                'headers' => $this->getAuthHeaders()
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
                'headers' => $this->getAuthHeaders()
            ]);
            $content = $response->getBody()->getContents();
            $responseClient = json_decode($content, true);
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
                'headers' => $this->getAuthHeaders()
            ]);
            $content = $response->getBody()->getContents();
            $responseClient = json_decode($content, true);
            return $responseClient;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function getTransaction(int $transactionId)
    {
        try {
            $client = new Client(['base_uri' => $this->baseUri]);
            $response = $client->request('GET', 'transaction/' . $transactionId, [
                'headers' => $this->getAuthHeaders()
            ]);
            return \json_decode($response->getBody()->getContents());
        } catch (\Exception $ex) {
            throw $ex;
            return $ex->getMessage();
        }
    }

    public function deleteTicket(int $ticketId)
    {
        try {
            $client = new Client(['base_uri' => $this->baseUri]);
            $client->request('DELETE', 'tickets/remove/' . $ticketId, [
                'headers' => $this->getAuthHeaders()
            ]);
            return true;
        } catch (\Exception $ex) {
            throw $ex;
            return false;
        }
    }

    public function applyDiscountCode(string $code, int $transactionId)
    {
        try {
            $apiClient = new Client(['base_uri' => $this->baseUri]);
            $response = $apiClient->request('POST', 'discount-code', [
                'form_params' => ['code' => $code, 'transaction_id' => $transactionId],
                'headers' => $this->getAuthHeaders()
            ]);
            $responseClient = json_decode($response->getBody()->getContents(), true);
            return $responseClient;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    private function getAuthHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept'     => 'application/json',
        ];
    }
}
