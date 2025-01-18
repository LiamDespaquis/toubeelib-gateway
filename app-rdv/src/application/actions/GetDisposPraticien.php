<?php
namespace toubeelib\rdv\application\actions;


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


class GetDisposPraticien extends AbstractAction{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        
        $praticienValidator=Validator::key('id',Validator::stringType()->notEmpty());

        try{
            $praticienValidator->assert($args);
            $dispos=$this->serviceRdv->getListeDisponibilite($args['id']);
            for($i=0; $i<count($dispos);$i++){
                $dispos[$i]=$dispos[$i]->format($this->formatDate);
            }
            
            $this->loger->info('GetDispo du praticien : '.$args['id']);
            return JsonRenderer::render($rs, 200, $dispos);

        }catch(NestedValidationException $e){
            $this->loger->error('GetDispoPraticien : '.$args['id'].' : '.$e->getMessage());
            throw new HttpBadRequestException($rq,$e->getMessage());
        } catch (\Exception $e) {
            $this->loger->error('GetDispoPraticien : '.$args['id'].' : '.$e->getMessage());
            throw new \Exception($rq,$e->getMessage());
        }

    }

}
