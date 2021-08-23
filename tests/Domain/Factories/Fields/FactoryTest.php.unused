<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Factories\Fields;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\Factory;
use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\FactoryInterface;

/**
 * @coversDefaultClass Alphonse\CleanArchBootstrap\Domain\Factories\Fields\Factory
 */
final class FactoryTest extends TestCase
{
    private $factory;

    public function setUp(): void
    {
        $this->factory = new class extends Factory implements FactoryInterface
        {
            public function assignProperty(string $propertyName, mixed $value): self
            {
                return parent::assignProperty(
                    propertyName: $propertyName,
                    value: $value,
                );
            }

            public function getAssignedProperties(): array
            {
                return parent::getAssignedProperties();
            }

            public function hasAssignedProperty(string $propertyName): bool
            {
                return parent::hasAssignedProperty(propertyName: $propertyName);
            }

            public function genericBuild(string $class): mixed
            {
                return parent::genericBuild($class);
            }

            public function build(): self
            {
                return $this;
            }
        };
    }

    /**
     * @test
     * @covers ::assignProperty
     */
    public function returns_new_instance_on_added_property(): void
    {
        // given a new factory

        // when adding a new property to the target object
        $otherFactory = $this->factory->assignProperty(
            propertyName: 'name',
            value: 'value'
        );

        // then both instances should be different
        $this->assertNotSame(
            expected: $this->factory,
            actual: $otherFactory,
            message: "Factory should return a new instance when a property is added to the target object"
        );
    }

    /**
     * @test
     * @covers ::getAssignedProperties
     */
    public function returns_assigned_properties(): void
    {
        // given a new factory

        // when adding a new property to the target object
        $this->factory = $this->factory->assignProperty(
            propertyName: 'name',
            value: 'value'
        );

        // then the property should be assigned
        $assignedProperties = $this->factory->getAssignedProperties();
        $propertyWasAssigned = in_array(needle: 'name', haystack: array_keys($assignedProperties));
        $this->assertTrue(
            condition: $propertyWasAssigned,
            message: "Factory didn't assign the property 'name'"
        );
    }

    /**
     * @test
     * @covers ::hasAssignedProperty
     */
    public function detects_assigned_properties(): void
    {
        // given a new factory

        // when adding a property to the target object
        $this->factory = $this->factory->assignProperty(
            propertyName: 'name',
            value: 'value'
        );

        // then the property should be assigned
        $this->assertTrue(
            condition: $this->factory->hasAssignedProperty(propertyName: 'name'),
            message: "The property 'name' was not assigned"
        );
    }

    /**
     * @test
     * @covers ::genericBuild
     */
    public function instantiate_target_class(): void
    {
        // given a factory and a target class
        $targetClass = self::class;

        // when requesting an instance of the target class
        $instance = $this->factory->genericBuild(class: $targetClass);

        // then the created instance should have the appropriate class
        $instantiatedClass = $instance::class;
        $correctClassWasInstantiated = ($targetClass === $instantiatedClass);
        $this->assertTrue(
            condition: $correctClassWasInstantiated,
            message: "Expected the factory to instantiante '{$targetClass}', got '{$instantiatedClass}'"
        );
    }
}
