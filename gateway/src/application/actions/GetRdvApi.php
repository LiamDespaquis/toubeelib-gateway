<?php

namespace toubeelibgateway\application\actions;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Exception\HttpBadRequestException;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpInternalServerErrorException;
use toubeelibgateway\application\actions\AbstractAction;

class GetRdvApi extends AbstractAction
{
    private string $url;
    public function __construct(Client $client, string $url)
    {
        $this->client = $client;
        $this->url = $url;
    }
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
                        'Authorization' => $rq->getHeader("Authorization")[0],

                    ],
                        'json' => $rq->getParsedBody(),

                ]
            );
            return $rs;
        } catch (ConnectException | ServerException $e) {
            return $e->getResponse();
            /*throw new HttpInternalServerErrorException($rq, $e->getMessage());*/
        } catch (ClientException $e) {
            return $e->getResponse();
            /*match($e->getCode()) {*/
            /*    400 => throw new HttpBadRequestException($rq, $e->getMessage()),*/
            /*    401 => throw new HttpUnauthorizedException($rq, $e->getMessage),*/
            /*    403 => throw new HttpForbiddenException($rq, $e->getMessage),*/
            /*    404 => throw new HttpNotFoundException($rq, $e->getMessage)*/
            /*};*/
        }
    }
}
