<?php

namespace App\Service;

use Psr\Container\ContainerInterface;

abstract class Service
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}