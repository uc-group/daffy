<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/settings")
 */
class SettingsController extends Controller
{
    /**
     * @return Response
     * @Route("/os", name="settings_os")
     */
    public function os()
    {
        return $this->render('settings/os.html.twig');
    }
}
