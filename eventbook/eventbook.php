<?php

/**
 * Plugin Name: Eventbook API Requests
 * Plugin URI: https://eventbook.ro/
 * Description: WordPress plugin that integrates the Eventbook API for event ticketing and management.
 * Version: 0.0.3
 * Author: Mihai Craita
 * Author URI: https://eventbook.ro/
 * Requires at least: 5.5
 * Tested up to: 6.9
 * Requires PHP: 7.4
 * License: MIT
 * Text Domain: eventbook
 *
 * @package EventbookApi
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

use Eventbook\Api;

add_action('admin_init', 'eventbook_add_setting');

function eventbook_add_setting()
{
    add_settings_section(
        'evb_settings_section',
        __('Eventbook Settings', 'eventbook'),
        'eventbook_section_callback',
        'general'
    );

    register_setting('general', 'evb_api_token', [
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ]);

    add_settings_field(
        'evb_api_token',
        __('Eventbook API Token', 'eventbook'),
        'eventbook_api_token_setting_callback',
        'general',
        'evb_settings_section',
        array('label_for' => 'evb_api_token')
    );
}

function eventbook_api_token_setting_callback()
{
    $token = get_option('evb_api_token', '');
    printf(
        '<input type="text" id="evb_api_token" name="evb_api_token" value="%s" class="regular-text" />',
        esc_attr($token)
    );
}

function eventbook_section_callback()
{
    echo '<p>' . esc_html__('The API token can be obtained by contacting eventbook.ro', 'eventbook') . '</p>';
}

/* register api routes */
add_action('rest_api_init', function () {
    // Public endpoints (no authentication required)
    register_rest_route('eventbook/v1', '/event', [
        'methods' => 'GET',
        'callback' => 'eventbook_get_event_info',
        'permission_callback' => '__return_true',
        'args' => [
            'eventId' => [
                'required' => true,
                'validate_callback' => function($param) {
                    return is_numeric($param);
                },
                'sanitize_callback' => 'absint'
            ]
        ]
    ]);

    register_rest_route('eventbook/v1', '/performance', [
        'methods' => 'GET',
        'callback' => 'eventbook_get_performance',
        'permission_callback' => '__return_true',
        'args' => [
            'performanceId' => [
                'required' => true,
                'validate_callback' => function($param) {
                    return is_numeric($param);
                },
                'sanitize_callback' => 'absint'
            ]
        ]
    ]);

    // Endpoints that require API token to be configured
    register_rest_route('eventbook/v1', '/client', [
        'methods' => 'POST',
        'callback' => 'eventbook_add_client',
        'permission_callback' => 'eventbook_check_api_configured'
    ]);

    register_rest_route('eventbook/v1', '/transaction', [
        [
            'methods' => 'POST',
            'callback' => 'eventbook_add_transaction',
            'permission_callback' => 'eventbook_check_api_configured'
        ],
        [
            'methods' => 'GET',
            'callback' => 'eventbook_get_transaction',
            'permission_callback' => 'eventbook_check_api_configured',
            'args' => [
                'transactionId' => [
                    'required' => true,
                    'validate_callback' => function($param) {
                        return is_numeric($param);
                    },
                    'sanitize_callback' => 'absint'
                ]
            ]
        ]
    ]);

    register_rest_route('eventbook/v1', '/tickets', [
        'methods' => 'POST',
        'callback' => 'eventbook_add_tickets',
        'permission_callback' => 'eventbook_check_api_configured'
    ]);

    register_rest_route('eventbook/v1', '/tickets/remove', [
        'methods' => 'POST',
        'callback' => 'eventbook_delete_ticket',
        'permission_callback' => 'eventbook_check_api_configured',
        'args' => [
            'ticketId' => [
                'required' => true,
                'validate_callback' => function($param) {
                    return is_numeric($param);
                },
                'sanitize_callback' => 'absint'
            ]
        ]
    ]);

    register_rest_route('eventbook/v1', '/apply-discount-code', [
        'methods' => 'POST',
        'callback' => 'eventbook_apply_discount_code',
        'permission_callback' => 'eventbook_check_api_configured',
        'args' => [
            'code' => [
                'required' => true,
                'sanitize_callback' => 'sanitize_text_field'
            ],
            'transaction_id' => [
                'required' => true,
                'validate_callback' => function($param) {
                    return is_numeric($param);
                },
                'sanitize_callback' => 'absint'
            ]
        ]
    ]);
});

/**
 * Check if API token is configured
 */
function eventbook_check_api_configured()
{
    $token = get_option('evb_api_token', '');
    if (empty($token)) {
        return new WP_Error(
            'eventbook_not_configured',
            __('Eventbook API token is not configured. Please configure it in Settings > General.', 'eventbook'),
            array('status' => 403)
        );
    }
    return true;
}

function eventbook_get_api_token()
{
    return get_option('evb_api_token', '');
}

function eventbook_get_event_info($request)
{
    $api = new Api(eventbook_get_api_token());
    $eventId = absint($request->get_param('eventId'));
    $result = $api->getEventInfo($eventId);
    return rest_ensure_response($result);
}

function eventbook_get_performance($request)
{
    $api = new Api(eventbook_get_api_token());
    $performanceId = absint($request->get_param('performanceId'));
    $result = $api->getPerformance($performanceId);
    return rest_ensure_response($result);
}

function eventbook_add_client($request)
{
    $api = new Api(eventbook_get_api_token());
    $client = $request->get_json_params();

    if (empty($client)) {
        return new WP_Error(
            'invalid_data',
            __('Invalid client data provided.', 'eventbook'),
            array('status' => 400)
        );
    }

    $result = $api->saveClient($client);
    return rest_ensure_response($result);
}

function eventbook_add_tickets($request)
{
    $api = new Api(eventbook_get_api_token());
    $ticketOrder = $request->get_json_params();

    if (empty($ticketOrder)) {
        return new WP_Error(
            'invalid_data',
            __('Invalid ticket order data provided.', 'eventbook'),
            array('status' => 400)
        );
    }

    $result = $api->addTickets($ticketOrder);
    return rest_ensure_response($result);
}

function eventbook_delete_ticket($request)
{
    $api = new Api(eventbook_get_api_token());
    $ticketId = absint($request->get_param('ticketId'));
    $result = $api->deleteTicket($ticketId);
    return rest_ensure_response($result);
}

function eventbook_add_transaction($request)
{
    $api = new Api(eventbook_get_api_token());
    $result = $api->addTransaction();
    return rest_ensure_response($result);
}

function eventbook_get_transaction($request)
{
    $api = new Api(eventbook_get_api_token());
    $transactionId = absint($request->get_param('transactionId'));
    $result = $api->getTransaction($transactionId);
    return rest_ensure_response($result);
}

function eventbook_apply_discount_code($request)
{
    $api = new Api(eventbook_get_api_token());
    $code = sanitize_text_field($request->get_param('code'));
    $transactionId = absint($request->get_param('transaction_id'));
    $result = $api->applyDiscountCode($code, $transactionId);
    return rest_ensure_response($result);
}

/**
 * Register and enqueue the evb.js file
 */
add_action('wp_enqueue_scripts', 'eventbook_register_javascripts');
function eventbook_register_javascripts()
{
    wp_enqueue_script(
        'eventbook',
        plugins_url('/js/evb.js', __FILE__),
        [],
        '0.0.2',
        true
    );
}
