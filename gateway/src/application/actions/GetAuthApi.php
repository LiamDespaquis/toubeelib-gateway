<?php

namespace toubeelibgateway\application\actions;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Exception\HttpBadRequestException;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpInternalServerErrorException;
use toubeelibgateway\application\actions\ActionGetApiGenerique;

class GetAuthApi extends ActionGetApiGenerique
{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $uri = $rq->getUri()->getPath();

            return $responseToubeelib = $this->client->request(
                $rq->getMethod(),
                $this->url. $uri,
                [
                    'timeout' => 5,
                    'headers' => [
                        'accept' => 'application/json',
                    ],
                        'json' => $rq->getParsedBody(),

                ]
            );
            return $rs;
        } catch (ConnectException | ServerException $e) {
            return $e->getResponse();
        } catch (ClientException $e) {
            return $e->getResponse();
        }
    }
}
