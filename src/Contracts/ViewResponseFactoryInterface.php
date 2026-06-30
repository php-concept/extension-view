<?php declare(strict_types=1);

namespace Concept\Extensions\View\Contracts;

use Concept\Extensions\Http\Protocol\HttpStatusCode;
use Psr\Http\Message\ResponseInterface;

interface ViewResponseFactoryInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(
        string $template,
        array $data = [],
        int $code = HttpStatusCode::OK,
    ): ResponseInterface;
}
