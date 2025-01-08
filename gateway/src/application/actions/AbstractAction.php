<?php

namespace toubeelibgateway\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractAction
{
    public function __construct()
    {
    }

    abstract public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface ;


}
