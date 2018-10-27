<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/dockerfile")
 */
class DockerfileController extends Controller
{
    /**
     * @Route("/", name="dockerfile")
     */
    public function index()
    {
        return $this->render('dockerfile/index.html.twig');
    }
}
