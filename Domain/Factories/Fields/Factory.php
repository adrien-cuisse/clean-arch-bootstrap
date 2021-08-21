<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

abstract class Factory implements FactoryInterface
{
    /**
     * @var array<string=>mixed> - map from properties name to properties value
     */
    private array $propertiesNameToValueMapping = [];

    /**
     * @param string $propertyName - the name of the property to assign, must match with
     *      the argument of the target class' constructor
     * @param mixed $value - the value to assign
     */
    protected function assignProperty(string $propertyName, mixed $value): FactoryInterface
    {
        $factory = clone $this;
        $factory->propertiesNameToValueMapping[$propertyName] = $value;

        return $factory;
    }

    /**
     * @return array<string,mixed> - map of so far assigned properties name to properties value
     */
    protected function getAssignedProperties(): array
    {
        return $this->propertiesNameToValueMapping;
    }

    /**
     * @param string $propertyName - the name of the property to check for assignment
     *
     * @return bool - true if the property has been assigned, false otherwise
     */
    protected function hasAssignedProperty(string $propertyName): bool
    {
        return isset($this->propertiesNameToValueMapping[$propertyName]);
    }

    /**
     * @param string $class - the class to build
     *
     * @return mixed - a new instance of the target class
     */
    protected function genericBuild(string $class): mixed
    {
        return new $class(...$this->propertiesNameToValueMapping);
    }
}
