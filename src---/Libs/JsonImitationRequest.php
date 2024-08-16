<?php

namespace Untek\Framework\WebTest\Libs;

use Symfony\Component\HttpFoundation\Response;

class JsonImitationRequest
{
    protected ImitationRequest $imitationRequest;

    public function __construct(string $endpointScript, ?string $baseUrl = 'http://localhost')
    {
        $this->imitationRequest = new ImitationRequest($endpointScript, $baseUrl);
    }

    public function sendJsonRequest(
        ?string $uri = null,
        string $method = 'GET',
        array $data = [],
        array $headers = []
    ): Response {
        $jsonContent = json_encode($data);
        $jsonHeaders = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        return $this->imitationRequest->sendRequest($uri, $method, $jsonHeaders + $headers, $jsonContent);
    }
}
