<?php

namespace App\Model\Dockerfile\Stage;

class StageId implements StageIdInterface
{
    /**
     * @var StageId
     */
    private $stage;

    /**
     * @var string
     */
    private $alias;

    /**
     * StageId constructor.
     * @param Stage $stage
     * @param string $alias
     */
    public function __construct(Stage $stage, string $alias)
    {
        $this->stage = $stage;
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->stage->getAlias();
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }
}
