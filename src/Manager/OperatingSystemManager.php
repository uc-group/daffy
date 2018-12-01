<?php

namespace App\Manager;

use App\Entity\ImageConfig;
use App\Entity\OperatingSystem;
use App\Exception\EntityAlreadyExistsException;
use App\Model\Dockerfile\Image;
use App\Model\OperatingSystem\Id;
use App\Request\CreateOperatingSystem;
use App\Request\UpdateOperatingSystem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OperatingSystemManager
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
     * @param CreateOperatingSystem $request
     * @return OperatingSystem
     * @throws EntityAlreadyExistsException
     */
    public function create(CreateOperatingSystem $request): OperatingSystem
    {
        $id = Id::fromNameAndVersion($request->getName(), $request->getVersion());
        $operatingSystem = $this->find($id);

        if ($operatingSystem) {
            throw new EntityAlreadyExistsException($id->toString());
        }

        $operatingSystem = new OperatingSystem(
            $request->getName(),
            $request->getVersion(),
            $request->getPackageManager(),
            $request->getDescription()
        );

        foreach ($this->findImageConfigs($request->getImages()) as $config) {
            $operatingSystem->addImageConfig($config);
        }

        $this->entityManager->persist($operatingSystem);
        $this->entityManager->flush();

        return $operatingSystem;
    }

    /**
     * @param OperatingSystem $operatingSystem
     * @param UpdateOperatingSystem $request
     * @return OperatingSystem
     */
    public function update(OperatingSystem $operatingSystem, UpdateOperatingSystem $request): OperatingSystem
    {
        $operatingSystem->setDescription($request->getDescription());
        $imageConfigs = $this->findImageConfigs($request->getImages());
        $operatingSystem->setPackageManager($request->getPackageManager());
        $operatingSystem->updateImages($imageConfigs);
        $this->entityManager->persist($operatingSystem);
        $this->entityManager->flush();

        return $operatingSystem;
    }

    /**
     * @param string[] $ids
     */
    public function removeMany(array $ids)
    {
        $operatingSystems = $this->findMany($ids);
        foreach ($operatingSystems as $operatingSystem) {
            $operatingSystem->updateImages([]);
            $this->entityManager->remove($operatingSystem);
        }

        $this->entityManager->flush();
    }

    /**
     * @param Id $id
     * @return null|OperatingSystem|object
     */
    public function find(Id $id)
    {
        return $this->entityManager->getRepository(OperatingSystem::class)->find($id->toString());
    }

    /**
     * @param string[] $ids
     * @return array|OperatingSystem[]
     */
    public function findMany(array $ids): array
    {
        return $this->entityManager->getRepository(OperatingSystem::class)->findBy([
            'id' => $ids
        ]);
    }

    /**
     * @param Image[] $images
     * @return array|ImageConfig[]
     */
    public function findImageConfigs(array $images)
    {
        $imageMap = array_combine(array_map(function ($image) {
            return (string)$image;
        }, $images), $images);

        $configs = $this->entityManager->getRepository(ImageConfig::class)->findBy([
            'id' => array_keys($imageMap)
        ]);

        /** @var ImageConfig $config */
        foreach ($configs as $config) {
            unset($imageMap[$config->getId()]);
        }

        foreach ($imageMap as $image) {
            $config = new ImageConfig($image);
            $this->entityManager->persist($config);
            $configs[] = $config;
        }

        return $configs;
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
