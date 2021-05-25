<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Eventbook\Api;

final class ApiSaveClientTest extends TestCase
{
    public function testSaveClient(): void
    {
        $api = new Api();
        $result = $api->saveClient(['first_name' => 'Mihai']);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
    }
}
