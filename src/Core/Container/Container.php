<?php

namespace Src\Core\Container;

use ReflectionClass;
use ReflectionParameter;

class Container
{
    private array $bindings = [];

    /*
    |--------------------------------------------------------------------------
    | Register Singleton
    |--------------------------------------------------------------------------
    */

    public function singleton(
        string $abstract,
        callable $resolver
    ): void {

        $this->bindings[$abstract] =
            $resolver;
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Class
    |--------------------------------------------------------------------------
    */

    public function make(
        string $abstract
    ): mixed {

        /*
        |--------------------------------------------------------------------------
        | Bound Resolver
        |--------------------------------------------------------------------------
        */

        if (
            isset(
                $this->bindings[$abstract]
            )
        ) {

            return $this->bindings[$abstract]();
        }

        /*
        |--------------------------------------------------------------------------
        | Reflection
        |--------------------------------------------------------------------------
        */

        $reflection =
            new ReflectionClass(
                $abstract
            );

        /*
        |--------------------------------------------------------------------------
        | No Constructor
        |--------------------------------------------------------------------------
        */

        $constructor =
            $reflection
                ->getConstructor();

        if (!$constructor) {

            return new $abstract();
        }

        /*
        |--------------------------------------------------------------------------
        | Resolve Dependencies
        |--------------------------------------------------------------------------
        */

        $dependencies = array_map(

            function (
                ReflectionParameter $parameter
            ) {

                $type =
                    $parameter
                        ->getType();

                if (!$type) {

                    throw new \Exception(

                        'Cannot resolve dependency'

                    );
                }

                return $this->make(
                    $type->getName()
                );
            },

            $constructor->getParameters()

        );

        /*
        |--------------------------------------------------------------------------
        | Create Instance
        |--------------------------------------------------------------------------
        */

        return $reflection
            ->newInstanceArgs(
                $dependencies
            );
    }
}