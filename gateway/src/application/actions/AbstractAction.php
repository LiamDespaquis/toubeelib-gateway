<?php

namespace toubeelibgateway\application\actions;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractAction
{
    public Client $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    abstract public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface ;


}
