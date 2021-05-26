async function getEventInfo(eventId) {
  return fetch('/?rest_route=/eventbook/event&eventId=' + eventId)
    .then(response => response.json());
}

async function postClient(client) {
  return fetch('/?rest_route=/eventbook/client', {
    'method': 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(client)
  })
    .then(response => response.json());
}

getEventInfo(123).then(data => console.log(data));
postClient(
  {'first_name': 'Mihai', 'last_name': 'Tester', 'phone':  '07222', 'email': 'mihai@eventbook.ro', 'observations': 'test'}
).then(data => console.log(data));
