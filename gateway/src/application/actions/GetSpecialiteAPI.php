<?php

namespace toubeelibgateway\application\actions;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Exception\HttpBadRequestException;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;

class GetSpecialiteAPI extends AbstractAction
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
            /*echo $this->url . '/specialites' . $args["route"];*/
            return $responseToubeelib = $this->client->request(
                'GET',
                $this->url .'/specialites'. $args["route"],
                [ "timeout" => 5 ]
            );

        } catch (ConnectException | ServerException $e) {
            return $e->getResponse();
            /*throw new HttpInternalServerErrorException($rq, $e->getMessage());*/
        } catch (ClientException $e) {
            return $e->getResponse();
            /*match($e->getCode()) {*/
            /*    400 => throw new HttpBadRequestException($rq, " … "),*/
            /*    401 => throw new HttpUnauthorizedException($rq, " … "),*/
            /*    403 => throw new HttpForbiddenException($rq, " … "),*/
            /*    404 => throw new HttpNotFoundException($rq, " … ")*/
            /*};*/
        }
    }
}
