<?php

return [
    'date.format'=>'Y-m-d H:i',
    'trucmuche'=>'ahouais?',
    'token.temps.validite'=> 6060, // temps de validité du token jwt en seconde
    'token.emmeteur'=> "api.toubeelib.fr", //url de l'emmeteur du token
    'token.audience'=> "api.toubeelib.fr", //url de l'audience du token
    'token.key.path' => __DIR__ . '/../../toubeelib.env', //path du fichier .env contenant le JWT secret key
    'token.jwt.algo' => 'HS512', //algo de jwt
];
