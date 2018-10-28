<?php

namespace App\Controller;

use App\Entity\OperatingSystem;
use App\Model\OperatingSystem\Id;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/os")
 */
class OperatingSystemController extends Controller
{

    /**
     * @Route("/list", name="os_list", options={"expose": true})
     * @return JsonResponse
     */
    public function osList()
    {
        $list = $this->getDoctrine()->getRepository(OperatingSystem::class)->findAll();

        return new JsonResponse($list);
    }

    /**
     * @param Request $request
     * @Route("/create", name="os_create", methods={"post"}, options={"expose": true})
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        //TODO: validate

        $id = Id::fromNameAndVersion($content['name'], $content['version']);
        $operatingSystem = $this->getDoctrine()->getRepository(OperatingSystem::class)->find($id->toString());
        if ($operatingSystem) {
            return new JsonResponse([
                'status' => 400,
                'message' => 'Operating system already exists.'
            ]);
        }

        $operatingSystem = new OperatingSystem($id, $content['description'] ?? null);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($operatingSystem);
        $manager->flush();

        return new JsonResponse([
            'status' => 200,
            'message' => 'Operating system created.',
            'data' => $operatingSystem
        ]);
    }
}
