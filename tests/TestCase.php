<?php

namespace ElFactory\IpApi\Tests;

use ElFactory\IpApi\IpApiServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{

    protected function getPackageProviders($app): array
    {
        return [
            IpApiServiceProvider::class
        ];
    }
}
