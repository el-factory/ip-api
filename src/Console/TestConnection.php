<?php

namespace ElFactory\IpApi\Console;

use ElFactory\IpApi\IpApi;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class TestConnection extends Command
{
    protected $signature = 'ip-api:connection 
                            {ip? : The IP address to test with}';

    protected $description = 'Test connection to ip-api.com';

    /**
     * @throws RequestException
     * @throws Exception
     */
    public function handle(): int
    {
        $ip = $this->argument('ip') ?? $this->publicIp();
        $response = IpApi::default($ip)->lookup();

        $this->warn('Testing connection to ip-api.com api with ip: '.$ip);
        $this->info(json_encode($response));

        return 0;
    }

    /**
     * Get the public IP address
     */
    private function publicIp(): string
    {
        $response = Http::get('https://httpbin.org/ip');
        $data = $response->json();

        return $data['origin'];
    }
}
