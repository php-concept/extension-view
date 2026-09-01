<?php declare(strict_types=1);

namespace Tests\Unit;

use Concept\Extensions\View\Registry\ViewPathRegistry;
use Concept\Extensions\View\Registry\ViewRouteNamespaceRegistry;
use PHPUnit\Framework\TestCase;

final class ViewRegistryTest extends TestCase
{
    public function testViewPathRegistryAppendsAndReturnsAll(): void
    {
        $registry = new ViewPathRegistry();
        $registry->append(['app' => '/views/app']);
        $registry->append(['admin' => '/views/admin']);

        $this->assertSame([
            'app' => '/views/app',
            'admin' => '/views/admin',
        ], $registry->all());
    }

    public function testViewRouteNamespaceRegistryAppendsAndReturnsAll(): void
    {
        $registry = new ViewRouteNamespaceRegistry();
        $registry->append(['/admin' => 'admin']);

        $this->assertSame(['/admin' => 'admin'], $registry->all());
    }
}
