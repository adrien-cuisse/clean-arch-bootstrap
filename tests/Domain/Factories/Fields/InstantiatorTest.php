<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Factories\Fields;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Fields\CreatesInstantiator;

/**
 * @coversDefaultClass Alphonse\CleanArchBootstrap\Domain\Factories\Fields\Instantiator
 */
final class InstantiatorTest extends TestCase
{
    use CreatesInstantiator;

    /**
     * @test
     * @covers ::assignConstructorArgument
     */
    public function returns_new_instance_on_added_constructor_argument(): void
    {
        // given a new instantiator
        $instantiator = $this->createRealInstantiator();

        // when adding a new argument to the constructor
        $otherInstantiator = $instantiator->assignConstructorArgument(
            name: 'name',
            value: 'value'
        );

        // then both instances should be different
        $instancesAreDifferent = ($instantiator !== $otherInstantiator);
        $this->assertTrue(
            condition: $instancesAreDifferent,
            message: "Instantiator should return a new instance when a new constructor argument is added"
        );
    }

    /**
     * @test
     * @covers ::getAssignedConstructorArguments
     */
    public function returns_assigned_arguments(): void
    {
        // given a new instantiator
        $instantiator = $this->createRealInstantiator();

        // when adding a new argument to the constructor
        $instantiator = $instantiator->assignConstructorArgument(
            name: 'name',
            value: 'value'
        );

        // then the argument should be assigned
        $assignedConstructorArguments = $instantiator->getAssignedConstructorArguments();
        $assignedConstructorArgumentsName = array_keys($assignedConstructorArguments);
        $argumentWasAssigned = in_array(needle: 'name', haystack: $assignedConstructorArgumentsName);
        $this->assertTrue(
            condition: $argumentWasAssigned,
            message: "Instantiator didn't assign the argument 'name'"
        );
    }

    /**
     * @test
     * @covers ::hasAssignedConstructorArgument
     */
    public function detects_assigned_arguments(): void
    {
        // given a new instantiator
        $instantiator = $this->createRealInstantiator();

        // when adding an argument to the constructor
        $instantiator = $instantiator->assignConstructorArgument(
            name: 'name',
            value: 'value'
        );

        // then the argument should be assigned
        $argumentWasAssigned = $instantiator->hasAssignedConstructorArgument(name: 'name');
        $this->assertTrue(
            condition: $argumentWasAssigned,
            message: "The argument 'name' was not assigned"
        );
    }

    /**
     * @test
     * @covers ::createInstance
     */
    public function instantiate_target_class(): void
    {
        // given a new instantiator and a target class
        $instantiator = $this->createRealInstantiator();
        $targetClass = self::class;

        // when requesting an instance of the target class
        $instance = $instantiator->createInstance(class: $targetClass);

        // then the created instance should have the appropriate class
        $instantiatedClass = $instance::class;
        $correctClassWasInstantiated = ($targetClass === $instantiatedClass);
        $this->assertTrue(
            condition: $correctClassWasInstantiated,
            message: "Expected the instantiator to instantiante '{$targetClass}', got '{$instantiatedClass}'"
        );
    }
}
