<?php declare(strict_types=1);

namespace Swoft\Http\Server\Formatter;

use Psr\Http\Message\ResponseInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Contract\ResponseFormatterInterface;
use Swoft\Http\Message\Response;
use Swoft\Stdlib\Helper\Arr;
use Swoft\Stdlib\Helper\JsonHelper;

/**
 * Class JsonResponseFormatter
 *
 * @Bean()
 *
 * @since 2.0
 */
class JsonResponseFormatter implements ResponseFormatterInterface
{
    /**
     * @param Response|ResponseInterface $response
     *
     * @return Response
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function format(Response $response): Response
    {
        $response = $response
            ->withoutHeader('Content-Type')
            ->withAddedHeader('Content-Type', ContentType::JSON);

        $data = $response->getData();

        if ($data !== null && (Arr::isArrayable($data) || is_string($data))) {
            $data    = is_string($data) ? ['data' => $data] : $data;
            $content = JsonHelper::encode($data, JSON_UNESCAPED_UNICODE);
            return $response->withContent($content);
        }

        return $response->withContent('{}');
    }
}
