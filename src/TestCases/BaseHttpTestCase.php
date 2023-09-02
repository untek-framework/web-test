<?php

namespace Untek\Framework\WebTest\TestCases;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Untek\Framework\WebTest\Libs\ImitationRequest;

abstract class BaseHttpTestCase extends TestCase
{
    protected ?string $endpointScript = null;

    protected string $baseUrl = 'http://localhost';

    protected function getRequestImitator(): ImitationRequest
    {
        return new ImitationRequest($this->endpointScript, $this->baseUrl);
    }

    protected function sendRequest(?string $uri = null, string $method = 'GET'): Response
    {
        return $this->getRequestImitator()->sendRequest($uri, $method);
    }
}
