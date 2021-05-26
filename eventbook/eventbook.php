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

add_action('admin_init', 'addSetting');
function addSetting() {
    add_settings_section('evb_settings_section', 'Eventbook settings', 'evb_section_callback_function', 'general');
    register_setting('general', 'evb_api_token');
    add_settings_field(
        'evb_api_token',
        'Eventbook API Token',
        'evb_api_token_setting_callback_function',
        'general',
        'evb_settings_section',
        array( 'label_for' => 'evb_api_token' )
    );
}

function evb_api_token_setting_callback_function() {
    return _e('<input type="text" name="evb_api_token" value="' . get_option('evb_api_token') . '" />');
}

function evb_section_callback_function() {
    _e('<p>The api token can be obtained by contacting eventbook.ro</p>','jwl-ultimate-tinymce');
}

/* register api routes */
$api = new Api(get_option('evb_api_token'));
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
    return rest_ensure_response($api->getEventInfo($eventId));
}

function postClient($request)
{
    $client = $request->get_json_params();
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
