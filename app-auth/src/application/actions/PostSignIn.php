<?php

namespace toubeelib\application\actions;

use DI\Container;
use Error;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\dto\CredentialsDTO;
use toubeelib\core\services\ServiceAuthUserNotFoundException;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;
use toubeelib\providers\auth\AuthnProviderInterface;

class PostSignIn extends AbstractAction
{
    public function __construct(Container $co)
    {
        parent::__construct($co);

    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {

        $jsonSignIn = $rq->getParsedBody();


        $rdvInputValidator = Validator::key('email', Validator::email()->notEmpty())
            ->key('password', Validator::stringType()->notEmpty());

        try {
            //validation
            $rdvInputValidator->assert($jsonSignIn);
            //formatage

            $authDto = $this->authProvider->signin(new CredentialsDTO('', $jsonSignIn['password'], $jsonSignIn['email']));
            $rs = $rs->withHeader('access_token', $authDto->atoken);
            $this->loger->info("Sign in de l'utilisateur " .$jsonSignIn['email']);
            return JsonRenderer::render($rs, 201, []);

            return $rs;
        } catch (NestedValidationException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch (ServiceAuthUserNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        } catch (\Exception $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
            //            throw new HttpInternalServerErrorException($rq, "Erreur serveur");
        } catch (Error $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }


    }
}
