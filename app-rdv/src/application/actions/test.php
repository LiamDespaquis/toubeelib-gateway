<?php

namespace toubeelib\rdv\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use toubeelib\rdv\application\actions\AbstractAction;
use toubeelib\rdv\application\renderer\JsonRenderer;
use toubeelib\rdv\core\services\praticien\ServicePraticien;
use toubeelib\rdv\core\services\rdv\ServiceRDV;
use toubeelib\rdv\core\services\rdv\ServiceRDVInvalidDataException;
use toubeelib\rdv\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\rdv\infrastructure\repositories\ArrayRdvRepository;
use function _PHPStan_9815bbba4\React\Async\waterfall;

class test extends AbstractAction
{

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $status = 200;
        try {
            $serviceRdv = new ServiceRDV(new ServicePraticien(new ArrayPraticienRepository()), new ArrayRdvRepository());
            $rdvs = $serviceRdv->getRdvById('r1');
            $routeParser = RouteContext::fromRequest($rq)->getRouteParser();
            $data = ["rendezVous" => $rdvs,
                "links" => [
                    "self" => $routeParser->urlFor("getRdv", ['id' => $rdvs->id]),
                    "praticien" => $routeParser->urlFor("getPraticien",['id'=>$rdvs->praticien->id]),
                    "patient"=>$routeParser->urlFor("getPatient",['id'=>$rdvs->patientId])
                ]
            ];
            $rs = JsonRenderer::render($rs, 200, $data);

//            var_dump(get_object_vars($rdvs));
        } catch (ServiceRDVInvalidDataException $s) {
            $data = ["erreur" => "Erreur RDV invalide"];
            $status = 404;
        }
        return $rs;

    }
}
