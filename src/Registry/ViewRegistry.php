<?php declare(strict_types=1);

namespace Concept\Extensions\View\Registry;

final class ViewRegistry
{
    public function __construct(
        private readonly ViewPathRegistry $viewPathRegistry,
        private readonly ViewExtensionRegistry $viewExtensionRegistry,
        private readonly ViewContextRegistry $viewContextRegistry,
    ) {}

    public function paths(): ViewPathRegistry
    {
        return $this->viewPathRegistry;
    }

    public function extensions(): ViewExtensionRegistry
    {
        return $this->viewExtensionRegistry;
    }

    public function contexts(): ViewContextRegistry
    {
        return $this->viewContextRegistry;
    }
}
