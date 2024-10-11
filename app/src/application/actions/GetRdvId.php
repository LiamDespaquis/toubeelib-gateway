<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use toubeelib\application\actions\AbstractAction;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\dto\RdvDTO;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;

class GetRdvId extends AbstractAction
{
        // todo : check status

    public static function ajouterLiensRdv(RdvDTO $rdv, ServerRequestInterface $rq):array{
        $routeParser = RouteContext::fromRequest($rq)->getRouteParser();
        return ["rendezVous" => $rdv,
            "links" => [
                "self" => $routeParser->urlFor("getRdv", ['id' => $rdv->id]),
                "praticien" => $routeParser->urlFor("getPraticien", ['id' => $rdv->praticien->id]),
                "patient" => $routeParser->urlFor("getPatient", ['id' => $rdv->patientId])
            ]
        ];
    }
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $status = 200;
        try {
            $rdvs = $this->serviceRdv->getRdvById($args['id']);

            $data=GetRdvId::ajouterLiensRdv($rdvs,$rq);
            $rs = JsonRenderer::render($rs, 200, $data);

        } catch (ServiceRDVInvalidDataException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }catch (\Exception $e){
            throw new HttpInternalServerErrorException($rq,$e->getMessage());
        }
//        $rs->getBody()->write(json_encode($data));
//        return $rs
//            ->withHeader('Content-Type', 'application/json')
//            ->withStatus($status);

        return $rs;
    }
}
