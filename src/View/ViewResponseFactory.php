<?php declare(strict_types=1);

namespace Concept\Extensions\View\View;

use Concept\Core\Http\Contracts\RequestContextInterface;
use Concept\Extensions\Http\Contracts\ResponseFactoryInterface;
use Concept\Extensions\Http\Protocol\HttpHeader;
use Concept\Extensions\Http\Protocol\HttpStatusCode;
use Concept\Extensions\Http\Protocol\HttpValue;
use Concept\Extensions\Http\Requests\RequestAttribute;
use Concept\Extensions\View\Contracts\ViewInterface;
use Concept\Extensions\View\Contracts\ViewResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

final class ViewResponseFactory implements ViewResponseFactoryInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly ViewInterface $view,
        private readonly RequestContextInterface $requestContext,
    ) {}

    public function create(
        string $template,
        array $data = [],
        int $code = HttpStatusCode::OK,
    ): ResponseInterface {
        $request = $this->requestContext->current();
        if ($request !== null) {
            $shared = $request->getAttribute(RequestAttribute::VIEW_PAYLOAD);
            if (is_array($shared)) {
                $data = array_merge($this->stringKeyed($shared), $data);
            }
        }

        $content = $this->view->render($template, $data);
        $response = $this->responseFactory->createResponse($code);
        $response->getBody()->write($content);

        return $response->withHeader(HttpHeader::CONTENT_TYPE, HttpValue::HTML);
    }

    /**
     * @param array<mixed, mixed> $values
     * @return array<string, mixed>
     */
    private function stringKeyed(array $values): array
    {
        $normalized = [];
        foreach ($values as $key => $value) {
            if (is_string($key)) {
                $normalized[$key] = $value;
            }
        }

        return $normalized;
    }
}
