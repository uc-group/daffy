<?php

namespace App\Model\Dockerfile\Stage;

interface StageIdInterface
{
    public function getName(): string;

    public function getAlias(): string;
}
