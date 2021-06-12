# eventbook-wordpress-plugin
Wordpress plugin for eventbook api

In your js you can call a set of functions like so:

Get event details
```
evb.getEvent(16500);
```

Get performance info
```
evb.getPerformance(75557);
```

Add a new client
```
evb.addClient(
  {
    "first_name": "Ion",
    "last_name": "Popescu",
    "phone":  "040-1110-444",
    "email": "mihai@eventbook.ro",
    "observations": "this is a test client",
    "extra_data": {
      "newsletter": 1,
      "terms_and_conditions": 1
    }
  });
```

Add a new transaction
```
evb.addTransaction();
```

Add tickets for a specific performance
```
evb.addTickets({
  "performance_id": performance.id,
  "number_of_tickets": 1,
  "transaction_id": transaction.id,
  "client_id": client.id
});
```

Show transaction details
```
evb.getTransaction(transaction.id);
```

Redirect to payment gateway where the transaction will be payed
```
evb.redirectToPaymentGateway(transaction.id);
```

Below is a full example:
```
let evb = new Eventbook();

async function evbTest() {
  const client = await evb.addClient({
      "first_name": "Ion",
      "last_name": "Popescu",
      "phone":  "040-1110-444",
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
