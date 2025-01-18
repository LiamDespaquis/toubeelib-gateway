<?php

namespace toubeelib\rdv\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use toubeelib\rdv\application\renderer\JsonRenderer;
use toubeelib\rdv\core\services\praticien\ServicePraticienInvalidDataException;

class GetPraticien extends AbstractAction
{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $idval = Validator::key('id', Validator::Uuid());

        try {
            $idval->assert($args);

            $praticien = $this->servicePraticien->getPraticienById($args['id']);
            return JsonRenderer::render($rs, 200, $praticien);

        } catch(NestedValidationException $e) {
            throw new HttpBadRequestException($rq, "Id du praticien invalide");
        } catch(ServicePraticienInvalidDataException $e) {
            throw new HttpNotFoundException($rq, "Praticien non trouvé");
        }
    }
}
