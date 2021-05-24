<?php

namespace Eventbook;

use GuzzleHttp\Client;

class Api
{
    public function getEventInfo(int $eventId)
    {
        $client = new Client(['base_uri' => 'https://eventbook.ro/api/']);
        $response = $client->request('GET', 'events/' . $eventId);
        return \json_decode($response->getBody()->getContents());
    }
}
