<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Tests\Integration\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

final class ApiContext implements Context
{
    private Response $response;

    public function __construct(
        private KernelInterface $kernel
    ) {
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @When I send a :method request to :path
     */
    public function iSendMethodRequest(string $method, string $path): Response
    {
        return $this->response = $this->kernel->handle($this->request($path, $method));
    }

    /**
     * @When I send a :method request to :path with body:
     */
    public function iSendMethodRequestWithBody(string $method, string $path, PyStringNode $content): Response
    {
        return $this->response = $this->kernel->handle($this->request($path, $method, $content->getRaw()));
    }

    /**
     * @Then the response code is :code
     */
    public function theResponseCodeIs(int $code): void
    {
        $this->assertResponseCode($code);
    }

    /**
     * @Then the response content is:
     */
    public function theResponseContentIs(PyStringNode $content): void
    {
        $this->assertResponseContent($content->getRaw());
    }

    private function request(
        string $path,
        string $method,
        ?string $body = null
    ): Request {
        $headers = [
            'CONTENT_TYPE' => 'application/json',
        ];

        return Request::create(
            $path,
            $method,
            [],
            [],
            [],
            $headers,
            $body
        );
    }

    private function assertResponseCode(int $code): void
    {
        if ($this->getResponse()->getStatusCode() !== $code) {
            throw new RuntimeException(
                sprintf('Response code is %d, %d expected', $this->response->getStatusCode(), $code)
            );
        }
    }

    private function assertResponseContent(string $expectedContent): void
    {
        $expectedContent = json_decode($expectedContent, true);
        $content = json_decode($this->getResponse()->getContent(), true);

        if ($content !== $expectedContent) {
            throw new RuntimeException('Response content is not as expected.');
        }
    }
}
