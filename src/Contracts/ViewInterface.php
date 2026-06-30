<?php declare(strict_types=1);

namespace Concept\Extensions\View\Contracts;

interface ViewInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function render(string $viewName, array $data = []): string;
}
