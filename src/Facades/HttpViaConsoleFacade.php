<?php

namespace Untek\Framework\WebTest\Facades;

use Untek\Framework\WebTest\Encoders\IsolateEncoder;
use Untek\Framework\WebTest\Libs\ConsoleHttpKernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelBrowser;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class HttpViaConsoleFacade
{
    public function __construct(private string $endpointScript)
    {
    }

    public function createHttpKernel(): HttpKernelInterface
    {
        $encoder = new IsolateEncoder();
        return new ConsoleHttpKernel($encoder, $this->endpointScript);
    }

    public function handleRequestViaBrowser(Request $request): Response
    {
        $httpKernelBrowser = $this->createHttpKernelBrowser();
        $httpKernelBrowser->request(
            $request->getMethod(),
            $request->getUri(),
            $request->request->all(),
            $request->files->all(),
            $request->server->all(),
            $request->getContent()
        );
        return $httpKernelBrowser->getResponse();
    }

    private function createHttpKernelBrowser(): HttpKernelBrowser
    {
        $httpKernel = $this->createHttpKernel();
        $httpKernelBrowser = new HttpKernelBrowser($httpKernel);
        $httpKernelBrowser->followRedirects();
        return $httpKernelBrowser;
    }
}
