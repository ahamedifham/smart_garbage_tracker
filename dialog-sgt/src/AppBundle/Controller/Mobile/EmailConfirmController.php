<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 10/31/16
 * Time: 5:14 PM
 */

namespace AppBundle\Controller\Mobile;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EmailConfirmController extends APIBaseController
{
    /**
     * @Route("user/email_confirm/{emailConfirmCode}", name="app_user_email_confirm")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function emailConfirmationAction(Request $request, $emailConfirmCode){


        $em = $this->getDoctrine()->getManager();
        $commonUserRepo = $em->getRepository('AppBundle:CommonUser');
        $user= $commonUserRepo->profileFromEmail($emailConfirmCode);
        $user->setEmailConfirm(1);

        $em->flush();

        return new Response('Thank you for registering with us. You can log in now',200);
    }
}