<?php

namespace App\Model\Dockerfile\Stage;

use App\Model\Dockerfile\Image;

class ImageId implements StageIdInterface
{
    /**
     * @var Image
     */
    private $image;
    /**
     * @var string
     */
    private $alias;

    /**
     * @param Image $image
     * @param string|null $alias
     */
    public function __construct(Image $image, string $alias)
    {
        $this->image = $image;
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->image;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }
}
