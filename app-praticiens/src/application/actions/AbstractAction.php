<?php

namespace toubeelib\praticiens\application\actions;

use DI\Container;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\praticiens\core\services\patient\ServicePatientInterface;
use toubeelib\praticiens\core\services\praticien\ServicePraticienInterface;
use toubeelib\praticiens\core\services\rdv\ServiceRDVInterface;
use toubeelib\praticiens\providers\auth\AuthnProviderInterface;

abstract class AbstractAction
{
    protected ServicePraticienInterface $servicePraticien;
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
        $this->servicePraticien = $cont->get(ServicePraticienInterface::class);
        $this->formatDate = $cont->get('date.format');
        $this->loger = $cont->get(Logger::class)->withName(get_class($this));


    }

    /**
     * @param array<int,mixed> $args
     */
    abstract public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface ;


}
