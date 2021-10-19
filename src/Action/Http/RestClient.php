<?php

declare(strict_types=1);

namespace App\Action\Http;

use App\Contract\Http\ClientInterface;
use App\Exception\Client\RequestException;
use App\Factory\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class RestClient implements ClientInterface
{
    public const QUERY_PARAM_ORDER_BY = 'orderBy';
    public const QUERY_PARAM_DIRECTION = 'direction';
    public const QUERY_PARAM_LIMIT = 'limit';
    public const QUERY_PARAM_PAGE = 'page';

    private ClientFactory $clientFactory;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    /**
     * @inheritDoc
     */
    public function request(Request $request): ResponseInterface
    {
        $uri = $request->getUri();

        if ($request->isCollection()) {
            $query = [
                self::QUERY_PARAM_ORDER_BY => $request->getOrderBy(),
                self::QUERY_PARAM_DIRECTION => $request->getDirection(),
                self::QUERY_PARAM_LIMIT => $request->getLimit(),
                self::QUERY_PARAM_PAGE => $request->getPage(),
            ];

            $uri .= '?' . http_build_query($query);
        }

        $client = $this->clientFactory->create();

        $headers = array_replace(
            [
                'Accept' => 'application/json',
            ],
            $request->getHeaders()
        );

        try {
            return $client->request(
                $request->getMethod(),
                $uri,
                [
                    'json' => $request->getBody(),
                    'headers' => $headers,
                ]
            );
        } catch (GuzzleException $exception) {
            $statusCode = 0;

            $message = $exception->getMessage();

            if ($exception instanceof \GuzzleHttp\Exception\RequestException) {
                $exceptionResponse = $exception->getResponse();

                if ($exceptionResponse !== null) {
                    $statusCode = $exceptionResponse->getStatusCode();

                    $responseContent = (array)\json_decode($exceptionResponse->getBody()->getContents(), true);

                    if (array_key_exists('errors', $responseContent)) {
                        $errors = $responseContent['errors'];

                        if (is_iterable($errors)) {
                            $message = \json_encode($errors, JSON_PRETTY_PRINT);
                        }
                    } elseif ($statusCode >= 500 && array_key_exists('debug', $responseContent)) {
                        $debug = $responseContent['debug'];

                        if (is_iterable($debug)) {
                            $message = \json_encode($debug, JSON_PRETTY_PRINT);
                        }
                    }
                }
            }

            throw new RequestException($message, $statusCode, $exception);
        }
    }
}
