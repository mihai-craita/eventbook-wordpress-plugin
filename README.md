# eventbook-wordpress-plugin
Wordpress plugin for eventbook api

In your js you can call functions like so:

```
postClient(
  {
    'first_name': 'Ion',
    'last_name': 'Popescu',
    'phone':  '040-1110-444',
    'email': 'mihai@eventbook.ro',
    'observations': 'this is a test client'
  }).then(responseData => console.log(responseData));
