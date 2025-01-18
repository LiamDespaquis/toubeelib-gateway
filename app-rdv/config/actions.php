<?php

use toubeelib\rdv\application\actions\DeleteRdvId;
use toubeelib\rdv\application\actions\GetPatient;
use toubeelib\rdv\application\actions\GetPraticien;
use toubeelib\rdv\application\actions\GetRdvId;
use toubeelib\rdv\application\actions\PatchRdv;
use toubeelib\rdv\application\actions\PostCreateRdv;
use toubeelib\rdv\application\actions\PostSignIn;
use toubeelib\rdv\application\actions\SearchPraticien;
use toubeelib\rdv\core\services\praticien\ServicePraticienInterface;
use toubeelib\rdv\application\actions\GetDisposPraticien;
use toubeelib\rdv\application\actions\GetDisposPraticienDate;
use toubeelib\rdv\core\services\rdv\ServiceRDVInterface;


return [

    GetDisposPraticien::class=>DI\autowire(),
    GetRdvId::class => DI\autowire(),
    PatchRdv::class => DI\autowire(),
    PostCreateRdv::class => DI\autowire(),
    DeleteRdvId::class => DI\autowire(),
    GetDisposPraticienDate::class => DI\autowire(),
    GetDisposPraticien::class => DI\autowire(),
    PostSignIn::class => DI\autowire(),
    SearchPraticien::class => DI\autowire(),
    GetPatient::class => DI\autowire(),
    GetPraticien::class => DI\autowire(),

    
    

];
