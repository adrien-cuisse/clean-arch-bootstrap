<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\Identity\Uuid;

use Generator;
use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid\Uuid;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid\UuidInterface;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid\InvalidUuidVersionException;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid\InvalidUuidNodeBytesCountException;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid\InvalidUuidStringException;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid\InvalidUuidTimestampLowBytesCountException;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid\InvalidUuidTimestampMidBytesCountException;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid\InvalidUuidTimestampHighBytesCountException;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects\CreatesDummyValueObject;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid\Uuid
 */
final class UuidTest extends TestCase
{
    use CreatesDummyValueObject;

    /**
     * @return UuidInterface - an instance to test
     */
    private function createInstance(
        int $version = 0,
        array $timestampLowBytes = [0, 0, 0, 0],
        array $timestampMidBytes = [0, 0],
        array $timestampHighBytes = [0, 0],
        int $clockSequenceHighByte = 0,
        int $clockSequenceLowByte = 0,
        array $nodeBytes = [0, 0, 0, 0, 0, 0],
    ): UuidInterface {
        return new class(
            $version,
            $timestampLowBytes,
            $timestampMidBytes,
            $timestampHighBytes,
            $clockSequenceHighByte,
            $clockSequenceLowByte,
            $nodeBytes,
        ) extends Uuid {
            public function __construct(
                int $version,
                array $timestampLowBytes,
                array $timestampMidBytes,
                array $timestampHighBytes,
                int $clockSequenceHighByte,
                int $clockSequenceLowByte,
                array $nodeBytes,
            ) {
                parent::__construct(
                    $version,
                    $timestampLowBytes,
                    $timestampMidBytes,
                    $timestampHighBytes,
                    $clockSequenceHighByte,
                    $clockSequenceLowByte,
                    $nodeBytes,
                );
            }

            public static function fromString(string $uuidString): static
            {
                return parent::fromString($uuidString);
            }
        };
    }

    public function valueObjectProvider(): Generator
    {
        $constructorArguments = [
            'version' => 0,
            'timestampLowBytes' => [0, 0, 0, 0],
            'timestampMidBytes' => [0, 0],
            'timestampHighBytes' => [0, 0],
            'clockSequenceHighByte' => 0,
            'clockSequenceLowByte' => 0,
            'nodeBytes' => [0, 0, 0, 0, 0, 0],
        ];

        $uuid = $this->createInstance(...$constructorArguments);

        yield 'not an uuid' => [
            $uuid,
            $this->createDummyValueObject(),
            false
        ];

        // should not match if version of any byte differs...
        foreach ($constructorArguments as $argumentName => $argumentValue) {
            $argumentsCopy = json_decode(json_encode($constructorArguments), true);
            $argumentType = gettype($argumentsCopy[$argumentName]);

            if ($argumentType === 'array') {
                // if array of bytes, modify each of them
                for ($bytePosition = 0; $bytePosition < count($argumentValue); $bytePosition++) {
                    $argumentsCopy2 = json_decode(json_encode($argumentsCopy), true);
                    $argumentsCopy2[$argumentName][$bytePosition]++;
                    $differentUuid = $this->createInstance(...$argumentsCopy2);
                    yield "different {$argumentName} #{$bytePosition}" => [
                        $uuid,
                        $differentUuid,
                        false
                    ];
                }
            } else if ($argumentType === 'int') {
                // if argument is a byte, just change it
                $argumentsCopy[$argumentName]++;
                $differentUuid = $this->createInstance(...$argumentsCopy);
                yield "different {$argumentName}" => [
                    $uuid,
                    $differentUuid,
                    false
                ];
            }
        }

        // ...except for unused bits
        $argumentsCopy = json_decode(json_encode($constructorArguments), true);
        $argumentsCopy['timestampHighBytes'][0] |= 0b1111_0000;
        yield 'same uuid with different (unused) 4 least significant bits of timestamp high' => [
            $uuid,
            $this->createInstance(...$argumentsCopy),
            true
        ];

        yield 'same uuid' => [
            $uuid,
            $this->createInstance(...$constructorArguments),
            true
        ];
    }

    /**
     * @test
     * @dataProvider valueObjectProvider
     */
    public function matches_same_rfc_uuid_string(UuidInterface $uuid, ValueObjectInterface $other, bool $expectedEquality): void
    {
        // given a value object to compare with

        // when comparing the 2 instances
        $areSameValue = $uuid->equals($other);

        // when it should match the expected equality
        $this->assertSame(
            expected: $expectedEquality,
            actual: $areSameValue,
        );
    }

    public function invalidBytesCountProvider(): Generator
    {
        yield [
            ['timestampLowBytes' => []],
            InvalidUuidTimestampLowBytesCountException::class,
            'timestamp-low bytes',
        ];
        yield [
            ['timestampMidBytes' => []],
            InvalidUuidTimestampMidBytesCountException::class,
            'timestamp-mid bytes',
        ];
        yield [
            ['timestampHighBytes' => []],
            InvalidUuidTimestampHighBytesCountException::class,
            'timestamp-high bytes',
        ];
        yield [
            ['nodeBytes' => []],
            InvalidUuidNodeBytesCountException::class,
            'node bytes',
        ];
    }

