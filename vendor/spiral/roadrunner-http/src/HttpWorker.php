<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Http;

use Spiral\RoadRunner\Payload;
use Spiral\RoadRunner\WorkerInterface;

/**
 * @psalm-import-type HeadersList from Request
 * @psalm-import-type AttributesList from Request
 * @psalm-import-type UploadedFilesList from Request
 * @psalm-import-type CookiesList from Request
 *
 * @psalm-type RequestContext = array {
 *      remoteAddr: string,
 *      protocol:   string,
 *      method:     string,
 *      uri:        string,
 *      attributes: AttributesList,
 *      headers:    HeadersList,
 *      cookies:    CookiesList,
 *      uploads:    UploadedFilesList|null,
 *      rawQuery:   string,
 *      parsed:     bool
 * }
 *
 * @see Request
 */
class HttpWorker implements HttpWorkerInterface
{
    /**
     * @var WorkerInterface
     */
    private WorkerInterface $worker;

    /**
     * @param WorkerInterface $worker
     */
    public function __construct(WorkerInterface $worker)
    {
        $this->worker = $worker;
    }

    /**
     * @return WorkerInterface
     */
    public function getWorker(): WorkerInterface
    {
        return $this->worker;
    }

    /**
     * {@inheritDoc}
     * @throws \JsonException
     */
    public function waitRequest(): ?Request
    {
        $payload = $this->worker->waitPayload();

        // Termination request
        if ($payload === null || (!$payload->body && !$payload->header)) {
            return null;
        }

        /** @var RequestContext $context */
        $context = \json_decode($payload->header, true, 512, \JSON_THROW_ON_ERROR);

        return $this->createRequest($payload->body, $context);
    }

    /**
     * {@inheritDoc}
     * @throws \JsonException
     */
    public function respond(int $status, string $body, array $headers = []): void
    {
        $headers = (string)\json_encode([
            'status'  => $status,
            'headers' => $headers ?: (object)[],
        ], \JSON_THROW_ON_ERROR);

        $this->worker->respond(new Payload($body, $headers));
    }

    /**
     * @param string $body
     * @param RequestContext $context
     * @return Request
     *
     * @psalm-suppress InaccessibleProperty
     */
    private function createRequest(string $body, array $context): Request
    {
        $request = new Request();
        $request->body = $body;

        $this->hydrateRequest($request, $context);

        return $request;
    }

    /**
     * @param Request $request
     * @param RequestContext $context
     *
     * @psalm-suppress InaccessibleProperty
     * @psalm-suppress MixedPropertyTypeCoercion
     */
    private function hydrateRequest(Request $request, array $context): void
    {
        $request->remoteAddr = $context['remoteAddr'];
        $request->protocol = $context['protocol'];
        $request->method = $context['method'];
        $request->uri = $context['uri'];
        \parse_str($context['rawQuery'], $request->query);

        $request->attributes = (array)($context['attributes'] ?? []);
        $request->headers = (array)($context['headers'] ?? []);
        $request->cookies = (array)($context['cookies'] ?? []);
        $request->uploads = (array)($context['uploads'] ?? []);
        $request->parsed = (bool)$context['parsed'];
    }
}
