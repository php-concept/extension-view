<?php declare(strict_types=1);

namespace Concept\Extensions\View\View;

use Concept\Extensions\View\Registry\ViewContextRegistry;
use Psr\Http\Message\ServerRequestInterface;

final class ViewContextResolver
{
    public function __construct(private readonly ViewContextRegistry $viewContextRegistry) {}

    public function resolve(ServerRequestInterface $request): ?string
    {
        $path = '/' . ltrim($request->getUri()->getPath(), '/');
        /** @var array<string, string> $namespacesMap */
        $namespacesMap = $this->viewContextRegistry->all();
        uksort($namespacesMap, fn(string $a, string $b): int => strlen($b) <=> strlen($a));

        foreach ($namespacesMap as $prefix => $namespace) {
            if (str_starts_with($path, $prefix)) {
                return $namespace;
            }
        }

        return $namespacesMap !== [] ? (string) reset($namespacesMap) : null;
    }
}
