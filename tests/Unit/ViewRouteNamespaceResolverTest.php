<?php declare(strict_types=1);

namespace Tests\Unit;

use Concept\Extensions\View\Registry\ViewRouteNamespaceRegistry;
use Concept\Extensions\View\Support\ViewRouteNamespaceResolver;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use PHPUnit\Framework\TestCase;

final class ViewRouteNamespaceResolverTest extends TestCase
{
    public function testResolveUsesLongestMatchingPrefix(): void
    {
        $registry = new ViewRouteNamespaceRegistry();
        $registry->append([
            '/admin' => 'admin',
            '/admin/settings' => 'admin-settings',
        ]);
        $resolver = new ViewRouteNamespaceResolver($registry);
        $request = (new ServerRequest())->withUri(new Uri('https://example.com/admin/settings/profile'));

        $this->assertSame('admin-settings', $resolver->resolve($request));
    }

    public function testResolveFallsBackToFirstNamespaceWhenNoPrefixMatches(): void
    {
        $registry = new ViewRouteNamespaceRegistry();
        $registry->append(['/admin' => 'admin', '/api' => 'api']);
        $resolver = new ViewRouteNamespaceResolver($registry);
        $request = (new ServerRequest())->withUri(new Uri('https://example.com/public/page'));

        $this->assertSame('admin', $resolver->resolve($request));
    }

    public function testResolveReturnsNullForEmptyMap(): void
    {
        $resolver = new ViewRouteNamespaceResolver(new ViewRouteNamespaceRegistry());
        $request = (new ServerRequest())->withUri(new Uri('https://example.com/'));

        $this->assertNull($resolver->resolve($request));
    }
}
