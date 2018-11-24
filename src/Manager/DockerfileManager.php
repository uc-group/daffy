<?php

namespace App\Manager;


use App\Entity\DockerfileConfig;
use App\Repository\DockerfileConfigRepository;
use App\Request\CreateDockerfileConfig;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DockerfileManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @return mixed
     */
    public function list()
    {
        /** @var DockerfileConfigRepository $repository */
        $repository = $this->entityManager->getRepository(DockerfileConfig::class);

        return $repository->findAllAsArray();
    }

    /**
     * @param CreateDockerfileConfig $request
     * @return DockerfileConfig
     * @throws \Exception
     */
    public function create(CreateDockerfileConfig $request)
    {
        $entity = new DockerfileConfig($request->getBaseImage(), $request->getName(), $request->getDescription());
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * @param DockerfileConfig $config
     */
    public function remove(DockerfileConfig $config)
    {
        $this->entityManager->remove($config);
        $this->entityManager->flush();
    }

    /**
     * @param DockerfileConfig $config
     */
    public function save(DockerfileConfig $config)
    {
        $this->entityManager->persist($config);
        $this->entityManager->flush();
    }

    /**
     * @param $requestObject
     * @return array
     */
    public function validate($requestObject): array
    {
        $errorList = [];
        $errors = $this->validator->validate($requestObject);
        if ($errors->count() > 0) {
            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $errorList[$error->getPropertyPath()] = $error->getMessage();
            }
        }

        return $errorList;
    }
}
