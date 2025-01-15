<?php

use toubeelib\application\actions\DeleteRdvId;
use toubeelib\application\actions\GetPatient;
use toubeelib\application\actions\GetPraticien;
use toubeelib\application\actions\GetRdvId;
use toubeelib\application\actions\PatchRdv;
use toubeelib\application\actions\PostCreateRdv;
use toubeelib\application\actions\PostSignIn;
use toubeelib\application\actions\SearchPraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\application\actions\GetDisposPraticien;
use toubeelib\application\actions\GetDisposPraticienDate;
use toubeelib\core\services\rdv\ServiceRDVInterface;


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
