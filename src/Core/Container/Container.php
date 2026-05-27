<?php

namespace Src\Core\Container;

use ReflectionClass;
use ReflectionParameter;
use Exception;

class Container
{
    private array $instances = [];

    public function get(
        string $class
    )
    {
        if (
            isset($this->instances[$class])
        ) {

            return $this->instances[$class];
        }

        if (
            !class_exists($class)
        ) {

            throw new Exception(
                "Class {$class} not found"
            );
        }

        $reflection =
            new ReflectionClass($class);

        $constructor =
            $reflection->getConstructor();

        if (!$constructor) {

            $instance =
                new $class();

            $this->instances[$class] =
                $instance;

            return $instance;
        }

        $dependencies = [];

        foreach (
            $constructor->getParameters()
            as $parameter
        ) {

            $dependencies[] =
                $this->resolveDependency(
                    $parameter
                );
        }

        $instance =
            $reflection->newInstanceArgs(
                $dependencies
            );

        $this->instances[$class] =
            $instance;

        return $instance;
    }

    private function resolveDependency(
        ReflectionParameter $parameter
    )
    {
        $type =
            $parameter->getType();

        if (!$type) {

            throw new Exception(
                "Cannot resolve dependency"
            );
        }

        return $this->get(
            $type->getName()
        );
    }
}