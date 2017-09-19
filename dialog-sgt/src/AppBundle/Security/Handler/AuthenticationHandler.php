<?php
namespace AppBundle\Security\Handler;
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 9/15/16
 * Time: 10:44 AM
 */
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class AuthenticationHandler implements AuthenticationSuccessHandlerInterface,AuthenticationFailureHandlerInterface
{
    protected $fosOAuthClientManager;
    protected $router;
    protected $doctrineORM;
    public function __construct($fosOAuthClientManager,$router,$doctrineORM)
    {
        $this->fosOAuthClientManager = $fosOAuthClientManager;
        $this->router = $router;
        $this->doctrineORM = $doctrineORM;
    }

    /**
     * @param $data
     * @return Response
     */
    protected function handleResponse($data){
        $response =  new Response(json_encode($data));
        $responseHeaders = $response->headers;
        $responseHeaders->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        $responseHeaders->set('content-type', 'application/json');
        $responseHeaders->set('accept', 'application/json');
//        $responseHeaders->set('Access-Control-Allow-Origin', '*');
        $responseHeaders->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
        return $response;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @return Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $clientManager = $this->fosOAuthClientManager;
        $client = $clientManager->createClient();
        $client->setRedirectUris(array(null));
        $client->setAllowedGrantTypes(['client_credentials']);
        //$client->setUser($token->getUser());
        $clientManager->updateClient($client);

        if (!$request->isXmlHttpRequest())
        {

            $routes = array();
            $routesAliases = array(
                'app_api_user_register',
                'app_api_user_get_routes'
            );
            foreach ($routesAliases as $routesAlias)
                $routes[$routesAlias] = $this->router->generate($routesAlias,array(),true);

            //var_dump(intval($token->getUser()->getRegistrationStatus()));

            $responseObj = (object)array(
                'status'=>true,
                'errors'=>array(),
                'data'=>(object)array(
                    'userId'=>intval($token->getUser()->getId()),
                    'registrationStatus'=>intval($token->getUser()->getRegistrationStatus()),
                    'auth'=>(object)array(
                        'client_id'     => $client->getPublicId(),
                        'client_secret'     => $client->getSecret(),
                        'grant_type'=>'client_credentials',
                        'token_url'=>$this->router->generate('fos_oauth_server_token',array(),true)
                    ),
                    'config'=>(object)array(
                        'select' => (object)array(
                            'fieldName1'=>array(),
                            'fieldName2'=>array(),
                        ),
                        'route' => (object)array(
                            'app_api_user_register'=>  $this->router->generate('app_api_user_register',array(),true),
                            'app_api_user_get_routes'=> $this->router->generate('app_api_user_get_routes',array(),true),
                            'app_api_user_set_route'=> $this->router->generate('app_api_user_set_route',array(),true),
                            'app_api_user_vehicle_tracking_initialize'=> $this->router->generate('app_api_user_vehicle_tracking_initialize',array(),true),
                            'app_api_user_vehicle_location'=> $this->router->generate('app_api_user_vehicle_location',array(),true),
                            'app_api_user_profile'=> $this->router->generate('app_api_user_profile',array(),true),
                            'app_api_user_profile_update'=> $this->router->generate('app_api_user_profile_update',array(),true),
                            'app_api_user_raise_complaint'=> $this->router->generate('app_api_user_raise_complaint',array(),true),
                            'app_api_user_collection_history'=> $this->router->generate('app_api_user_collection_history',array(),true),
                            'app_api_user_set_dump_point'=> $this->router->generate('app_api_user_set_dump_point',array(),true),
                            'app_api_user_get_all_routes'=> $this->router->generate('app_api_user_get_all_routes',array(),true),
                            'app_api_user_view_route'=> $this->router->generate('app_api_user_view_route',array(),true),
                            'app_api_user_set_user_route'=> $this->router->generate('app_api_user_set_user_route',array(),true),
                            'app_api_user_set_user_feedback'=> $this->router->generate('app_api_user_set_user_feedback',array(),true),
                            'app_api_user_set_user_package'=> $this->router->generate('app_api_user_set_user_package',array(),true),
                            'app_api_user_get_current_location'=> $this->router->generate('app_api_user_get_current_location',array(),true),
                            'app_api_user_set_gcm'=> $this->router->generate('app_api_user_set_gcm',array(),true),
                            'app_api_user_set_dump_point_and_auto_pick_route'=> $this->router->generate('app_api_user_set_dump_point_and_auto_pick_route',array(),true),
                            'app_api_user_set_user_history'=> $this->router->generate('app_api_user_set_user_history',array(),true),
                            'app_api_user_get_user_history'=> $this->router->generate('app_api_user_get_user_history',array(),true),
                            'app_api_user_edit_user_history'=> $this->router->generate('app_api_user_edit_user_history',array(),true),
                            'app_api_user_get_user_location_center'=> $this->router->generate('app_api_user_get_user_location_center',array(),true),
                            'app_api_user_get_user_complaints'=> $this->router->generate('app_api_user_get_user_complaints',array(),true),
                        )
                    )

                )
            );
            return $this->handleResponse($responseObj);
        } else {
            // TODO Handle non XmlHttp request here
        }
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if (!$request->isXmlHttpRequest()) {
            $responseObj = (object)array(
                'status'=>false,
                'errors'=>array(),

            );
            return $this->handleResponse($responseObj);
        } else {
            // TODO Handle non XmlHttp request here
        }
    }
}