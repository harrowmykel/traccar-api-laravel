# traccar-api

A PHP client library for the [Traccar](https://www.traccar.org/) GPS tracking platform REST API. Provides a clean, fluent interface for interacting with all Traccar API endpoints, with support for typed response models, multiple authentication methods, and a PSR-compatible HTTP client layer.

## Requirements

- PHP 8.1+
- [Guzzle HTTP](https://github.com/guzzle/guzzle) `^7.13` (installed automatically via Composer)

## Installation

```bash
composer require piccmaq/traccar-api
```

## Authentication

Three authentication modes are available:

### Bearer Token

```php
use PiccmaQ\TraccarApi\TraccarApi;

$api = TraccarApi::withAuthorizationToken('your-api-token');
```

### Email & Password

```php
$api = TraccarApi::withEmailPassword('admin@example.com', 'secret');
```

Authenticate and store the session token for subsequent requests:

```php
$response = $api->authenticate();
$token = $api->getAuthToken(); // bearer token returned by the server
```

### No Authentication

```php
$api = TraccarApi::noAuth();
```

## Configuration

You can also instantiate the client directly via the constructor for full control:

```php
$api = new TraccarApi([
    'baseUrl'    => 'https://your-traccar-server.example.com',
    'authMethod' => 'token',
    'authToken'  => 'your-api-token',
    'headers'    => ['X-Custom-Header' => 'value'],
]);
```

Change the base URL at any time via the fluent setter:

```php
$api->setBaseUrl('https://my-other-server.example.com/api');
```

You can also inject a custom `GuzzleHttp\ClientInterface` implementation, useful for testing or advanced HTTP configuration:

```php
$api->setHttpClient($myCustomGuzzleClient);
```

## Usage

Every resource is accessed via a dedicated method on the `TraccarApi` instance. Each method returns a request builder that sends the HTTP request and returns a `Response` object.

### Devices

```php
// List all devices
$response = $api->devices()->list();
$devices  = $response->getStructuredBody(); // array of DeviceModel

// Get a single device
$response = $api->devices()->get(42);
$device   = $response->getStructuredBody(); // DeviceModel

// Create a device
$response = $api->devices()->create([
    'name'     => 'Truck 01',
    'uniqueId' => 'IMEI123456',
]);

// Update a device
$api->devices()->update(42, ['name' => 'Truck 01 (updated)']);

// Delete a device
$api->devices()->delete(42);

// Update accumulators
$api->devices()->updateAccumulators(42, ['hours' => 1000]);

// Upload a device image
$api->devices()->uploadImage(42, '/path/to/image.jpg');
```

### Positions

```php
$response  = $api->positions()->list(['deviceId' => 42]);
$positions = $response->getStructuredBody(); // array of PositionModel
```

### Sessions

```php
// Create a session (cookie-based login)
$api->session()->create('admin@example.com', 'secret');

// Fetch current session user info
$response = $api->session()->info();
$user     = $response->getStructuredBody(); // UserModel

// Generate a token (optionally with expiration date)
$api->session()->generateToken('2026-12-31T23:59:59Z');

// Revoke a token
$api->session()->revokeToken('some-token');

// End the session
$api->session()->close();
```

### Reports

```php
// Route report
$response = $api->reports()->route([
    'deviceId' => [42],
    'from'     => '2026-01-01T00:00:00Z',
    'to'       => '2026-01-31T23:59:59Z',
]);

// Trip summary
$response = $api->reports()->summary([...]);

// Trips
$response = $api->reports()->trips([...]);

// Stops
$response = $api->reports()->stops([...]);

// Events
$response = $api->reports()->events([...]);

// Downloadable reports (pdf, xlsx, etc.)
$api->reports()->routeDownload('xlsx', [...]);
```

### Other Resources

| Method | Description |
|---|---|
| `$api->server()` | Server info and settings |
| `$api->users()` | User management |
| `$api->groups()` | Device groups |
| `$api->geofences()` | Geofence management |
| `$api->commands()` | Send commands to devices |
| `$api->notifications()` | Notification configuration |
| `$api->drivers()` | Driver management |
| `$api->attributes()` | Computed attributes |
| `$api->maintenance()` | Maintenance records |
| `$api->calendars()` | Calendar management |
| `$api->permissions()` | User/device permission linking |
| `$api->events()` | Event queries |
| `$api->statistics()` | Server statistics |
| `$api->share()` | Share tokens |
| `$api->orders()` | Order management |
| `$api->audit()` | Audit log |
| `$api->health()` | Server health check |
| `$api->password()` | Password reset |

## Working with Responses

All request methods return a `PiccmaQ\TraccarApi\Responses\Response` object:

```php
$response = $api->devices()->list();

$response->isSuccessful();      // true if HTTP 2xx
$response->getStatusCode();     // e.g. 200
$response->toArray();           // raw decoded JSON as array
$response->getBody();           // raw response body string
$response->getHeaders();        // array of response headers

// Typed model (populated when a model class is pre-configured by the request builder)
$response->hasStructuredBody();  // true if a typed model was hydrated
$response->getStructuredBody();  // DeviceModel | DeviceModel[] | null
```

### Response Models

Resource requests automatically hydrate responses into typed model objects. For example, `$api->devices()->list()` returns an array of `DeviceModel` instances with properties like:

```php
$device = $response->getStructuredBody()[0];

echo $device->id;       // int
echo $device->name;     // string
echo $device->status;   // string
echo $device->uniqueId; // string
```

You can also request a custom model hydration on any response using `withModel()`:

```php
use PiccmaQ\TraccarApi\Models\UserModel;

$response = $api->session()->info()->withModel(UserModel::class);
```

## Running Tests

```bash
# Linux / macOS
composer tests

# Windows
composer tests_windows
```

## License

MIT
