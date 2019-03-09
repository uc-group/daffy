<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/docker-compose")
 */
class DockerComposeController extends Controller
{
    /**
     * @Route("/", name="docker_compose")
     */
    public function index()
    {
        return $this->render('docker_compose/index.html.twig');
    }
}
