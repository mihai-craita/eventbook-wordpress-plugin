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
}

let evb = new Eventbook();

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
  }).then(responseData => console.log(responseData));
