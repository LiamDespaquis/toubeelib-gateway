<?php

namespace toubeelib\praticiens\application\actions;

use Exception;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use toubeelib\praticiens\application\renderer\JsonRenderer;
use toubeelib\praticiens\core\domain\entities\praticien\Praticien;
use toubeelib\praticiens\core\domain\entities\praticien\Specialite;
use toubeelib\praticiens\core\dto\PraticienDTO;

class SearchPraticien extends AbstractAction
{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $jsonPraticien = $rq->getQueryParams();
        $cles = ['tel', 'prenom', 'nom', 'adresse', 'specialite'];
        foreach($cles as $cle) {
            if(!isset($jsonPraticien[$cle])) {
                $jsonPraticien[$cle] = "";
            }
            try {
                $validatorPraticien = Validator::key($cle, Validator::stringType());
                $validatorPraticien->assert($jsonPraticien);
            } catch(NestedValidationException $e) {
                throw new HttpBadRequestException($rq, $e->getMessage());
            }
        }

        try {
            $pra = new Praticien($jsonPraticien['nom'], $jsonPraticien['prenom'], $jsonPraticien['adresse'], "");
            $pra->setId("");
            $pra->setSpecialite(new Specialite("", $jsonPraticien['specialite']));
            $praticienDto = new PraticienDTO($pra);

            $praticiensCherche = $this->servicePraticien->searchPraticien($praticienDto);
            $this->loger->info("recherche du praticien $praticienDto");
            $rs = JsonRenderer::render($rs, 200, $praticiensCherche);
            $this->loger->error(array_reduce($rs->getHeaders(), function ($carry, $item) {
                return $carry . $item[0] . "\n";
            }, ""));
            return $rs;

        } catch(Exception $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }

}
