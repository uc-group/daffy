<?php

namespace App\Controller;

use App\Model\Dockerfile\PackageManager\PackageManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/settings")
 */
class SettingsController extends Controller
{
    /**
     * @param PackageManagerRegistry $registry
     * @return Response
     * @Route("/os", name="settings_os")
     */
    public function os(PackageManagerRegistry $registry, TranslatorInterface $translator)
    {
        return $this->render('settings/os.html.twig', [
            'packageManagers' => $registry->all()
        ]);
    }
}
