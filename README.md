# eventbook-wordpress-plugin
Wordpress plugin for eventbook api

In your js you can call functions like so:

postClient(
  {'first_name': 'Mihai', 'last_name': 'Tester', 'phone':  '07222', 'email': 'mihai@mhra.com', 'observations': 'test'}
).then(data => console.log(data));
