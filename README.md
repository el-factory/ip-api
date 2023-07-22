# IpApi - IP Address Information Lookup

The `IpApi` package provides an easy-to-use interface for looking up information about IP addresses using the ip-api.com API. It allows you to fetch various details about an IP address, such as geographical location, time zone, currency, and more.

Requirements:
- PHP 7.2 or higher
- Laravel 7 or higher

## Installation

The `IpApi` package requires Laravel version 7 or higher.

To install the package, you can use Composer:

```bash
composer require elfactory/ip-api
```

## Configuration

After installing the package, you can optionally publish the configuration file to customize the default settings:

```bash
php artisan vendor:publish --tag=ip-api-config
```

This will create a `ip-api.php` file in the `config` directory of your Laravel application. You can modify the default configuration values in this file according to your specific requirements.

## Usage

### IP Address Lookup

To perform an IP address lookup, you can use the `lookup` method on the `IpApi` class:

```php
use ElFactory\IpApi\IpApi;

$ipDetails = IpApi::default('188.216.103.93')->lookup();
```

This will return an array with the details of the provided IP address.

### Additional Configuration

You can also customize the IP address lookup by using the following methods:

#### `fields(array $fields): IpApi`

Set the fields to be included in the API response. The `$fields` parameter should be an array of fields you want to retrieve.

```php
$ipDetails = IpApi::default('188.216.103.93')->fields(['city', 'country', 'timezone'])->lookup();
```

#### `usingKey(string $apiKey): IpApi`

By default, the package uses the API key provided in config file. If you wish to send different API key for a specific request, you can use the `usingKey` method to set the API key for that request.

```php
$ipDetails = IpApi::default('188.216.103.93')->usingKey('YOUR_API_KEY')->lookup();
```

#### `retry(int $times, int $sleep): IpApi`

Set the retry configuration for failed API requests. The `$times` parameter represents the number of retry attempts, and the `$sleep` parameter specifies the number of seconds to sleep between retries.

```php
$ipDetails = IpApi::default('188.216.103.93')->retry(3, 2)->lookup();
```

#### `lang(string $language): IpApi`

Set the language for the API response. The `$language` parameter should be a two-letter language code.

```php
$ipDetails = IpApi::default('188.216.103.93')->lang('en')->lookup();
```

#### `withHeaders(): IpApi`

Include request limit and remaining requests headers in the API response.

```php
$ipDetails = IpApi::default('188.216.103.93')->withHeaders()->lookup();
```

### Console Command - `ip-api:connection`

The `IpApi` package also includes a console command named `ip-api:connection` that allows you to test the connection to the ip-api.com API with a specific IP address or your public IP address (if not provided).

To use the command, run the following Artisan command:

```bash
php artisan ip-api:connection {ip?}
```

- `{ip}` (optional): The IP address to test with. If not provided, the command will use your public IP address.

The command will display the details retrieved from the ip-api.com API for the provided IP address.

### Example

Here's an example of using the `ip-api:connection` command:

```bash
php artisan ip-api:connection 188.216.103.93
```

This will test the connection to ip-api.com API with the specified IP address and display the details retrieved.

If no IP address is provided, the command will automatically use your public IP address:

```bash
php artisan ip-api:connection
```

## Exceptions

The `IpApi` class may throw the following exceptions:

- `Exception`: If the provided IP address is invalid or reserved.
- `RequestException`: If an error occurs while making the API request.

## License

The `IpApi` package is open-source software licensed under the [MIT License](https://opensource.org/licenses/MIT). Feel free to use and modify it according to your needs.

---

Please note that this `README.md` file is just a basic example, and you may want to expand it further by adding more details, usage examples, and other relevant information specific to your package. It's essential to provide comprehensive documentation to help users understand how to use your package effectively.