# Traccar API Laravel Plugin Guidance

This document is designed to help AI/LLM agents quickly understand the design and architecture of the Laravel wrapper for the Traccar API PHP client.

## Package Architecture

The package acts as a bridge between a Laravel application and the `harrowmykel/traccar-api-php` client. It provides:

1. **`PiccmaQ\TraccarApiLaravel\TraccarProvider`**:
   - The service provider registering `TraccarLaravelClient` as a singleton in the Laravel container under the alias `traccar_laravel`.
   - Merges package-level config (`traccar.php`) into the Laravel config repository.
   - Publishes config files.

2. **`PiccmaQ\TraccarApiLaravel\TraccarLaravelClient`**:
   - Manages connection instantiation.
   - Accepts a server configuration profile name (e.g. `'default'`).
   - Retrieves URL and auth details from `config('traccar.servers.<profile>')`.
   - Prepares and holds a configured instance of `PiccmaQ\TraccarApi\TraccarApi`.
   - Exposes the underlying client via `getClient()`.

3. **`PiccmaQ\TraccarApiLaravel\Facades\TraccarLaravel`**:
   - Facade forwarding static calls to `traccar_laravel` (resolving to `TraccarLaravelClient`).

4. **`config/traccar.php`**:
   - Configuration file storing multiple server connection options and specifying the default active server connection.

## Common Operations

### Instantiating underlying API wrapper
To interact with Traccar API endpoints, always retrieve the raw client using the `getClient()` method:

```php
// Facade syntax
$api = \PiccmaQ\TraccarApiLaravel\Facades\TraccarLaravel::getClient();

// Dependency Injection syntax
$client = app(\PiccmaQ\TraccarApiLaravel\TraccarLaravelClient::class);
$api = $client->getClient();
```

### Supported API Resources (from `TraccarApi`)
Call these on the client to initiate operations (refer to the `traccar-api-php` documentation for detailed parameters):
- **Devices**: `$api->devices()->list()`, `$api->devices()->get($id)`, `$api->devices()->create($data)`, `$api->devices()->update($id, $data)`, `$api->devices()->delete($id)`
- **Positions**: `$api->positions()->list($query)`
- **Sessions**: `$api->session()->create($email, $password)`, `$api->session()->info()`, `$api->session()->close()`
- **Reports**: `$api->reports()->route($query)`, `$api->reports()->summary($query)`, `$api->reports()->trips($query)`, `$api->reports()->stops($query)`
- **Other resources**: `server()`, `users()`, `groups()`, `geofences()`, `commands()`, `notifications()`, `drivers()`, `attributes()`, `maintenance()`, `calendars()`, `permissions()`, `events()`, `statistics()`, `share()`, `orders()`, `audit()`, `health()`, `password()`

## Handling Responses

Methods return `PiccmaQ\TraccarApi\Responses\Response`. Useful methods on response objects:
- `isSuccessful()`: Boolean indicating success.
- `getStatusCode()`: HTTP status code.
- `getStructuredBody()`: Hydrates response JSON into typed models (e.g. `DeviceModel`, `PositionModel`, `UserModel`).
- `toArray()`: Returns raw associative array representation of payload.
