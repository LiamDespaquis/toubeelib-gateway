<?php
namespace toubeelib\praticiens\application\actions;


use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use toubeelib\praticiens\application\actions\AbstractAction;
use toubeelib\praticiens\application\renderer\JsonRenderer;

class GetPraticienPlanning extends AbstractAction{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        //echo "test action for GetDisposPraticienDate";


        //var_dump($rq->getParsedBody()); 


        $jsonDates = $rq->getParsedBody();

        $status = 200;
        $champs = ['id', 'start_Date', 'end_Date'];

        $praticienIdValidator = Validator::key('id',Validator::Uuid()->notEmpty());
        $praticienValidator = Validator::key('start_date', Validator::dateTime($this->formatDate)->notEmpty())
        ->key('end_date', Validator::dateTime($this->formatDate));


        
        try{

            $praticienValidator->assert($jsonDates);
            $praticienIdValidator->assert($args);
            $dateDebut = DateTimeImmutable::createFromFormat($this->formatDate,$jsonDates['start_date']);
            $dateFin = DateTimeImmutable::createFromFormat($this->formatDate,$jsonDates['end_date']);
            if($dateFin->getTimestamp()<= $dateDebut->getTimestamp()){
                throw new HttpBadRequestException($rq, "Date fin plus petite que Date debut");
            }
            
            $dispos=$this->serviceRdv->getPlanningPraticien($args['id'], $jsonDates['start_date'], $jsonDates['end_date'],);
            return JsonRenderer::render($rs, 200, $dispos);
            

        }catch(NestedValidationException $e){
            throw new HttpBadRequestException($rq,$e->getMessage());
        }
    }
}
