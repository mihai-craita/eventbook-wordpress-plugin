# eventbook-wordpress-plugin
Wordpress plugin for eventbook api

In your js you can call functions like so:

```
/*
Get event details
param: eventId int
*/
evb.getEvent(16500);

/*
Add a new client
param: client object
*/
evb.addClient(
  {
    "first_name": "Ion",
    "last_name": "Popescu",
    "phone":  "040-1110-444",
    "email": "mihai@eventbook.ro",
    "observations": "this is a test client",
    "extra": {
      "newsletter": 1,
      "terms_and_conditions": 1
    }
  }).then(responseData => console.log(responseData));

/*
Add tickets for performance
*/
evb.addTickets({
  "performance_id": 76884,
  "number_of_tickets": 1
});
