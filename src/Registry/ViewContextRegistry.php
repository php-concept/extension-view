<?php declare(strict_types=1);

namespace Concept\Extensions\View\Registry;

final class ViewContextRegistry
{
    /** @var array<string, string> */
    private array $items = [];

    /**
     * @param array<string, string> $values
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
