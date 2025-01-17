<?php

namespace toubeelib\praticiens\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use toubeelib\praticiens\application\actions\AbstractAction;
use toubeelib\praticiens\application\renderer\JsonRenderer;
use toubeelib\praticiens\core\services\praticien\ServicePraticien;
use toubeelib\praticiens\core\services\rdv\ServiceRDV;
use toubeelib\praticiens\core\services\rdv\ServiceRDVInvalidDataException;
use toubeelib\praticiens\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\praticiens\infrastructure\repositories\ArrayRdvRepository;
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
