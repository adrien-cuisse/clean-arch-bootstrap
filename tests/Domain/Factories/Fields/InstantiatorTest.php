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

    public function setUp(): void
    {
        $this->instantiator = $this->createRealInstantiator();
    }

    /**
     * @test
     * @covers ::assignConstructorArgument
     */
    public function returns_new_instance_on_added_constructor_argument(): void
    {
        // given a constructor argument
        $argumentName = 'name';
        $argumentValue = '';

        // when adding it to the constructor of the target class
        $otherInstantiator = $this->instantiator->assignConstructorArgument($argumentName, $argumentValue);

        // then a different instantiator should be returned
        $instantiatorsAreDifferent = ($this->instantiator !== $otherInstantiator);
        $this->assertTrue(
            condition: $instantiatorsAreDifferent,
            message: "Instantiator should return a new instance when a new constructor argument is added",
        );
    }

    /**
     * @test
     * @covers ::assignConstructorArgument
     */
    public function assigns_constructor_named_argument(): void
    {
        // given a constructor argument
        $argumentName = 'name';
        $argumentValue = '';

        // when adding it to the constructor of the target class
        $this->instantiator = $this->instantiator->assignConstructorArgument($argumentName, $argumentValue);

        // then the argument should be assigned
        $assignedConstructorArguments = $this->instantiator->assignedConstructorArguments();
        $this->assertArrayHasKey(
            key: $argumentName,
            array: $assignedConstructorArguments,
            message: "Instantiator didn't assign the argument '{$argumentName}'",
        );
    }

    /**
     * @test
     * @covers ::assignConstructorArgument
     */
    public function assigns_proper_value_to_constructor_named_argument(): void
    {
        // given a constructor argument
        $argumentName = 'name';
        $argumentValue = 'value';

        // when adding it to the constructor of the target class
        $this->instantiator = $this->instantiator->assignConstructorArgument($argumentName, $argumentValue);

        // then the argument should be assigned with the correct value
        $assignedConstructorArguments = $this->instantiator->assignedConstructorArguments();
        $assignedArgumentValue = $assignedConstructorArguments[$argumentName] ?? null;
        $this->assertSame(
            expected: $argumentValue,
            actual: $assignedArgumentValue,
            message: "Instantiator didn't assign proper value to the argument '{$argumentName}', expected '{$argumentValue}', got '{$assignedArgumentValue}'",
        );
    }

    /**
     * @test
     * @covers ::assignedConstructorArguments
     */
    public function returns_assigned_arguments(): void
    {
        // given a constructor argument
        $argumentName = 'name';
        $argumentValue = 'value';

        // when adding it to the constructor of the target class
        $this->instantiator = $this->instantiator->assignConstructorArgument($argumentName, $argumentValue);

        // then the assigned arguments list should contain the assigned argument
        $assignedConstructorArguments = $this->instantiator->assignedConstructorArguments();
        $this->assertSame(
            expected: [$argumentName => $argumentValue],
            actual: $assignedConstructorArguments,
            message: "Argument ['{$argumentName}' => '{$argumentValue}'] doesn't appear in constructor arguments list",
        );
    }

    /**
     * @test
     * @covers ::hasAssignedConstructorArgument
     */
    public function detects_assigned_arguments(): void
    {
        // given a constructor argument
        $argumentName = 'name';
        $argumentValue = '';

        // when adding it to the constructor of the target class
        $instantiator = $this->instantiator->assignConstructorArgument($argumentName, $argumentValue);

        // then the argument should be assigned
        $argumentWasAssigned = $instantiator->hasAssignedConstructorArgument($argumentName);
        $this->assertTrue(
            condition: $argumentWasAssigned,
            message: "Instantiator failed to detect that the named argument '{$argumentName}' was assigned",
        );
    }

    /**
     * @test
     * @covers ::instantiate
     */
    public function instantiates_target_class(): void
    {
        // given  a target class
        $targetClass = self::class;

        // when creating an instance of it
        $instance = $this->instantiator->instantiate($targetClass);

        // then the created instance should have the appropriate class
        $instantiatedClass = $instance::class;
        $this->assertInstanceOf(
            expected: $targetClass,
            actual: $instance,
            message: "Expected the instantiator to instantiante '{$targetClass}', got '{$instantiatedClass}'",
        );
    }
}
