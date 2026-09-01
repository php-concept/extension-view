<?php declare(strict_types=1);

namespace Tests\Support;

use Concept\Core\Http\Contracts\RequestContextInterface;
use Psr\Http\Message\ServerRequestInterface;

final class StubRequestContext implements RequestContextInterface
{
    public function __construct(private ?ServerRequestInterface $request = null) {}

    public function set(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function current(): ?ServerRequestInterface
    {
        return $this->request;
    }

    public function reset(): void
    {
        $this->request = null;
    }
}
