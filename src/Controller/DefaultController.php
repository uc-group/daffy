<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Grzegorz Balcewicz <grzegorz@balcewicz.pl>
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="app")
     */
    public function app()
    {
        return $this->render('default/app.html.twig');
    }
}
