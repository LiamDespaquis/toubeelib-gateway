<?php

namespace toubeelib\rdv\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

 class HomeAction extends AbstractAction
{


    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        var_dump($rq->getHeaders());
        $rs->getBody()->write('Hello World :D');
        return $rs;}

}
