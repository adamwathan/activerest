# ActiveREST

ActiveRecord wrapper for REST APIs. Still way pre-Alpha, avoid :)

## Basic usage

### Setup
Extend `ActiveRest\Model` and supply a base URL. Override any actions you need to if the API you are using is non-standard.

Now you can add any additional behavior you need to this model and treat it as if it was coming directly from your own database. 

Any properties returned from the server will be available as public properties on your object *(for now at least)*.

```php
use AdamWathan\ActiveRest\Model as ActiveRestModel;

class ApiPerson extends ActiveRestModel {
	// Specify the API end point
	protected $baseUrl = 'http://package.dev/api/people';

	// Override any standard RESTful behavior with custom end points
	protected $actions = [
		'destroy' => [ 'method' => 'GET', 'uri' => '{id}/delete'],
	];
}
```

### Retrieving a record

```php
// Get the model with ID of 1
$person = ApiPerson::find(1);
```

### Updating an existing record

```php
$person = ApiPerson::find(1);
$person->name = "John Doe";

// Save the model to the third party API
$person->save();
```

### Adding a new record

```php
$person = new ApiPerson;
$person->name = "John Doe";
$person->save();

// Can now retrieve the ID sent back by the server
$person->id;
```

### Deleting a record

```php
$person = ApiPerson::find(1);
$person->delete();
```

## To Do
- Moar tests
- Collection support
- Getter/setter magic method interceptors
- Support for query parameters and such
- A million other things