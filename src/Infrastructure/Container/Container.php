<?php

namespace Src\Infrastructure\Container;

class Container
{
    private array $bindings = [];

    private array $instances = [];

    public function bind(
        string $abstract,
        callable $factory
    ): void {

        $this->bindings[$abstract] = $factory;
    }

    public function singleton(
        string $abstract,
        callable $factory
    ): void {

        $this->instances[$abstract] = $factory();
    }

    public function get(
        string $abstract
    ): mixed {

        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (isset($this->bindings[$abstract])) {

            return $this->bindings[$abstract]();
        }

        throw new \Exception(
            "Class {$abstract} not found."
        );
    }
}