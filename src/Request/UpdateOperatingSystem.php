<?php

namespace App\Request;

use App\Entity\OperatingSystem;
use App\Exception\InvalidNameException;
use App\Model\Dockerfile\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateOperatingSystem extends PostOperatingSystem
{
    /**
     * @param Request $request
     * @return UpdateOperatingSystem
     */
    public static function fromRequest(Request $request): self
    {
        $content = json_decode($request->getContent(), true);

        return new self(
            $content['description'] ?? null,
            $content['images'] ?? null
        );
    }
}
