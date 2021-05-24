async function getEventInfo(eventId) {
  return fetch('/?rest_route=/eventbook/event&eventId=' + eventId)
    .then(response => response.json());
}

getEventInfo(123).then(data => console.log(data));
