<?php

namespace toubeelibgateway\application\actions;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;
use toubeelibgateway\application\renderer\JsonRenderer;

class GetPraticienApi extends AbstractAction
{
    private string $url;
    public function __construct(Client $client, string $url)
    {
        $this->client = $client;
        $this->url = $url;
    }
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $uri = $args["route"];

        try {
            $responseToubeelib = $this->client->request(
                'GET',
                $this->url .'/praticiens'. $uri,
                [
                    "timeout" => 8 ,

                ]
            );
            $body = $responseToubeelib->getBody();
            $status = $responseToubeelib->getStatusCode();
            return JsonRenderer::render($rs, $status)->withBody($body);
        } catch (ConnectException | ServerException $e) {
            return $e->getResponse();
            
        } catch (ClientException $e) {
            return $e->getResponse();
            
        }
    }
}