    /**
     * @test
     * @testdox Rejects invalid $bytesName count
     * @dataProvider invalidBytesCountProvider
     */
    public function rejects_invalid_bytes_count(array $invalidBytes, string $expectedExceptionClass, string $bytesName): void
    {
        $this->expectException($expectedExceptionClass);

        // given an Uuid with invalid bytes count
        $this->createInstance(...$invalidBytes);

        // then instantiation should be rejected
    }

    /**
     * @test
     */
    public function expects_4_bits_version(): void
    {
        $this->expectException(InvalidUuidVersionException::class);

        // given some uuid with version taking more than 4 bits
        $this->createInstance(0b1111_1111);

        // then instantation should be rejected
    }

    /**
     * @test
     * @testdox Interlops version in timestamp-high MSB
     */
    public function interlops_version_in_time_high_MSB(): void
    {
        // given some Uuid
        $uuid = $this->createInstance(version: 0b0000_0101, timestampHighBytes: [0b0000_1010, 0x0000_0110]);

        // when extracting its byte 6
        $seventhByteHexaString = substr($uuid, offset: 14, length: 2);
        sscanf($seventhByteHexaString, '%2x', $seventhByte);

        // then version should be interloped with timestamp-high MSB
        $this->assertSame(
            expected: 0b0101_1010,
            actual: $seventhByte,
            message: "Uuid should interlop version in timestamp-high MSB",
        );
    }

    /**
     * @test
     * @testdox Interlops RFC variant in clock-sequence-high byte
     */
    public function interlops_RFC_variant_in_clock_seq_high(): void
    {
        // given some Uuid
        $uuid = $this->createInstance(clockSequenceHighByte: 0b0011_1111);

        // when extracting its byte 8
        $ninthByteHexaString = substr($uuid, offset: 19, length: 2);
        sscanf($ninthByteHexaString, '%2x', $ninthByte);

        // then variant should be interloped with clock-sequence-high
        $this->assertSame(
            expected: 0b1011_1111,
            actual: $ninthByte,
            message: "Uuid should interlop variant in clock-sequence-high",
        );
    }

    /**
     * @test
     * @testdox Has RFC-compliant string-representation
     */
    public function has_rfc_compliant_representation(): void
    {
        // given a new Uuid
        $uuid = $this->createInstance();

        // when using its RFC representation
        $representation = $uuid->toRfcUuidString();

        // then it should have RFC compliant format
        $rfcUuidPattern = '/^[[:xdigit:]]{8}-([[:xdigit:]]{4}-){3}[[:xdigit:]]{12}$/';
        $this->assertMatchesRegularExpression(
            pattern: $rfcUuidPattern,
            string: $representation,
            message: "The UUID doesn't comply with RFC representation 01234567-89ab-cdef-0123-456789abcdef",
        );
    }

    public function byteProvider(): Generator
    {
        $bytes = [
            'version' => 0b1111,
            'timestampLowBytes' => [0xde, 0xaf, 0xba, 0xbe],
            'timestampMidBytes' => [0xde, 0xad],
            'timestampHighBytes' => [0xbe, 0xef],
            'clockSequenceHighByte' => 0x80,
            'clockSequenceLowByte' => 0x00,
            'nodeBytes' => [0xc0, 0xff, 0xee, 0xba, 0x0b, 0xab],
        ];

        yield 'timestamp-low byte 0' => [
            $bytes,
            'timestamp-low byte 0',
            $bytes['timestampLowBytes'][0],
            0
        ];
        yield 'timestamp-low byte 1' => [
            $bytes,
            'timestamp-low byte 1',
            $bytes['timestampLowBytes'][1],
            2
        ];
        yield 'timestamp-low byte 2' => [
            $bytes,
            'timestamp-low byte 2',
            $bytes['timestampLowBytes'][2],
            4
        ];
        yield 'timestamp-low byte 3' => [
            $bytes,
            'timestamp-low byte 3',
            $bytes['timestampLowBytes'][3],
            6
        ];
        yield 'timestamp-mid byte 0' => [
            $bytes,
            'timestamp-mid byte 0',
            $bytes['timestampMidBytes'][0],
            9
        ];
        yield 'timestamp-mid byte 1' => [
            $bytes,
            'timestamp-mid byte 1',
            $bytes['timestampMidBytes'][1],
            11
        ];
        yield 'timestamp-high byte 0 and version' => [
            $bytes,
            'timestamp-high byte 0 and version',
            $bytes['timestampHighBytes'][0],
            14
        ];
        yield 'timestamp-high byte 1' => [
            $bytes,
            'timestamp-high byte 1',
            $bytes['timestampHighBytes'][1],
            16
        ];
        yield 'clock-sequence-high and variant' => [
            $bytes,
            'clock-sequence-high and variant',
            $bytes['clockSequenceHighByte'],
            19
        ];
        yield 'clock-sequence-low' => [
            $bytes,
            'clock-sequence-low',
            $bytes['clockSequenceLowByte'],
            21
        ];
        yield 'node byte 0' => [
            $bytes,
            'node byte 0',
            $bytes['nodeBytes'][0],
            24
        ];
        yield 'node byte 1' => [
            $bytes,
            'node byte 1',
            $bytes['nodeBytes'][1],
            26
        ];
        yield 'node byte 2' => [
            $bytes,
            'node byte 2',
            $bytes['nodeBytes'][2],
            28
        ];
        yield 'node byte 3' => [
            $bytes,
            'node byte 3',
            $bytes['nodeBytes'][3],
            30
        ];
        yield 'node byte 4' => [
            $bytes,
            'node byte 4',
            $bytes['nodeBytes'][4],
            32
        ];
        yield 'node byte 5' => [
            $bytes,
            'node byte 5',
            $bytes['nodeBytes'][5],
            34
        ];
    }

