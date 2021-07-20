class Eventbook {
  get headers() {
    return {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    }
  };

  async getEvent(eventId) {
    return fetch('/?rest_route=/eventbook/event&eventId=' + eventId)
      .then(response => response.json());
  }

  async getPerformance(performanceId) {
    return fetch('/?rest_route=/eventbook/performance&performanceId=' + performanceId)
      .then(response => response.json());
  }

  async addClient(client) {
    return fetch('/?rest_route=/eventbook/client', {
      'method': 'POST',
      headers: this.headers,
      body: JSON.stringify(client)
    })
      .then(response => response.json());
  }

  async addTickets(ticketOrder)  {
    return fetch('/?rest_route=/eventbook/tickets', {
      'method': 'POST',
      headers: this.headers,
      body: JSON.stringify(ticketOrder)
    })
      .then(response => response.json());
  }

  async addTransaction() {
    return fetch('/?rest_route=/eventbook/transaction', {
      'method': 'POST',
      headers: this.headers
    })
      .then(response => response.json());
  }

  async getTransaction(transactionId) {
    return fetch('/?rest_route=/eventbook/transaction&transactionId=' + transactionId)
      .then(response => response.json());
  }

  redirectToPaymentGateway(transactionId) {
    window.location.href = 'https://eventbook.ro/card-payment-form/' + transactionId;
  }
}

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
    "number_of_tickets": 2,
    "transaction_id": transaction.id,
    "client_id": client.id
  });
  transaction = await evb.getTransaction(transaction.id);
  console.log(transaction);
  // evb.redirectToPaymentGateway(transaction.id);
}
