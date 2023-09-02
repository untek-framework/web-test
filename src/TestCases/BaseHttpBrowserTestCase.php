<?php

namespace Untek\Framework\WebTest\TestCases;

use PHPUnit\Framework\TestCase;
use Untek\Framework\WebTest\Libs\BrowserImitationRequest;

abstract class BaseHttpBrowserTestCase extends TestCase
{
    protected ?string $endpointScript = null;

    protected function getRequestImitator(): BrowserImitationRequest
    {
        return new BrowserImitationRequest($this->endpointScript);
    }
}
