<?php

namespace toubeelib\rdv\application\actions;

use DateTime;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use toubeelib\rdv\application\actions\AbstractAction;
use toubeelib\rdv\application\renderer\JsonRenderer;
use toubeelib\rdv\core\services\praticien\ServicePraticien;
use toubeelib\rdv\core\services\rdv\ServiceRDV;
use toubeelib\rdv\infrastructure\repositories\ArrayRdvRepository;
use toubeelib\rdv\infrastructure\repositories\ArrayPraticienRepository;

class GetDisposPraticienDate extends AbstractAction
{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {


        $jsonDates = $rq->getQueryParams();

        $status = 200;

        $praticienIdValidator = Validator::key('id', Validator::Uuid()->notEmpty());
        $praticienValidator = Validator::key('date_debut', Validator::dateTime($this->formatDate)->notEmpty())
        ->key('date_fin', Validator::dateTime($this->formatDate)->notEmpty());


        try {

            $praticienValidator->assert($jsonDates);
            $dateDebut = DateTimeImmutable::createFromFormat($this->formatDate, $jsonDates['date_debut']);
            $dateFin = DateTimeImmutable::createFromFormat($this->formatDate, $jsonDates['date_fin']);
            if($dateFin->getTimestamp() <= $dateDebut->getTimestamp()) {
                throw new HttpBadRequestException($rq, "Date fin plus petite que Date debut");
            }
            $dateDebut = $jsonDates['date_debut'];
            $dateFin = $jsonDates['date_fin'];
        } catch(NestedValidationException $e) {
            /*echo $e->getMessage();*/
            // si le format des dates envoyé n'est pas valide alors on créer nos propres dates
            $dateDebut = new DateTimeImmutable();
            $dateFin = $dateDebut->modify('+2 weeks');
            $dateDebut = $dateDebut->format($this->formatDate);
            $dateFin = $dateFin->format($this->formatDate);

            // $this->loger->error("date invalides : ". $jsonDates['date_debut'] ." " . $jsonDates['date_fin']);
        }

        try {
            $praticienIdValidator->assert($args);

            $dispos = $this->serviceRdv->getListeDisponibiliteDate($args['id'], $dateDebut, $dateFin);
            for($i = 0; $i < count($dispos);$i++) {
                $dispos[$i] = $dispos[$i]->format($this->formatDate);
            }
            return JsonRenderer::render($rs, 200, $dispos);

        } catch(NestedValidationException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        }
    }
}
