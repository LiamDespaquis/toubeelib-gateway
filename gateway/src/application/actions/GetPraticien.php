<?php

namespace toubeelibgateway\application\actions;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelibgateway\application\actions\AbstractAction;

class GetPraticien extends AbstractAction
{
    public Client $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        return $responseToubeelib = $this->client->request('GET', 'praticiens', [
        ]);
        return $rs;
    }
}
