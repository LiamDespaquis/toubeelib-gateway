<?php

namespace toubeelib\praticiens\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use toubeelib\praticiens\application\actions\AbstractAction;
use toubeelib\praticiens\application\renderer\JsonRenderer;
use toubeelib\praticiens\core\services\rdv\ServiceRDVInvalidDataException;
use toubeelib\praticiens\core\services\ServiceOperationInvalideException;

class DeleteRdvId extends AbstractAction
{
    //annule rdv, pas supprimer

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {

        $status = 200;
        try {
            $rdv = $this->serviceRdv->annulerRendezVous($args['id']);
            $rs = JsonRenderer::render($rs,201, GetRdvId::ajouterLiensRdv($rdv, $rq));
            $this->loger->info('DeleteRdv : '.$args['id'].' rdv supprimé');
        } catch (ServiceRDVInvalidDataException $e) {
            $this->loger->error('DeleteRdv : '.$args['id'].' : '.$e->getMessage());
            throw new HttpNotFoundException($rq,$e->getMessage());
        }catch (ServiceOperationInvalideException $e){
            $this->loger->error('DeleteRdv : '.$args['id'].' : '.$e->getMessage());
            throw new HttpBadRequestException($rq, $e->getMessage());
        }
        return $rs;
    }
}
