<?php

namespace toubeelibgateway\application\actions;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;

class GetPraticienById extends AbstractAction
{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {

        try {
            /*echo $args['route'];*/
            return $responseToubeelib = $this->client->request('GET', 'praticiens'.$args["route"], [
                "json" => $rq->getParsedBody()
            ]);
        } catch (ConnectException | ServerException $e) {
            throw new HttpInternalServerErrorException($rq, " … ");
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
