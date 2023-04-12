# GForms API PHP SDK Client Library #

## API Client Version

This is the first version of a community sponsored PHP SDK client for Gravity Forms API.

## API version support

This client **only** supports Gravity Forms's API v2.  Please see their [API documentation](https://docs.gravityforms.com/rest-api-v2/) for more information.

## Requirements
* PHP 8.1+

## Installation

The Gravity Forms API PHP SDK client can be installed using [Composer](https://packagist.org/packages/kylewlawrence/gravity-forms-api-client-php).

Are you using this with Laravel? If so, use the [Laravel wrapper](https://github.com/KyleWLawrence/gravity-forms-laravel).

### Composer

To install run `composer require kylewlawrence/gravity-forms-api-client-php`

## Configuration

Configuration is done through an instance of `GForms\Api\HttpClient`.
The block is mandatory and if not passed, an error will be thrown.

``` php
// load Composer
require 'vendor/autoload.php';

use KyleWLawrence\GForms\Api\HttpClient as GFormsAPI;

$username     = "6wiIBWbGkBMo1mRDMuVwkw1EPsNkeUj95PIz2akv"; // replace this with your Gravity Forms api username
$password     = "fdsJfds43dMo1mRDMuVwkw1EPsNkeUj9fdjk376l"; // replace this with your Gravity Forms api password

$client = new GFormsAPI();
$client->setAuth('basic', ['username' => $username, 'password' => $password]);
```

## Usage

### Basic Operations

``` php
// Get all forms
$forms = $client->forms()->getAll();
print_r($forms);

// Create a new form
$newForm = $client->forms()->create([
    'title' => 'Blah Blah',                          
    'fields' => [
        {
            'id' : '1',
            'label' : 'My Text',
            'type' : 'text'
        },
        {
            'id' : '2',
            'label' : 'More Text',
            'type' : 'text'
        }
    ],
]);
print_r($newForm);

// Update a form
$client->forms()->update(12345, [
    'title' => 'New Title',
    'fields' => [
        {
            'id' : '1',
            'label' : 'New Field Label',
            'type' : 'text'
        },
        {
            'id' : '2',
            'label' : 'New Field Label #2',
            'type' : 'text'
        }
    ],
]);

// Delete a form
$client->forms()->delete(12345);

// Get all forms
$forms = $client->forms()->getAll();
print_r($forms);
```

## Discovering Methods & Classes

``` php
// Get the base methods/classes available
$client->getValidSubResrouces()

// The above example will output something like:
[
    'entries' => "GForms\Api\Resources\Core\Entries",
    'forms' => "GForms\Api\Resources\Core\Forms",
]

// These are methods/classes that can be chained to the client. For instance:
// For instance, "forms" => "GForms\Api\Resources\Core\Forms", can be used as $client->forms()

// To find the chained methods available to the class, now do:
$client->forms()->getRoutes()

// The above example will output something like:
[
    'getAll' => 'forms',
    'get' => 'forms/{id}',
    'create' => 'forms',
    'update' => 'forms/{id}',
    'delete' => 'forms/{id}',
]

// Those routes can be compared with the GForms documentation routes and run as chained methods such as the below command to get all sites:
$client->forms()->getAll()
```

### Pagination

The GForms API offers a way to get the next pages for the requests and is documented in [the GForms Developer Documentation](https://docs.gravityforms.com/rest-api-v2/#h-use-paging).

The way to do this is to pass it as an option to your request.

``` php
$forms = $this->client->forms()->getAll(['paging[page_size]' => 20, 'paging[current_page]' => 1]);
```

The allowed options are
* page_size
* current_page
* offset

### Retrying Requests

Add the `RetryHandler` middleware on the `HandlerStack` of your `GuzzleHttp\Client` instance. By default `GForms\Api\HttpClient` 
retries: 
* timeout requests
* those that throw `Psr\Http\Message\RequestInterface\ConnectException:class`
* and those that throw `Psr\Http\Message\RequestInterface\RequestException:class` that are identified as ssl issue.

#### Available options

Options are passed on `RetryHandler` as an array of values.

* max = 2 _limit of retries_
* interval = 300 _base delay between retries in milliseconds_
* max_interval = 20000 _maximum delay value_
* backoff_factor = 1 _backoff factor_
* exceptions = [ConnectException::class] _Exceptions to retry without checking retry_if_
* retry_if = null _callable function that can decide whether to retry the request or not_

## Contributing

Pull Requests are always welcome. I'll catch-up and develop the contribution guidelines soon. For the meantime, just open and issue or create a pull request.

## Copyright and license

Copyright 2023-present KyleWLawrence

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
