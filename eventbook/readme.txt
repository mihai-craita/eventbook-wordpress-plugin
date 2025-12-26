=== Eventbook API Requests ===
Contributors: mihaicraita
Tags: eventbook, events, ticketing, api, booking
Requires at least: 5.5
Tested up to: 6.7
Stable tag: 0.0.2
Requires PHP: 7.4
License: MIT
License URI: https://opensource.org/licenses/MIT

WordPress plugin that integrates the Eventbook API for event ticketing and management.

== Description ==

A WordPress plugin that integrates the Eventbook API for event ticketing and management. This plugin allows you to display events, manage tickets, process transactions, and handle client information directly from your WordPress site.

= Features =

* Display event details
* Show performance information
* Manage client information
* Create and manage transactions
* Add and remove tickets
* Apply discount codes
* Redirect to payment gateway

= API Key Required =

To use this plugin, you need an Eventbook account and API token. Please contact [eventbook.ro](https://eventbook.ro) to request an account and obtain your API key.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/eventbook` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to Settings > General and scroll to the Eventbook settings section
4. Enter your Eventbook API token

== Usage ==

Once installed and configured with your API token, you can use the JavaScript client library in your theme or page templates.

= Get event details =

`evb.getEvent(16500);`

= Get performance info =

`evb.getPerformance(75557);`

= Add a new client =

`evb.addClient({
  "first_name": "Ion",
  "last_name": "Popescu",
  "phone": "040-1110-444",
  "email": "example@example.com",
  "observations": "this is a test client",
  "extra_data": {
    "newsletter": 1,
    "terms_and_conditions": 1
  }
});`

= Complete example =

`let evb = new Eventbook();

async function evbTest() {
  const client = await evb.addClient({
    "first_name": "Ion",
    "last_name": "Popescu",
    "phone": "040-1110-444",
    "email": "example@example.com",
    "observations": "this is a test client",
    "extra_data": {
      "newsletter": 1,
      "terms_and_conditions": 1
    }
  });

  let transaction = await evb.addTransaction();

  await evb.addTickets({
    "performance_id": 75636,
    "number_of_tickets": 1,
    "transaction_id": transaction.id,
    "client_id": client.id
  });

  transaction = await evb.getTransaction(transaction.id);
  console.log(transaction);
  evb.redirectToPaymentGateway(transaction.id);
}

evbTest();`

== Privacy ==

This plugin connects to the Eventbook API (eventbook.ro) to provide ticketing functionality. When you use this plugin:

* Client information (names, email addresses, phone numbers) is transmitted to eventbook.ro
* Transaction and ticket data is stored on Eventbook servers
* API requests include your configured API token for authentication

Please review Eventbook's privacy policy at https://eventbook.ro/privacy before using this plugin.

No data is transmitted to third parties without your explicit use of the plugin's functions.

== Frequently Asked Questions ==

= Where do I get an API token? =

Please contact [eventbook.ro](https://eventbook.ro) to request an account and obtain your API key.

= What endpoints are available? =

The plugin provides REST API endpoints under the namespace `eventbook/v1`:
* GET `/eventbook/v1/event` - Get event details
* GET `/eventbook/v1/performance` - Get performance info
* POST `/eventbook/v1/client` - Add a new client
* POST `/eventbook/v1/transaction` - Create a transaction
* GET `/eventbook/v1/transaction` - Get transaction details
* POST `/eventbook/v1/tickets` - Add tickets
* POST `/eventbook/v1/tickets/remove` - Remove a ticket
* POST `/eventbook/v1/apply-discount-code` - Apply a discount code

= Is the plugin secure? =

Yes, the plugin follows WordPress security best practices including:
* Input validation and sanitization
* Output escaping
* REST API permission callbacks
* Proper authentication checks

== Screenshots ==

1. Eventbook API settings in WordPress Settings > General

== Changelog ==

= 0.0.2 =
* Added WordPress 5.5+ compatibility
* Added REST API permission callbacks (required since WP 5.5)
* Added input validation and sanitization
* Added translation support with text domain
* Improved security with output escaping
* Updated REST API namespace to eventbook/v1
* Improved build script with versioning
* Added proper error handling

= 0.0.1 =
* Initial release
* Basic Eventbook API integration
* JavaScript client library

== Upgrade Notice ==

= 0.0.2 =
This version adds WordPress 5.5+ compatibility and important security improvements. REST API endpoints now use the /eventbook/v1/ namespace.
