<?php declare(strict_types=1);

namespace Tests\Support;

use Concept\Extensions\Http\Contracts\ResponseFactoryInterface;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;

final class StubResponseFactory implements ResponseFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return (new Response())->withStatus($code, $reasonPhrase);
    }

    public function json(mixed $data, int $code = 200, int $jsonFlags = 0): ResponseInterface
    {
        return $this->createResponse($code);
    }

    public function jsonSuccess(mixed $data = [], int $code = 200, int $jsonFlags = 0): ResponseInterface
    {
        return $this->createResponse($code);
    }

    public function jsonError(string $message, int $code = 500, array $errors = [], int $jsonFlags = 0): ResponseInterface
    {
        return $this->createResponse($code);
    }

    public function redirect(string $url, int $status = 302): ResponseInterface
    {
        return $this->createResponse($status);
    }

    public function redirectByName(string $urlName, array $parameters = [], int $status = 302): ResponseInterface
    {
        return $this->createResponse($status);
    }

    public function redirectBack(int $status = 302, string $fallback = '/', ?\Psr\Http\Message\ServerRequestInterface $request = null): ResponseInterface
    {
        return $this->createResponse($status);
    }
}
