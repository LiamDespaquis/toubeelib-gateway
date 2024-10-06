<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use toubeelib\application\actions\AbstractAction;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\ServiceOperationInvalideException;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;

class DeleteRdvId extends AbstractAction
{
    //annule rdv, pas supprimer

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {

        $status = 200;
        try {
            $rdv = $this->serviceRdv->annulerRendezVous($args['id']);
            $rs = JsonRenderer::render($rs,201, GetRdvId::ajouterLiensRdv($rdv, $rq));
        } catch (ServiceRDVInvalidDataException $e) {
            throw new HttpNotFoundException($rq,$e->getMessage());
        }catch (ServiceOperationInvalideException $e){
            throw new HttpBadRequestException($rq, $e->getMessage());
        }
        return $rs;
    }
}
