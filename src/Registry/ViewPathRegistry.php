<?php declare(strict_types=1);

namespace Concept\Extensions\View\Registry;

final class ViewPathRegistry
{
    /** @var array<string, string> namespace => absolute filesystem path */
    private array $items = [];

    /**
     * @param array<string, string> $values namespace => absolute filesystem path
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
