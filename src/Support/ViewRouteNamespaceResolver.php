<?php declare(strict_types=1);

namespace Concept\Extensions\View\Support;

use Concept\Extensions\View\Registry\ViewRouteNamespaceRegistry;
use Psr\Http\Message\ServerRequestInterface;

final class ViewRouteNamespaceResolver
{
    public function __construct(private readonly ViewRouteNamespaceRegistry $routeNamespaceRegistry) {}

    public function resolve(ServerRequestInterface $request): ?string
    {
        $path = '/' . ltrim($request->getUri()->getPath(), '/');
        $map = $this->routeNamespaceRegistry->all();
        uksort($map, fn(string $a, string $b): int => strlen($b) <=> strlen($a));

        foreach ($map as $prefix => $namespace) {
            if (str_starts_with($path, $prefix)) {
                return $namespace;
            }
        }

        return $map !== [] ? (string) reset($map) : null;
    }
}
