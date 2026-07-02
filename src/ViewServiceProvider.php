<?php declare(strict_types=1);

namespace Concept\Extensions\View;

use Concept\Core\Container\ContainerDependency;
use Concept\Core\Http\Contracts\RequestContextInterface;
use Concept\Extensions\Event\Events\ExtensionAwakened;
use Concept\Extensions\Event\Support\EventDispatcherResolver;
use Concept\Extensions\Http\Contracts\ResponseFactoryInterface;
use Concept\Extensions\View\Contracts\ViewInterface;
use Concept\Extensions\View\Contracts\ViewResponseFactoryInterface;
use Concept\Extensions\View\Factory\ViewResponseFactory;
use Concept\Extensions\View\Registry\ViewExtensionRegistry;
use Concept\Extensions\View\Registry\ViewPathRegistry;
use Concept\Extensions\View\Registry\ViewRegistry;
use Concept\Extensions\View\Registry\ViewRouteNamespaceRegistry;
use Concept\Extensions\View\Support\ViewRouteNamespaceResolver;
use League\Container\ServiceProvider\AbstractServiceProvider;

final class ViewServiceProvider extends AbstractServiceProvider
{
    private const string EXTENSION_NAME = 'view';

    /**
     * @param array<string, string> $paths namespace => absolute filesystem path
     * @param array<int, class-string> $extensions
     * @param array<string, string> $routeNamespace
     */
    public function __construct(
        private readonly array $paths = [],
        private readonly array $extensions = [],
        private readonly array $routeNamespace = [],
    ) {}

    public function provides(string $id): bool
    {
        return in_array($id, [
            ViewRegistry::class,
            ViewRouteNamespaceResolver::class,
            ViewResponseFactoryInterface::class,
        ], true);
    }

    public function register(): void
    {
        $container = $this->getContainer();

        $container->add(ViewRegistry::class, function() use ($container): ViewRegistry {
            EventDispatcherResolver::optional($container)?->dispatch(new ExtensionAwakened(
                extensionName: self::EXTENSION_NAME,
                anchorId: ViewRegistry::class,
            ));

            $viewPathRegistry = new ViewPathRegistry();
            $viewPathRegistry->append($this->paths);

            $viewExtensionRegistry = new ViewExtensionRegistry();
            $viewExtensionRegistry->append($this->extensions);

            $viewRouteNamespaceRegistry = new ViewRouteNamespaceRegistry();
            $viewRouteNamespaceRegistry->append($this->routeNamespace);

            return new ViewRegistry($viewPathRegistry, $viewExtensionRegistry, $viewRouteNamespaceRegistry);
        })->setShared(true);

        $container->add(ViewRouteNamespaceResolver::class, function() use ($container): ViewRouteNamespaceResolver {
            /** @var ViewRegistry $viewRegistry */
            $viewRegistry = ContainerDependency::get($container, ViewRegistry::class);

            return new ViewRouteNamespaceResolver($viewRegistry->routeNamespace());
        })->setShared(true);

        $container->add(ViewResponseFactoryInterface::class, function() use ($container): ViewResponseFactory {
            /** @var ResponseFactoryInterface $responseFactory */
            $responseFactory = ContainerDependency::get($container, ResponseFactoryInterface::class);
            /** @var ViewInterface $view */
            $view = ContainerDependency::get($container, ViewInterface::class);
            /** @var RequestContextInterface $requestContext */
            $requestContext = ContainerDependency::get($container, RequestContextInterface::class);

            return new ViewResponseFactory($responseFactory, $view, $requestContext);
        })->setShared(true);
    }
}
