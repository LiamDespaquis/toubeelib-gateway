<?php

namespace toubeelib\praticiens\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\praticiens\application\renderer\JsonRenderer;
use toubeelib\praticiens\core\services\praticien\ServicePraticienInvalidDataException;

class GetSpecialite extends AbstractAction
{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        if(!isset($args['id'])) {
            return JsonRenderer::render($rs, 400, ['error' => 'missing id']);
        }
        $id = $args['id'];
        try {
            $specialite = $this->servicePraticien->getSpecialiteById($id);
            return JsonRenderer::render($rs, 200, $specialite);
        } catch(ServicePraticienInvalidDataException $e) {
            return JsonRenderer::render($rs, 400, ['error' => 'missing id']);
        }

    }
}
