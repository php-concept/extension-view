<?php declare(strict_types=1);

namespace Concept\Extensions\View;

use Concept\Core\Http\Contracts\RequestContextInterface;
use Concept\Extensions\Event\Events\ExtensionAwakened;
use Concept\Extensions\Event\Support\EventDispatcherResolver;
use Concept\Extensions\Http\Contracts\ResponseFactoryInterface;
use Concept\Extensions\View\Contracts\ViewInterface;
use Concept\Extensions\View\Contracts\ViewResponseFactoryInterface;
use Concept\Extensions\View\Registry\ViewContextRegistry;
use Concept\Extensions\View\Registry\ViewExtensionRegistry;
use Concept\Extensions\View\Registry\ViewPathRegistry;
use Concept\Extensions\View\Registry\ViewRegistry;
use Concept\Extensions\View\View\ViewContextResolver;
use Concept\Extensions\View\View\ViewResponseFactory;
use League\Container\ServiceProvider\AbstractServiceProvider;

final class ViewServiceProvider extends AbstractServiceProvider
{
    private const string EXTENSION_NAME = 'view';

    /**
     * @param array<string, string> $paths
     * @param array<string, string> $contexts
     * @param array<int, class-string> $extensions
     */
    public function __construct(
        private readonly array $paths = [],
        private readonly array $contexts = [],
        private readonly array $extensions = [],
    ) {}

    public function provides(string $id): bool
    {
        return in_array($id, [
            ViewRegistry::class,
            ViewContextResolver::class,
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

            $viewContextRegistry = new ViewContextRegistry();
            $viewContextRegistry->append($this->contexts);

            return new ViewRegistry($viewPathRegistry, $viewExtensionRegistry, $viewContextRegistry);
        })->setShared(true);

        $container->add(ViewContextResolver::class, function() use ($container): ViewContextResolver {
            /** @var ViewRegistry $viewRegistry */
            $viewRegistry = $container->get(ViewRegistry::class);

            return new ViewContextResolver($viewRegistry->contexts());
        })->setShared(true);

        $container->add(ViewResponseFactoryInterface::class, function() use ($container): ViewResponseFactory {
            /** @var ResponseFactoryInterface $responseFactory */
            $responseFactory = $container->get(ResponseFactoryInterface::class);
            /** @var ViewInterface $view */
            $view = $container->get(ViewInterface::class);
            /** @var RequestContextInterface $requestContext */
            $requestContext = $container->get(RequestContextInterface::class);

            return new ViewResponseFactory($responseFactory, $view, $requestContext);
        })->setShared(true);
    }
}