    /**
     * @test
     * @testdox Puts $byteName at correct position in string
     * @dataProvider byteProvider
     */
    public function puts_byte_at_correct_position_in_string(array $uuidBytes, string $byteName, int $expectedByteValue, int $positionInString): void
    {
        // given an Uuid as a string
        $uuid = $this->createInstance(...$uuidBytes);
        $uuidString = (string) $uuid;

        // when accessing the byte at given position in the string
        $byteHexaString = substr($uuidString, offset: $positionInString, length: 2);
        sscanf($byteHexaString, '%2x', $byte);

        // then the byte should have the given value
        $this->assertSame(
            expected: $expectedByteValue,
            actual: $byte & $expectedByteValue,
            message: "Expected {$byteName} ($expectedByteValue) to be at position {$positionInString} in string {$uuidString}, found {$byteHexaString} ({$byte})",
        );
    }

    /**
     * @test
     * @testdox Creating an instance requires RFC-compliant Uuid-string
     */
    public function expects_rfc_compliant_uuid_string(): void
    {
        $this->expectException(InvalidUuidStringException::class);

        // given an invalid Uuid-string
        $invalidUuidString = 'invalid string';

        // when trying to create an Uuid form it
        call_user_func_array(
            callback: $this->createInstance()::class . '::fromString',
            args: [$invalidUuidString],
        );

        // then instantiation should be rejected
    }

    /**
     * @test
     * @testdox Uuid made from string gives correct string back
     */
    public function creates_uuid_matching_base_string(): void
    {
        // given an rfc-compliant uuid-string
        $uuidString = '6b9b83fb-916b-471d-c37f-1980f0bf78bd';

        // when creating an uuid instance from it
        $uuid = call_user_func_array(
            callback: $this->createInstance()::class . '::fromString',
            args: [$uuidString],
        );

        // then it should match back the uuid-string when turned back to string
        $this->assertSame(
            expected: $uuidString,
            actual: (string) $uuid,
            message: "Expected to find back uuid-string {$uuidString} when make an Uuid from it, got {$uuid}",
        );
    }

    /**
     * @test
     * @testdox Creates versioned Uuid from string
     */
    public function creates_versioned_uuid_from_string(): void
    {
        // given an versioned uuid-string
        $uuidString = '00000000-0000-7000-0000-000000000000';

        // when making an uuid from it
        $uuid = $uuid = call_user_func_array(
            callback: $this->createInstance()::class . '::fromString',
            args: [$uuidString],
        );

        // then version of the instance should be 7
        $this->assertSame(
            expected: 7,
            actual: $uuid->version(),
            message: "Expected version 7 to be parsed from string, got {$uuid->version()}",
        );
    }

    public function variantProvider(): Generator
    {
        yield [
            ['0', '1', '2', '3', '4', '5', '6', '7'],
            'Apollo NCS (backward compatibility)'
        ];
        yield [
            ['8', '9', 'a', 'b'],
            'RFC'
        ];
        yield [
            ['c', 'd'],
            'Microsoft (backward compatibility)'
        ];
        yield [
            ['e', 'f'],
            'Reserved (future definition)'
        ];
    }

    /**
     * @test
     * @testdox Creates Uuid from string with variant $expectedVariant
     * @dataProvider variantProvider
     */
    public function creates_variant_uuid_from_string(array $variantDigits, string $expectedVariant): void
    {
        foreach ($variantDigits as $variantDigit) {
            // given an Uuid made from string with variant
            $uuid = $uuid = call_user_func_array(
                callback: $this->createInstance()::class . '::fromString',
                args: ["00000000-0000-0000-{$variantDigit}000-000000000000"],
            );

            // when checking the variant it's made from
            $variant = $uuid->variant();

            // then it should match the expectation
            $this->assertSame(
                expected: $expectedVariant,
                actual: $variant,
                message: "Failed to detect that digit {$variantDigit} belongs to {$expectedVariant}",
            );
        }
    }

    /**
     * @test
     */
    public function has_native_rfc_uuid_string_representation(): void
    {
        // given a Uuid
        $uuid = $this->createInstance();

        // when checking its native format
        $format = $uuid->nativeFormat();

        // then it should be of type string
        $this->assertSame(
            expected: $uuid->toRfcUuidString(),
            actual: $format,
            message: "Expected Uuid to have RFC Uuid-string as native representation, got {$format}",
        );
    }
}
