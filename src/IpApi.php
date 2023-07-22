<?php

namespace ElFactory\IpApi;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class IpApi
{

    private string $key;

    private bool $withHeaders = false;

    private string $lang;

    private array $retry = [
        'times' => 1,
        'sleep' => 0,
    ];

    private array $fields = [
        'as', 'asname', 'city', 'continent', 'continentCode', 'country', 'countryCode', 'currency', 'district',
        'hosting', 'isp', 'lat', 'lon', 'message', 'mobile', 'offset', 'org', 'proxy', 'query', 'region', 'regionName',
        'reverse', 'status', 'timezone', 'zip',
    ];

    /**
     * IpApi constructor.
     *
     * @throws Exception If the given IP address is invalid or reserved.
     */
    protected function __construct(protected string $ip)
    {
        $this->validateIp();

        $this->key = config('ip-api.key');
        $this->lang = config('ip-api.lang');
    }

    /**
     * Create a new instance of the IpApi class with default settings.
     *
     * @throws Exception If the given IP address is invalid or reserved.
     */
    public static function default(string $ip): self
    {
        return new static($ip);
    }

    /**
     * Perform the IP address lookup.
     *
     * @throws RequestException If an error occurs while making the API request.
     */
    public function lookup(): array
    {
        $query = $this->prepareQuery();

        $response = Http::retry($this->retry['times'], $this->retry['sleep'],
            fn(Exception $e) => $e instanceof ConnectionException
        )->get(config('ip-api.url').$query)->throw();

        if ( ! $this->withHeaders) {
            return $response->json();
        }

        return array_merge($response->json(), [
            'requestRemaining' => $response->header('X-Rl'),
            'requestLimit'     => $response->header('X-Ttl'),
        ]);
    }

    /**
     * Set the fields to be included in the API response.
     */
    public function fields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Set the API key to be used for the API request.
     */
    public function usingKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Set the retry configuration for failed API requests.
     */
    public function retry(int $times, int $sleep): self
    {
        $this->retry = Arr::only(compact('times', 'sleep'), array_keys($this->retry));

        return $this;
    }

    /**
     * Set the language for the API response.
     */
    public function lang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Include request limit and remaining requests headers in the response array.
     */
    public function withHeaders(): self
    {
        $this->withHeaders = true;

        return $this;
    }

    /**
     * Prepare the query string for the API request.
     */
    private function prepareQuery(): string
    {
        $query = "{$this->ip}?fields=".implode(',', $this->fields)."&lang={$this->lang}";

        if ($this->key) {
            $query .= '&key='.$this->key;
        }

        return $query;
    }

    /**
     * Validate the given IP address to ensure it is valid and not reserved.
     *
     * @throws Exception If the IP address is invalid or reserved.
     */
    private function validateIp(): void
    {
        if ( ! filter_var($this->ip, FILTER_VALIDATE_IP)) {
            throw new Exception('Invalid IP address');
        }
    }
}
