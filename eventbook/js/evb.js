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

evb.getEvent(16754).then(data => console.log(data));
evb.addClient({
  'first_name': 'Mihai', 'last_name': 'Tester', 'phone':  '07222', 'email': 'mihai@eventbook.ro', 'observations': 'test'
}).then(data => console.log(data));

evb.addTickets({
  'performance_id': 76884,
  'number_of_tickets': 1
});
