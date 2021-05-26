<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Eventbook\Api;

final class ApiSaveClientTest extends TestCase
{
    public function testSaveClient(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        $token = $_ENV['TOKEN'];
        $api = new Api($token);
        $result = $api->saveClient(['first_name' => 'Mihai']);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
    }
}
