<?php

namespace Untek\Framework\WebTest\Asserts;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use PHPUnit\Framework\Assert;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;

class HttpBrowserAssert extends Assert
{
    use ArraySubsetAsserts;

    protected Crawler $crawler;
    protected AbstractBrowser $browser;

    public function __construct(AbstractBrowser $browser)
    {
        $this->browser = $browser;
        $this->crawler = $browser->getCrawler();
    }

    public function assertCookie(string $name, string $value)
    {
        $cookie = $this->browser->getCookieJar()->get($name);
        $this->assertNotNull($cookie);
        $this->assertEquals($value, $cookie->getValue());
    }

    public function assertContainsContent(string $content)
    {
        $html = html_entity_decode($this->crawler->html());
        $this->assertStringContainsString($content, $html);
        return $this;
    }

    public function assertEqualsContent(string $content)
    {
        $this->assertEquals($content, $this->crawler->text());
        return $this;
    }

    public function assertFormValues(string $buttonValue, array $values)
    {
        $form = $this->crawler->selectButton($buttonValue)->form();
        $this->assertArraySubset($values, $form->getValues());
        return $this;
    }

    public function assertUnauthorized()
    {
        $html = html_entity_decode($this->crawler->html());
        $this->assertStringContainsString('Логин', $html);
        return $this;
    }

    public function assertIsFormError()
    {
        $html = html_entity_decode($this->crawler->html());
        $this->assertStringContainsString('Has errors!', $html);
        return $this;
    }

    public function assertIsNotFormError()
    {
        $html = html_entity_decode($this->crawler->html());
        $this->assertNotContains('Has errors!', $html);
        return $this;
    }

    /*public function assertFormError($message)
    {
        //$this->assertIsFormError();
        $this->assertContainsText($message, $this->crawler->html());
        return $this;
    }*/
}
