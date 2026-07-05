# Traccar API Client for Laravel

A Laravel wrapper for the [`harrowmykel/traccar-api-php`](https://github.com/harrowmykel/traccar-api-php) package. It integrates the Traccar REST API client seamlessly into Laravel, supporting configuration profiles, a Laravel Facade, dependency injection, and automatic service provider binding.

## Features

- **Multi-Server Configuration**: Define multiple Traccar server profiles in your config and switch between them at runtime.
- **Laravel Facade**: Access the Traccar API with clean syntax via `TraccarLaravel::`.
- **Dependency Injection**: Inject `TraccarLaravelClient` directly into your controllers or jobs.
- **Fluent Traccar PHP Client Integration**: Under the hood, retrieves the underlying `PiccmaQ\TraccarApi\TraccarApi` client instance.

## Requirements

- PHP 8.3+
- Laravel 11.0+ / 12.0+ / 13.0+
- `harrowmykel/traccar-api-php`

## Installation

Add the package via Composer:

```bash
composer require harrowmykel/traccar-api-laravel
```

*Note: Since the package is currently in development/VCS, make sure your project's `composer.json` has the appropriate repository settings if installing from a custom VCS source.*

Publish the configuration file:

```bash
php artisan vendor:publish --provider="PiccmaQ\TraccarApiLaravel\TraccarProvider" --tag="config"
```

This will create a `config/traccar.php` file in your application.

## Configuration

In `config/traccar.php`, configure your default connection and the details of your Traccar servers.

```php
return [

    'default' => env('TRACCAR_DEFAULT_ACCOUNT', 'default'),

    'servers' => [
        'default' => [
            'url' => env('TRACCAR_URL', 'https://demo.traccar.org'),
            'auth_type' => env('TRACCAR_AUTH_TYPE', 'email'), // 'none', 'email', or 'token'
            
            // Required if auth_type is 'email'
            'username' => env('TRACCAR_USERNAME', 'admin'),
            'password' => env('TRACCAR_PASSWORD', 'admin'),

            // Required if auth_type is 'token'
            'auth_token' => env('TRACCAR_AUTH_TOKEN', null),
        ],
        // You can define other connections here...
    ],
];
```

Make sure to add the corresponding environment variables to your `.env` file:

```env
TRACCAR_DEFAULT_ACCOUNT=default
TRACCAR_URL=https://demo.traccar.org
TRACCAR_AUTH_TYPE=email
TRACCAR_USERNAME=your_email@example.com
TRACCAR_PASSWORD=your_password
```

## Usage

### Using the Facade

The `TraccarLaravel` Facade forwards calls directly to the default `TraccarLaravelClient`, which wraps the `TraccarApi` client. To interact with the underlying API client, call the `getClient()` method:

```php
use PiccmaQ\TraccarApiLaravel\Facades\TraccarLaravel;

// Get a list of all devices
$response = TraccarLaravel::getClient()->devices()->list();
$devices = $response->getStructuredBody(); // Array of DeviceModel

foreach ($devices as $device) {
    echo $device->name . ' - ' . $device->status . PHP_EOL;
}
```

### Dependency Injection

Alternatively, you can type-hint `PiccmaQ\TraccarApiLaravel\TraccarLaravelClient` in your class constructor or method parameters:

```php
use PiccmaQ\TraccarApiLaravel\TraccarLaravelClient;

class TrackerController extends Controller
{
    public function index(TraccarLaravelClient $traccar)
    {
        $devices = $traccar->getClient()->devices()->list()->getStructuredBody();
        
        return view('devices.index', compact('devices'));
    }
}
```

### Multi-Server Usage

You can connect to a non-default server dynamically by instantiating `TraccarLaravelClient` with a different configuration profile name:

```php
use PiccmaQ\TraccarApiLaravel\TraccarLaravelClient;

// Connect using the 'staging' server profile defined in config/traccar.php
$stagingClient = new TraccarLaravelClient('staging');
$devices = $stagingClient->getClient()->devices()->list()->getStructuredBody();
```

## License

MIT
