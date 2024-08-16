<?php

namespace Untek\Framework\WebTest\Libs;

use Symfony\Component\HttpFoundation\Response;
use Untek\Framework\WebTest\Facades\HttpViaConsoleFacade;

class ImitationRequest
{
    protected ?string $baseUrl;

    protected HttpViaConsoleFacade $httpViaConsoleFacade;

    public function __construct(string $endpointScript, ?string $baseUrl = 'http://localhost')
    {
        $this->baseUrl = $baseUrl;
        $this->httpViaConsoleFacade = new HttpViaConsoleFacade($endpointScript);
    }

    public function sendRequest(
        ?string $uri = null,
        string $method = 'GET',
        array $headers = [],
        string $content = null
    ): Response {
        $uri = $this->prepareUrl($uri);
        $request = $this->createHttpClient()->createRequest($uri, $method, $headers, $content);
        return $this->httpViaConsoleFacade->handleRequestViaBrowser($request);
    }

    protected function createHttpClient(): TestHttpRequestFactoryClient
    {
        return new TestHttpRequestFactoryClient();
    }

    protected function prepareUrl(?string $uri = null): string
    {
        $baseUrl = rtrim($this->baseUrl, '/');
        if (empty($uri)) {
            return $baseUrl;
        }
        return $baseUrl . '/' . ltrim($uri, '/');
    }
}
