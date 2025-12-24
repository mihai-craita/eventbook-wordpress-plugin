# Eventbook WordPress Plugin

A WordPress plugin that integrates the Eventbook API for event ticketing and management. This plugin allows you to display events, manage tickets, process transactions, and handle client information directly from your WordPress site.

## Installation

1. Download the plugin archive (zip or tar.gz)
2. Upload to your WordPress site via **Plugins > Add New > Upload Plugin**
3. Activate the plugin
4. Go to **Settings > General** and scroll to the **Eventbook settings** section
5. Enter your Eventbook API token

## Getting an API Key

To use this plugin, you need an Eventbook account and API token. Please contact [eventbook.ro](https://eventbook.ro) to request an account and obtain your API key.

## Usage

Once installed and configured with your API token, you can use the JavaScript client library in your theme or page templates.

### Available Methods

#### Get event details
```javascript
evb.getEvent(16500);
```

#### Get performance info
```javascript
evb.getPerformance(75557);
```

#### Add a new client
```javascript
evb.addClient({
  "first_name": "Ion",
  "last_name": "Popescu",
  "phone": "040-1110-444",
  "email": "mihai@eventbook.ro",
  "observations": "this is a test client",
  "extra_data": {
    "newsletter": 1,
    "terms_and_conditions": 1
  }
});
```

#### Add a new transaction
```javascript
evb.addTransaction();
```

#### Add tickets for a specific performance
```javascript
evb.addTickets({
  "performance_id": performance.id,
  "number_of_tickets": 1,
  "transaction_id": transaction.id,
  "client_id": client.id
});
```

#### Get transaction details
```javascript
evb.getTransaction(transaction.id);
```

#### Redirect to payment gateway
```javascript
evb.redirectToPaymentGateway(transaction.id);
```

#### Delete a ticket from transaction
```javascript
let ticketId = 1234;
evb.deleteTicket(ticketId);
```

### Complete Example

Below is a full example of creating a client, transaction, adding tickets, and redirecting to payment:

```javascript
let evb = new Eventbook();

async function evbTest() {
  const client = await evb.addClient({
    "first_name": "Ion",
    "last_name": "Popescu",
    "phone": "040-1110-444",
    "email": "mihai@eventbook.ro",
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

  await evb.addTickets({
    "performance_id": 75557,
    "number_of_tickets": 1,
    "transaction_id": transaction.id,
    "client_id": client.id
  });

  transaction = await evb.getTransaction(transaction.id);
  console.log(transaction);
  evb.redirectToPaymentGateway(transaction.id);
}

evbTest();
```
