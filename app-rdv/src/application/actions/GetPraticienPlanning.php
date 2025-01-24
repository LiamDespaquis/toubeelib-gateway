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

class GetPraticienPlanning extends AbstractAction
{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {


        $paramDate = $rq->getQueryParams();


        $praticienIdValidator = Validator::key('id', Validator::Uuid()->notEmpty());
        $praticienValidator = Validator::key('start_date', Validator::dateTime($this->formatDate)->notEmpty())
        ->key('end_date', Validator::dateTime($this->formatDate));

        try {
            $praticienValidator->assert($paramDate);

            $start_date = DateTimeImmutable::createFromFormat($this->formatDate, $paramDate['start_date']);
            $end_date = DateTimeImmutable::createFromFormat($this->formatDate, $paramDate['end_date']);
            if($end_date->getTimestamp() <= $start_date->getTimestamp()) {
                throw new HttpBadRequestException($rq, "Date fin plus petite que Date debut");
            }
        } catch(NestedValidationException $e) {
            $start_date = null;
            $end_date = null;
        }

        try {

            $praticienIdValidator->assert($args);

            $dispos = $this->serviceRdv->getPlanningPraticien($args['id'], $start_date, $end_date);
            return JsonRenderer::render($rs, 200, $dispos);


        } catch(NestedValidationException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        }
    }
}
