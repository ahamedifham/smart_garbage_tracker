<?php
/**
 * Created by PhpStorm.
 * User: Janith
 * Date: 8/17/2016
 * Time: 12:03 PM
 */

namespace AppBundle\Controller\Mobile;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
/**
 * @Route("/oauth/v2")
 */
class SecurityController extends BaseController
{
    /**
     * @Route("/auth/login", name="app_api_login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'backend/login/login_temp.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
                'page_title'=>'login'
            )
        );
    }

    /**
     * @Route("/auth/token_return", name="app_api_login_token")
     */
    public function returnTokenAction(){
        $clientManager = $this->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris(array(null));
        $client->setAllowedGrantTypes(['client_credentials']);
        $clientManager->updateClient($client);
        return $this->redirect($this->generateUrl('fos_oauth_server_token',array(
            'client_id'     => $client->getPublicId(),
            'client_secret'     => $client->getSecret(),
            'grant_type'=>'client_credentials',
        )));

    }
}

