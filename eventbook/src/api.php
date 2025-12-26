<?php

namespace Eventbook;

class Api
{
    private $baseUri;
    private $accessToken;

    public function __construct($accessToken, $baseUri = 'https://eventbook.ro/api/')
    {
        $this->baseUri = rtrim($baseUri, '/') . '/';
        $this->accessToken = $accessToken;
    }

    public function getEventInfo(int $eventId)
    {
        $url = $this->baseUri . 'events/' . $eventId;
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return $response->get_error_message();
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body);
    }

    public function getPerformance(int $performanceId)
    {
        $url = $this->baseUri . 'performances/' . $performanceId;
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return $response->get_error_message();
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body);
    }

    public function saveClient($client)
    {
        $url = $this->baseUri . 'client';
        $response = wp_remote_post($url, [
            'headers' => $this->getAuthHeaders(),
            'body' => json_encode($client),
            'timeout' => 15
        ]);

        if (is_wp_error($response)) {
            return $response->get_error_message();
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }

    public function addTickets($ticketOrder)
    {
        $url = $this->baseUri . 'tickets/add';
        $response = wp_remote_post($url, [
            'headers' => $this->getAuthHeaders(),
            'body' => json_encode($ticketOrder),
            'timeout' => 15
        ]);

        if (is_wp_error($response)) {
            return $response->get_error_message();
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }

    public function addTransaction()
    {
        $url = $this->baseUri . 'transaction';
        $response = wp_remote_post($url, [
            'headers' => $this->getAuthHeaders(),
            'timeout' => 15
        ]);

        if (is_wp_error($response)) {
            return $response->get_error_message();
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }

    public function getTransaction(int $transactionId)
    {
        $url = $this->baseUri . 'transaction/' . $transactionId;
        $response = wp_remote_get($url, [
            'headers' => $this->getAuthHeaders(),
            'timeout' => 15
        ]);

        if (is_wp_error($response)) {
            throw new \Exception(esc_html($response->get_error_message()));
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body);
    }

    public function deleteTicket(int $ticketId)
    {
        $url = $this->baseUri . 'tickets/remove/' . $ticketId;
        $response = wp_remote_request($url, [
            'method' => 'DELETE',
            'headers' => $this->getAuthHeaders(),
            'timeout' => 15
        ]);

        if (is_wp_error($response)) {
            throw new \Exception(esc_html($response->get_error_message()));
        }

        return true;
    }

    public function applyDiscountCode(string $code, int $transactionId)
    {
        $url = $this->baseUri . 'discount-code';
        $response = wp_remote_post($url, [
            'headers' => $this->getAuthHeaders(),
            'body' => [
                'code' => $code,
                'transaction_id' => $transactionId
            ],
            'timeout' => 15
        ]);

        if (is_wp_error($response)) {
            return $response->get_error_message();
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }

    private function getAuthHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];
    }
}
