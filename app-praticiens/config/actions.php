<?php

use toubeelib\application\actions\DeleteRdvId;
use toubeelib\application\actions\GetPatient;
use toubeelib\application\actions\GetPraticien;
use toubeelib\application\actions\GetRdvId;
use toubeelib\application\actions\PatchRdv;
use toubeelib\application\actions\PostCreateRdv;
use toubeelib\application\actions\PostSignIn;
use toubeelib\application\actions\SearchPraticien;

use toubeelib\application\actions\GetDisposPraticien;
use toubeelib\application\actions\GetDisposPraticienDate;

use toubeelib\praticiens\application\actions\GetSpecialite;

return [
    SearchPraticien::class => DI\autowire(),
    GetPraticien::class => DI\autowire(),
    GetSpecialite::class => DI\autowire()




];
