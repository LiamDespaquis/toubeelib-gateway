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

class GetRdv extends AbstractAction
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
            return $responseToubeelib = $this->client->request(
                'GET',
                $this->url. '/rdvs' . $args['route'],
                [
                    'timeout' => 5,
                ]
            );
            return $rs;
        } catch (ConnectException | ServerException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        } catch (ClientException $e) {
            match($e->getCode()) {
                400 => throw new HttpBadRequestException($rq, " … "),
                401 => throw new HttpUnauthorizedException($rq, " … "),
                403 => throw new HttpForbiddenException($rq, " … "),
                404 => throw new HttpNotFoundException($rq, " … ")
            };
        }
    }
}
