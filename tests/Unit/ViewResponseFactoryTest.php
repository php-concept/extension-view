<?php declare(strict_types=1);

namespace Tests\Unit;

use Concept\Extensions\Http\Protocol\HttpHeader;
use Concept\Extensions\Http\Protocol\HttpStatusCode;
use Concept\Extensions\Http\Protocol\HttpValue;
use Concept\Extensions\View\Factory\ViewResponseFactory;
use Concept\Extensions\View\Requests\RequestAttribute;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use Tests\Support\StubRequestContext;
use Tests\Support\StubResponseFactory;
use Tests\Support\StubView;

final class ViewResponseFactoryTest extends TestCase
{
    public function testCreateRendersViewWithMergedSharedPayload(): void
    {
        $request = (new ServerRequest())
            ->withAttribute(RequestAttribute::VIEW_PAYLOAD, [
                'title' => 'Shared',
                0 => 'ignored',
                'count' => 2,
            ]);
        $factory = new ViewResponseFactory(
            new StubResponseFactory(),
            new StubView(),
            new StubRequestContext($request),
        );

        $response = $factory->create('home/index', ['count' => 5]);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertSame(HttpValue::HTML, $response->getHeaderLine(HttpHeader::CONTENT_TYPE));
        $this->assertSame(
            'home/index:{"title":"Shared","count":5}',
            (string) $response->getBody(),
        );
    }
}
