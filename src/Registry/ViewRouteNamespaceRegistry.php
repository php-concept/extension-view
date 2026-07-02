<?php declare(strict_types=1);

namespace Concept\Extensions\View\Registry;

final class ViewRouteNamespaceRegistry
{
    /** @var array<string, string> */
    private array $items = [];

    /**
     * @param array<string, string> $values route prefix => view namespace
     */
    public function append(array $values): void
    {
        $this->items = array_merge($this->items, $values);
    }

    /**
     * @return array<string, string>
     */
    public function all(): array
    {
        return $this->items;
    }
}
