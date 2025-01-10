<?php

namespace toubeelibgateway\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetPraticienById extends AbstractAction
{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        return $responseToubeelib = $this->client->request('GET', 'praticiens/'.$args["id"], [
        ]);
        return $rs;
    }
}
