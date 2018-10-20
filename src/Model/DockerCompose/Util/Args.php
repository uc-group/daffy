<?php

namespace App\Model\DockerCompose\Util;

class Args
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param null|string $key
     * @return array
     */
    public function getData($key = null): array
    {
        if ($key !== null) {
            return $this->data[$key] ?? null;
        }

        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function addData($key, $value): void
    {
        $this->data[$key] = $value;
    }

    //TODO: Method that will generate args in yaml syntax, where we could specify if we want a key-value or just a value syntax
}