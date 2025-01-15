<?php

namespace toubeelib\application\actions;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use toubeelib\application\renderer\JsonRenderer;

class GetPatient extends AbstractAction{

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {

        $idvalidator = Validator::key('id', Validator::Uuid());
        try{
            $idvalidator->assert($args);

            $patient = $this->servicePatient->getPatientById($args['id']);

            return JsonRenderer::render($rs, 200,$patient);

        }catch(NestedValidationException $e){
            throw new HttpBadRequestException($rq, $e->getMessage());
        }
    }
}
