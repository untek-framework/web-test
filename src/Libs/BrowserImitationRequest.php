<?php

namespace Untek\Framework\WebTest\Libs;

use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\HttpKernelBrowser;
use Untek\Framework\WebTest\Facades\HttpViaConsoleFacade;

class BrowserImitationRequest
{
    protected AbstractBrowser $browser;

    protected string $baseUrl = 'http://localhost';

    public function __construct(string $endpointScript)
    {
        $this->browser = $this->createBrowser($endpointScript);
    }

    public function sendRequest(
        string $uri,
        string $method = 'GET',
        array $parameters = [],
        array $files = [],
        array $server = [],
        string $content = null,
        bool $changeHistory = true
    ): Crawler {
        $url = $this->prepareUrl($uri);
        return $this->browser->request(
            $method,
            $url,
            $parameters,
            $files,
            $server,
            $content,
            $changeHistory
        );
    }

    public function sendForm(string $uri, string $buttonValue, array $formValues): Crawler
    {
        $crawler = $this->sendRequest($uri);
        $form = $crawler->selectButton($buttonValue)->form();
        foreach ($formValues as $name => $value) {
            $form[$name] = $value;
        }
        $crawler = $this->browser->submit($form);
        return $crawler;
    }

    public function getCrawler(): Crawler
    {
        return $this->browser->getCrawler();
    }

    public function getBrowser(): AbstractBrowser
    {
        return $this->browser;
    }

    protected function prepareUrl(?string $uri = null): string
    {
        $baseUrl = rtrim($this->baseUrl, '/');
        if (empty($uri)) {
            return $baseUrl;
        }
        return $baseUrl . '/' . ltrim($uri, '/');
    }

    protected function createBrowser(string $endpointScript): AbstractBrowser
    {
        $facade = new HttpViaConsoleFacade($endpointScript);
        $httpKernel = $facade->createHttpKernel();
        $browser = new HttpKernelBrowser($httpKernel);
        $browser->followRedirects();
        return $browser;
    }
}
