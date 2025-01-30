<?php


use toubeelib\application\actions\PostSignIn;
use toubeelib\application\actions\ValidateTokenAction;


return [

    PostSignIn::class => DI\autowire(),
    ValidateTokenAction::class => DI\autowire(),
];
