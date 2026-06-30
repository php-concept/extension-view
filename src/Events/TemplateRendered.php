<?php declare(strict_types=1);

namespace Concept\Extensions\View\Events;

final readonly class TemplateRendered
{
    public function __construct(
        public string $view,
        public float $startedAt,
        public float $duration,
    ) {}
}
