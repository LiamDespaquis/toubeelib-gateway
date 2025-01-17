<?php

namespace toubeelib\praticiens\core\services\rdv;

use DateInterval;
use DateTimeImmutable;
use DI\Container;
use Exception;
use toubeelib\praticiens\core\domain\entities\rdv\RendezVous;
use toubeelib\praticiens\core\dto\InputRdvDto;
use toubeelib\praticiens\core\dto\RdvDTO;
use toubeelib\praticiens\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\praticiens\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\praticiens\core\services\ServiceRessourceNotFoundException as toubeelibServiceRessourceNotFoundException;
use toubeelib\praticiens\core\services\praticien\ServicePraticien;
use toubeelib\praticiens\core\services\praticien\ServicePraticienInterface;
use toubeelib\praticiens\core\services\rdv\ServiceRDVInterface;
use toubeelib\praticiens\core\services\rdv\ServiceRDVInvalidDataException;
use toubeelib\praticiens\core\services\rdv\ServiceRessourceNotFoundException;
use toubeelib\praticiens\core\services\ServiceOperationInvalideException;

class ServiceRDV implements ServiceRDVInterface
{
    private RdvRepositoryInterface $rdvRepository;
    private ServicePraticien $servicePraticien;
    private string $dateFormat;

    public const INTERVAL = 30;
    public const HDEBUT = [9, 00];
    public const HFIN = [17, 30];

    public function __construct(Container $cont)
    {
        $this->rdvRepository = $cont->get(RdvRepositoryInterface::class);
        $this->servicePraticien = $cont->get(ServicePraticienInterface::class);
        $this->dateFormat = $cont->get('date.format');
    }

    public function getRdvById(string $id): RdvDTO
    {
        try {
            $rdv = $this->rdvRepository->getRdvById($id);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new Exception('invalid RDV ID');
        }
        $praticien = $this->servicePraticien->getPraticienById($rdv->getPraticienId());
        return $rdv->toDTO($praticien);

    }

    /*string $praticienID, $patientID, string $specialite, \DateTimeImmutable $dateHeure*/
    public function creerRendezvous(InputRdvDto $inputRdvDto): RdvDTO
    {
        $rdv = RendezVous::fromInputDto($inputRdvDto);

        $id = RamseyUuid::uuid4()->__toString();
        $rdv->setId($id);

        try {
            $praticien = $this->servicePraticien->getPraticienById($rdv->getPraticienId());
            if ($praticien->specialiteLabel != $this->servicePraticien->getSpecialiteById($rdv->getSpecialite())->label) {
                throw new \Exception($praticien->specialiteLabel . "=!" . $rdv->getSpecialite());
            }

            //mauvais check, ne marche que si le rdv est dans les dispo proposé par la methode, si il est plus loin, il ne sera pas checké
            if (!in_array($rdv->getDateHeure(), $this->getListeDisponibilite($rdv->getPraticienId()))) {
                throw new \Exception("Praticien indisponible");
            }
        } catch (\Exception $e) {
            throw new ServiceRDVInvalidDataException("Création de rdv impossible : " . $e->getMessage());
        }
        $this->rdvRepository->addRdv($id, $rdv);
        return $rdv->toDTO($praticien);
    }


    public function getRdvByPatient(string $id): array
    {
        try {
            $listeRDV = $this->rdvRepository->getRdvByPatient($id);
        } catch(RepositoryEntityNotFoundException $e) toubeelibServiceRessourceNotFoundException
        return array_map(
            function (RendezVous $rdv) {
                return $rdv->toDTO($this->servicePraticien->getPraticienById($rdv->getPraticienId()));
            },
            $listeRDV
        );
    }

    /*string $praticienID*/

    public function annulerRendezVous(string $id): RdvDTO
    {
        try {
            $rdvAAnnuler = $this->getRdvById($id);
            if($rdvAAnnuler->getStatus() == RendezVous::MAINTENU ||
            $rdvAAnnuler->getStatus() == RendezVous::ANNULE) {
                $this->rdvRepository->cancelRdv($id);
                $rdvAAnnuler->setStatus(RendezVous::ANNULE);
                return $rdvAAnnuler;
            } else {
                throw new ServiceOperationInvalideException("Rendez vous $id non annulable");
            }
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServiceRDVInvalidDataException($e->getMessage());
        }
    }

    /* string $id, string $praticienId, string $patientId, string $specialite, \DateTimeImmutable $dateHeure */
    public function modifRendezVous(InputRdvDto $inputRdv): RdvDTO
    {

        //ancien rdv
        $rdvOld = $this->rdvRepository->getRdvById($inputRdv->getId());

        //praticien du nouveau rdv
        $praticien = $this->servicePraticien->getPraticienById($inputRdv->getPraticienId());

        if ($rdvOld->getStatus() != RendezVous::MAINTENU) {
            echo $rdvOld->getStatus();
            throw new \Exception("Impossible de changer les informations d'un rdv qui n'est pas 'maintenu'");
        }

        if ($rdvOld->getDateHeure() != $inputRdv->getDateHeure() || $rdvOld->getSpecialite() != $inputRdv->getSpecialite()) {

            $this->annulerRendezVous($inputRdv->getId());
            $res = $this->creerRendezvous($inputRdv);
            $this->rdvRepository->modifierRdv(new RendezVous($inputRdv->getPraticienId(), $inputRdv->getPatientId(), $inputRdv->getSpecialite(), $inputRdv->getDateHeure(), $rdvOld->getStatus()));
            $res->status = $rdvOld->getStatus();
            return $res;
        } else {
            if ($praticien->specialiteLabel != $this->servicePraticien->getSpecialiteById($rdvOld->getSpecialite())->label) {
                throw new \Exception($praticien->specialiteLabel . "=!" . $rdvOld->getSpecialite());
            }
            $this->rdvRepository->modifierRdv(new RendezVous($inputRdv->getPraticienId(), $inputRdv->getPatientId(), $inputRdv->getSpecialite(), $inputRdv->getDateHeure(), $rdvOld->getStatus()));


            //$rdvOld->specialiteLabel = $inputRdv->getSpecialite();
            return $rdvOld->toDTO($praticien);
        }

    }



}
