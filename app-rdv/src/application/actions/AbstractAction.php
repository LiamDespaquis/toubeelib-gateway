<?php

namespace toubeelib\rdv\application\actions;


use DI\Container;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\rdv\core\services\ServiceAuthInterface;
use toubeelib\rdv\core\services\praticien\ServicePraticienInterface;
use toubeelib\rdv\core\services\rdv\ServiceRDVInterface;
use toubeelib\rdv\providers\auth\AuthnProviderInterface;
use toubeelib\rdv\core\services\patient\ServicePatientInterface;

abstract class AbstractAction
{
    protected ServiceRDVInterface $serviceRdv;
    protected ServicePraticienInterface $servicePraticien; 
    protected AuthnProviderInterface $authProvider;
    protected ServicePatientInterface $servicePatient;
    protected string $formatDate;
    protected Container $cont;

    protected Logger $loger;
    /**
     * @param ServiceRDVInterface $srdv
     * @param ServicePraticienInterface $sprt
     * @param string $formatDate
     */
    public function __construct(Container $cont)
    {
        $this->serviceRdv = $cont->get(ServiceRDVInterface::class);
        $this->servicePraticien = $cont->get(ServicePraticienInterface::class);
        $this->servicePatient = $cont->get(ServicePatientInterface::class);
        $this->formatDate = $cont->get('date.format');
        $this->loger = $cont->get(Logger::class)->withName(get_class($this));
        

    }

    /**
     * @param array<int,mixed> $args
     */
    abstract public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface ;
    

}
