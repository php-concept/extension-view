<?php declare(strict_types=1);

namespace Tests\Support;

use Concept\Extensions\View\Contracts\ViewInterface;

final class StubView implements ViewInterface
{
    public function render(string $viewName, array $data = []): string
    {
        return $viewName . ':' . json_encode($data, JSON_THROW_ON_ERROR);
    }

    public function renderString(string $template, array $data = []): string
    {
        return $template;
    }
}
