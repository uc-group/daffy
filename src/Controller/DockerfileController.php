<?php

namespace App\Controller;

use App\Builder\DockerfileBuilder;
use App\Entity\DockerfileConfig;
use App\Entity\ImageConfig;
use App\Entity\OperatingSystem;
use App\Manager\DockerfileManager;
use App\Model\Dockerfile\Image;
use App\Request\CreateDockerfileConfig;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
        $images = $this->getDoctrine()->getRepository(ImageConfig::class)->findAllAsArray();

        return $this->render('dockerfile/index.html.twig', [
            'images' => $images
        ]);
    }

    /**
     * @Route("/list", name="dockerfile_list", options={"expose": true})
     * @param DockerfileManager $manager
     * @return JsonResponse
     */
    public function list(DockerfileManager $manager)
    {
        return new JsonResponse($manager->list());
    }

    /**
     * @Route("/create", name="dockerfile_create", options={"expose": true})
     * @param Request $request
     * @param DockerfileManager $manager
     * @return JsonResponse
     * @throws \Exception
     */
    public function create(Request $request, DockerfileManager $manager)
    {
        $createRequest = CreateDockerfileConfig::fromRequest($request);

        $errors = $manager->validate($createRequest);
        if (!empty($errors)) {
            return new JsonResponse([
                'status' => 400,
                'message' => 'Invalid form data',
                'errors' => $errors
            ]);
        }

        $dockerfileConfig = $manager->create($createRequest);

        return new JsonResponse([
            'status' => 200,
            'message' => 'Dockerfile config created.',
            'data' => $dockerfileConfig
        ]);
    }

    /**
     * @param DockerfileConfig $config
     * @param DockerfileManager $manager
     * @Route("/remove/{id}", name="dockerfile_remove", options={"expose": true})
     * @return JsonResponse
     */
    public function remove(DockerfileConfig $config, DockerfileManager $manager)
    {
        $manager->remove($config);

        return new JsonResponse([
            'status' => 200,
            'message' => 'Dockerfile config removed.'
        ]);
    }

    /**
     * @param DockerfileConfig $config
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/view/{id}", name="dockerfile_view", options={"expose": true})
     */
    public function view(DockerfileConfig $config)
    {
        $configData = $config->jsonSerialize();
        $configData['layers'] = [];
        foreach ($config->getLayersDefinition() as $definition) {
            $configData['layers'][] = $definition->toArray();
        }

        return $this->render('dockerfile/view.html.twig', [
            'config' => $configData,
            'stages' => $config->getStages()
        ]);
    }

    /**
     * @param DockerfileConfig $config
     * @param DockerfileBuilder $builder
     * @return JsonResponse
     * @throws \Exception
     * @Route("/build/{id}", name="dockerfile_build", options={"expose": true})
     */
    public function build(DockerfileConfig $config, DockerfileBuilder $builder)
    {
        return new JsonResponse((string)$builder->build($config));
    }

    /**
     * @param Request $request
     * @param DockerfileConfig $config
     * @param DockerfileManager $manager
     * @param DockerfileBuilder $builder
     * @return JsonResponse
     * @throws \Exception
     * @Route("/save/{id}", name="dockerfile_save", options={"expose": true})
     */
    public function save(Request $request, DockerfileConfig $config, DockerfileManager $manager, DockerfileBuilder $builder)
    {
        $data = json_decode($request->getContent(), true);
        $config->setLayersDefinition($data['layersConfig']);
        $manager->save($config);

        return $this->build($config, $builder);
    }
}
