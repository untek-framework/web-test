<?php

namespace Untek\Framework\WebTest\Libs;

use Symfony\Component\HttpFoundation\Request;
use Untek\Framework\WebTest\Helpers\TestHttpRequestHelper;

class TestHttpRequestFactoryClient
{
    public function createRequest(
        $uri,
        $method,
        array $headers = [],
        ?string $content = null,
        array $parameters = [],
        array $cookies = [],
        array $data = [],
    ): Request {
        $headers = TestHttpRequestHelper::prepareHeaderKeys($headers);
        $server = TestHttpRequestHelper::transformHeadersToServerVars($headers);
        $files = TestHttpRequestHelper::extractFilesFromDataArray($data);
        return Request::create(
            $uri,
            $method,
            $parameters,
            $cookies,
            $files,
            $server,
            $content
        );
    }
}
