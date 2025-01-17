<?php

namespace toubeelib\praticiens\core\services\praticien;

// ! Not use
use DI\Container;
use toubeelib\praticiens\core\domain\entities\praticien\Praticien;
use toubeelib\praticiens\core\dto\InputPraticienDTO;
use toubeelib\praticiens\core\dto\PraticienDTO;
use toubeelib\praticiens\core\dto\SpecialiteDTO;
use toubeelib\praticiens\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\praticiens\core\repositoryInterfaces\RepositoryEntityNotFoundException;

// ! Not use


class ServicePraticien implements ServicePraticienInterface
{
    private PraticienRepositoryInterface $praticienRepository;

    public function __construct(Container $cont)
    {
        $this->praticienRepository = $cont->get(PraticienRepositoryInterface::class);
    }

    public function createPraticien(InputPraticienDTO $p): PraticienDTO
    {
        // TODO : valider les données et créer l'entité
        return new PraticienDTO($$p);


    }

    public function getPraticienById(string $id): PraticienDTO
    {
        try {
            $praticien = $this->praticienRepository->getPraticienById($id);
            return new PraticienDTO($praticien);
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServicePraticienInvalidDataException('invalid Praticien ID');
        }
    }

    public function getSpecialiteById(string $id): SpecialiteDTO
    {
        try {
            $specialite = $this->praticienRepository->getSpecialiteById($id);
            return $specialite->toDTO();
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServicePraticienInvalidDataException('invalid Specialite ID');
        }
    }

    public function searchPraticien(PraticienDTO $p): array
    {
        $pratSearch = Praticien::fromDTO($p);
        return array_map(function (Praticien $p) {
            return new PraticienDTO($p);
        }, $this->praticienRepository->searchPraticiens($pratSearch));
    }

    public function getListeDisponibilite(string $idPraticien): array
    {

        $results = [];
        /*$listeRDV = $this->rdvRepository->getRdvByPraticien($idPraticien);*/
        /*$listeRDVHorraires = array_map(function ($rdv) {*/
        /*    if ($rdv->status != RendezVous::ANNULE) {*/
        /*        $rr = $rdv->dateHeure->format($this->dateFormat);*/
        /*        return $rr;*/
        /*    }*/
        /*}, $listeRDV);*/
        /*$startDate = new DateTimeImmutable("now");*/
        /*$startDate = $startDate->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]);*/
        /*$endDate = $startDate->add(new DateInterval("P7D"))->setTime(ServiceRDV::HFIN[0], ServiceRDV::HFIN[1]);*/
        /**/
        /*while ($startDate->diff($endDate)->format('%d') > 0) {*/
        /*    while ($startDate->format('U') % 86400 <= ServiceRDV::HFIN[0] * 3600 + ServiceRDV::HFIN[1] * 60) {*/
        /**/
        /*        if (!in_array($startDate->format($this->dateFormat), $listeRDVHorraires)) {*/
        /**/
        /*            $results[] = $startDate;*/
        /*        }*/
        /*        $startDate = $startDate->add(new DateInterval("PT30M"));*/
        /*    }*/
        /*    $startDate = $startDate->add(new DateInterval('P1D'))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]);*/
        /*}*/
        /**/
        return $results;
    }

    public function getListeDisponibiliteDate(string $idPraticien, ?string $test_start_Date, ?string $test_end_Date): array
    {
        //echo "test for getListeDisponibiliteDate";

        $results = [];
        /*$listeRDV = $this->rdvRepository->getRdvByPraticien($idPraticien);*/
        /*$listeRDVHorraires = array_map(function ($rdv) {*/
        /*    if ($rdv->status != RendezVous::ANNULE) {*/
        /*        $rr = $rdv->dateHeure->format($this->dateFormat);*/
        /*        return $rr;*/
        /*    }*/
        /*}, $listeRDV);*/
        /**/
        /*// ! return vide si start date est vide uniquement*/
        /*$startDate = $test_start_Date != null*/
        /*    ? (new DateTimeImmutable($test_start_Date))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1])*/
        /*    : (new DateTimeImmutable('now'))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]);*/
        /**/
        /*$endDate = $test_end_Date != null && $test_end_Date != $test_start_Date*/
        /*    ? (new DateTimeImmutable($test_end_Date))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1])*/
        /*    : (new DateTimeImmutable('now'))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1])->add(new DateInterval('P31D'));*/
        /**/
        /*while ($startDate->diff($endDate)->format('%d') > 0) {*/
        /*    while ($startDate->format('U') % 86400 <= ServiceRDV::HFIN[0] * 3600 + ServiceRDV::HFIN[1] * 60) {*/
        /**/
        /*        if (!in_array($startDate->format($this->dateFormat), $listeRDVHorraires)) {*/
        /**/
        /*            $results[] = $startDate;*/
        /*        }*/
        /*        $startDate = $startDate->add(new DateInterval("PT30M"));*/
        /*    }*/
        /*    $startDate = $startDate->add(new DateInterval('P1D')) ->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]);*/
        /*}*/
        /**/
        return $results /*!= null ? $results : "Pas de disponibilité pour ce praticien"*/;
    }

    public function getPlanningPraticien(string $idPraticien, ?string $test_start_Date, ?string $test_end_Date): array
    {
        $results = [];
        /*$startDate = $test_start_Date != null*/
        /*    ? (new DateTimeImmutable($test_start_Date))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1])*/
        /*    : (new DateTimeImmutable('now'))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]);*/
        /**/
        /*$endDate = $test_end_Date != null && $test_end_Date != $test_start_Date*/
        /*    ? (new DateTimeImmutable($test_end_Date))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1])*/
        /*    : (new DateTimeImmutable('now'))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1])->add(new DateInterval('P31D'));*/
        /**/
        /*$listeRDV = $this->rdvRepository->getRdvByPraticien($idPraticien);*/
        /*foreach($listeRDV as $rdv) {*/
        /*    if ($rdv->status != RendezVous::ANNULE && $rdv->dateHeure->format('U') > $startDate->format('U') && $rdv->dateHeure->format('U') < $endDate->format('U')) {*/
        /*        $results[] = $rdv->toDTO($this->servicePraticien->getPraticienById($idPraticien));*/
        /*    }*/
        /*}*/

        return $results;
    }
}
