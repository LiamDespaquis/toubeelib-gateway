<?php

namespace toubeelib\rdv\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Exception\HttpBadRequestException;
use toubeelib\rdv\application\actions\AbstractAction;
use toubeelib\rdv\application\renderer\JsonRenderer;
use toubeelib\rdv\core\dto\InputRdvDto;
use toubeelib\rdv\core\services\praticien\ServicePraticien;
use toubeelib\rdv\core\services\rdv\ServiceRDVInvalidDataException;
use toubeelib\rdv\infrastructure\repositories\ArrayRdvRepository;
use toubeelib\rdv\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\rdv\core\services\rdv\ServiceRDV;

class PatchRdv extends AbstractAction
{

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {

        $data=$rq->getParsedBody();
        $data['id']=$args['id'];

        //validation de l'existance de id, idpraticien, specialite, dateHeure
        $rdvInputValidator = Validator::key('id',Validator::stringType()->notEmpty())
            ->key('praticienId', Validator::stringType()->notEmpty())
            ->key('patientId', Validator::stringType()->notEmpty())
            ->key('specialite', Validator::stringType()->notEmpty())
            ->key('dateHeure', Validator::dateTime($this->formatDate)->notEmpty())
            ;

        try{
            $rdvInputValidator->assert($data);
            $inputRdv = InputRdvDto::fromArray($data);
            $inputRdv->setId($data['id']);
            $dto=$this->serviceRdv->modifRendezVous($inputRdv);
            $data=GetRdvId::ajouterLiensRdv($dto, $rq);
            $this->loger->info('PatchRdv: '.$args['id'].' : ');
            $rs = JsonRenderer::render($rs, 201, $data);


        }catch(NestedValidationException $e){
            $this->loger->error('PatchRdv : '.$args['id'].' : '.$e->getMessage());
            throw new HttpBadRequestException($rq,$e->getMessage());
        }catch(ServiceRDVInvalidDataException $e){
            $this->loger->error('PatchRdv: '.$args['id'].' : '.$e->getMessage());
            throw new HttpBadRequestException($rq,$e->getMessage());
        }



        return $rs;
    }
}
