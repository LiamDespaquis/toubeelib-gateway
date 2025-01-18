<?php

namespace toubeelib\rdv\infrastructure\repositories;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use toubeelib\rdv\core\domain\entities\praticien\Praticien;
use toubeelib\rdv\core\domain\entities\praticien\Specialite;
use toubeelib\rdv\core\repositoryInterfaces\PraticienRepositoryInterface;

class ApiPraticienRepository implements PraticienRepositoryInterface
{
    protected Client $client;
    protected LoggerInterface $logger;
    protected string $uriBase;

    public function __construct(LoggerInterface $logger)
    {
        //Uri base et client pas fait par l'injection de dépendance car impossible de la faire fonctionner pour cette classe
        $this->uriBase = 'http://gateway.toubeelib/praticiens/' ;
        $this->client = new Client(['base_uri' => $this->uriBase]);
        $this->logger = $logger;
    }

    public function getSpecialiteById(string $id): Specialite
    {

    }

    public function save(Praticien $praticien): string
    {
    }

    public function getPraticienById(string $id): Praticien
    {
        try {
            $resPraticien = $this->client->request('GET', $id);
            $objPraticien = json_decode($resPraticien->getBody()->getContents());
            $praticien = new Praticien($objPraticien->nom, $objPraticien->prenom, $objPraticien->adresse, $objPraticien->tel);
            $praticien->setId($objPraticien->id);
            $praticien->setSpecialite(new Specialite('', $objPraticien->specialiteLabel), '');
        } catch(\Exception $e) {
            $this->logger->error($e->getTraceAsString());
        }
        return $praticien;
    }

    public function searchPraticiens(Praticien $praticien): array
    {
    }
}
