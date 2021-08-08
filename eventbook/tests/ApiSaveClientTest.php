<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Eventbook\Api;

final class ApiSaveClientTest extends TestCase
{
    private $token;
    private $baseUri;

    public function setUp(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        $this->token = $_ENV['TOKEN'];
        $this->baseUri = 'https://dev.eventbook.ro/api/';
    }

    public function testSaveClient(): void
    {
        $api = new Api($this->token, $this->baseUri);
        $result = $api->saveClient([
            'first_name' => 'Mihai',
            'last_name' => 'Popescu',
            'email' => 'test@test.com'
        ]);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testAddTransaction(): void
    {
        $api = new Api($this->token, $this->baseUri);
        $result = $api->addTransaction();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testAddTicketAndDeleteItOnTransaction(): void
    {
        $api = new Api($this->token, $this->baseUri);
        $transaction = $api->addTransaction();
        $transaction = $api->addTickets([
            "performance_id" => 75557,
            "number_of_tickets" => 1,
            "transaction_id" => $transaction['id'],
        ]);
        $this->assertIsArray($transaction['tickets']);
        $ticketId = $transaction['tickets'][0]['id'];
        $this->assertIsInt($ticketId);
        $this->assertTrue($api->deleteTicket($ticketId));
    }
}
