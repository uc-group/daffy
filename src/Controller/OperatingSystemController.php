<?php

namespace App\Controller;

use App\Entity\ImageConfig;
use App\Entity\OperatingSystem;
use App\Exception\EntityAlreadyExistsException;
use App\Exception\InvalidNameException;
use App\Manager\OperatingSystemManager;
use App\Model\Dockerfile\Image;
use App\Model\OperatingSystem\Id;
use App\Request\CreateOperatingSystem;
use App\Request\PostOperatingSystem;
use App\Request\UpdateOperatingSystem;
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
     * @param OperatingSystemManager $manager
     * @return JsonResponse
     * @Route("/create", name="os_create", methods={"post"}, options={"expose": true})
     */
    public function create(Request $request, OperatingSystemManager $manager)
    {
        $createRequest = CreateOperatingSystem::fromRequest($request);

        $errors = $manager->validate($createRequest);
        if (!empty($errors)) {
            return new JsonResponse([
                'status' => 400,
                'message' => 'Invalid form data',
                'errors' => $errors
            ]);
        }

        try {
            $operatingSystem = $manager->create($createRequest);
        } catch (EntityAlreadyExistsException $exception) {
            return new JsonResponse([
                'status' => 400,
                'message' => 'Operating system already exists.'
            ]);
        }

        return new JsonResponse([
            'status' => 200,
            'message' => 'Operating system created.',
            'data' => $operatingSystem
        ]);
    }

    /**
     * @param Request $request
     * @param OperatingSystem $operatingSystem
     * @param OperatingSystemManager $manager
     * @return JsonResponse
     * @Route("/update/{id}", name="os_update", methods={"post"}, options={"expose": true})
     */
    public function update(Request $request, OperatingSystem $operatingSystem, OperatingSystemManager $manager)
    {
        $updateRequest = UpdateOperatingSystem::fromRequest($request);

        $errors = $manager->validate($updateRequest);
        if (!empty($errors)) {
            return new JsonResponse([
                'status' => 400,
                'message' => 'Invalid form data',
                'errors' => $errors
            ]);
        }

        $operatingSystem = $manager->update($operatingSystem, $updateRequest);

        return new JsonResponse([
            'status' => 200,
            'message' => 'Operating system updated.',
            'data' => $operatingSystem
        ]);
    }

    /**
     * @param Request $request
     * @param OperatingSystemManager $manager
     * @return JsonResponse
     * @Route("/delete", name="os_delete", methods={"post"}, options={"expose": true})
     */
    public function delete(Request $request, OperatingSystemManager $manager)
    {
        $content = json_decode($request->getContent(), true);
        $manager->removeMany($content['id'] ?? []);

        return new JsonResponse([
            'status' => 200,
            'message' => 'Operating systems removed.'
        ]);
    }
}
