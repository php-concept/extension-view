<?php declare(strict_types=1);

namespace Concept\Extensions\View\Registry;

final class ViewExtensionRegistry
{
    /** @var array<int, class-string> */
    private array $items = [];

    /**
     * @param array<int, class-string> $values
     */
    public function append(array $values): void
    {
        $this->items = array_merge($this->items, $values);
    }

    /**
     * @return array<int, class-string>
     */
    public function all(): array
    {
        return $this->items;
    }
}
