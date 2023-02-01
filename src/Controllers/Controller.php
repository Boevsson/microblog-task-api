<?php

namespace App\Controllers;

use Awurth\SlimValidation\Validator;
use Psr\Container\ContainerInterface;

abstract class Controller
{
    protected ContainerInterface $container;
    protected Validator $validator;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->validator = $container->get('validator');
    }
}