<?php

/**
 * @package EventbookApi
 * @version 0.0.1
Plugin Name: Eventbook Api Requests
Plugin URI: http://wordpress.org/plugins/eventbook-api-requests/
Description: This is a plugin that calls eventbook API.
You can use it to make requests for your events by adding tickets or getting infos.
Author: Mihai Craita
Version: 0.0.1
Author URI: https://eventbook.ro/
*/

require_once __DIR__ . '/vendor/autoload.php';

use Eventbook\Api;

add_action( 'rest_api_init', function () {
    register_rest_route('eventbook', '/event', [
        'methods' => 'GET',
        'callback' => 'getEventInfo'
    ]);
    register_rest_route('eventbook', '/client', [
        'methods' => 'POST',
        'callback' => 'postClient'
    ]);
});

function getEventInfo($request)
{
    $eventId = (int) $request->get_param('eventId');
    $api  = new Api();
    return rest_ensure_response($api->getEventInfo($eventId));
}

function postClient($request)
{
    $client = $request->get_json_params();
    $api  = new Api();
    return rest_ensure_response($api->saveClient($client));
}

/*
 * register the evb.js file
 */
add_action('wp_enqueue_scripts', 'register_evb_javascripts');
function register_evb_javascripts(){
    wp_register_script('evb.js', plugins_url('/js/evb.js', __FILE__), [], '', true);
    wp_enqueue_script('evb.js');
}
