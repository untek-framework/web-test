<?php

namespace Untek\Framework\WebTest\Asserts;

use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Response;

class HttpResponseAssert extends Assert
{
    protected Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function assertContainsContent(string $needle)
    {
        $content = $this->response->getContent();
        $this->assertStringContainsString($needle, $content);
        return $this;
    }
}
