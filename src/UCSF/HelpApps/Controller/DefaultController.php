<?php

namespace UCSF\HelpApps\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class DefaultController
{
    public function ipNetVerifyAction(Request $request, Application $app)
    {
        $ipAddress = $request->get('ip_add', $request->getClientIp());
        $verifier = $app['ucsf.helpapps.services.ip_net_verifier'];
        $location = $verifier->getLocation($ipAddress);
        return $app['twig']->render('helpapps/ipnetverify.twig', array(
                'ip_address' => $ipAddress,
                'location' => $location
            )
        );
    }
}
