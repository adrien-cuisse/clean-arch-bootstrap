<?php

namespace Alphonse\CleanArch\Tests\Domain\Fields\Identity\Uuid;

use Generator;
use ReflectionClass;
use PHPUnit\Framework\TestCase;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\Uuid;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\UuidInterface;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidVersionException;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidNodeBytesCountException;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidTimeLowBytesCountException;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidTimeMidBytesCountException;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidTimeHighBytesCountException;

/**
 * @coversDefaultClass Alphonse\CleanArch\Domain\Fields\Identity\Uuid\Uuid
 * @uses Alphonse\CleanArch\Domain\Fields\Identity\Uuid\Uuid
 */
final class UuidTest extends TestCase
{
    private function getProperty(UuidInterface $uuid, string $propertyName): int|array
    {
        $class = new ReflectionClass(Uuid::class);
        $property = $class->getProperty(name: $propertyName);
        $property->setAccessible(true);

        return $property->getValue(object: $uuid);
    }

    private function getConstant(string $constantName): int
    {
        return (new ReflectionClass(Uuid::class))->getConstant(name: $constantName);
    }

    /**
     * @return UuidInterface - an instance to test
     */
    private function createInstance(
        int $version = 0,
        array $timeLowBytes = [0, 0, 0, 0],
        array $timeMidBytes = [0, 0],
        array $timeHighBytes = [0, 0],
        int $clockSeqHighByte = 0,
        int $clockSeqLowByte = 0,
        array $nodeBytes = [0, 0, 0, 0, 0, 0],
    ): UuidInterface {
        return new class(
            $version,
            $timeLowBytes,
            $timeMidBytes,
            $timeHighBytes,
            $clockSeqHighByte,
            $clockSeqLowByte,
            $nodeBytes,
        ) extends Uuid {
            public function __construct(
                int $version,
                array $timeLowBytes,
                array $timeMidBytes,
                array $timeHighBytes,
                int $clockSeqHighByte,
                int $clockSeqLowByte,
                array $nodeBytes,
            ) {
                parent::__construct(
                    version: $version,
                    timeLowBytes: $timeLowBytes,
                    timeMidBytes: $timeMidBytes,
                    timeHighBytes: $timeHighBytes,
                    clockSeqHighByte: $clockSeqHighByte,
                    clockSeqLowByte: $clockSeqLowByte,
                    nodeBytes: $nodeBytes,
                );
            }
        };
    }

    public function invalidBytesCountProvider(): Generator
    {
        yield 'time-low' => [
            ['timeLowBytes' => []],
            InvalidUuidTimeLowBytesCountException::class
        ];
        yield 'time-mid' => [
            ['timeMidBytes' => []],
            InvalidUuidTimeMidBytesCountException::class
        ];
        yield 'time-high' => [
            ['timeHighBytes' => []],
            InvalidUuidTimeHighBytesCountException::class
        ];
        yield 'node' => [
            ['nodeBytes' => []],
            InvalidUuidNodeBytesCountException::class
        ];
    }

    /**
     * @test
     * @dataProvider invalidBytesCountProvider
     * @covers ::__construct
     * @uses Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidTimeLowBytesCountException
     * @uses Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidTimeMidBytesCountException
     * @uses Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidTimeHighBytesCountException
     * @uses Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidNodeBytesCountException
     */
    public function rejects_invalid_bytes_count(array $invalidBytes, string $expectedExceptionClass): void
    {
        $this->expectException(exception: $expectedExceptionClass);

        // given an Uuid with invalid bytes count
        $this->createInstance(...$invalidBytes);

        // then instantiation should be rejected
    }

    /**
     * @test
     * @covers ::__construct
     * @uses Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidVersionException
     */
    public function expects_4_bits_version(): void
    {
        $this->expectException(InvalidUuidVersionException::class);

        // given some uuid with version taking more than 4 bits
        $this->createInstance(version: 0b1111_1111);

        // then instantation should be rejected
    }

    public function overflowingBytesProvider(): Generator
    {
        yield 'time-low' => [
            ['timeLowBytes' => [0x4201, 0x4202, 0x4203, 0x4204]],
            [0x01,0x02, 0x03, 0x04],
            'timeLowBytes'
        ];
        yield 'time-mid' => [
            ['timeMidBytes' => [0x4205, 0x4206]],
            [0x05,0x06],
            'timeMidBytes'
        ];
        yield 'time-high' => [
            ['timeHighBytes' => [0x4207, 0x4208]],
            [0x07,0x08],
            'timeHighBytes'
        ];
        yield 'node' => [
            ['nodeBytes' => [0x420a, 0x420b, 0x420c, 0x420d, 0x420e, 0x420f]],
            [0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f],
            'nodeBytes'
        ];
    }

    /**
     * @test
     * @dataProvider overflowingBytesProvider
     * @covers ::__construct
     * @covers ::clampToByte
     * @covers ::clampToBytes
     */
    public function clamps_to_bytes(array $overflowingBytes, array $expectedClampedValues, string $propertyName): void
    {
        // given a new Uuid
        $uuid = $this->createInstance(...$overflowingBytes);

        // when accessing the stored bytes
        $storedBytes = $this->getProperty(uuid: $uuid, propertyName: $propertyName);

        // then they should be clamped as expected
        $this->assertSame(
            expected: $expectedClampedValues,
            actual: $storedBytes,
            message: sprintf(
                "Expected property {$propertyName} to be clamped to [%s], got [%s]",
                implode(', ', $expectedClampedValues),
                implode(', ', $storedBytes),
            ),
        );
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function clamps_time_high_most_significant_byte_to_4_bits(): void
    {
        // given some uuid with time-high first byte taking more than 4 bits
        $uuid = $this->createInstance(timeHighBytes: [0b1111_1111, 0x00]);

        // when accessing its time-high first byte
        $timeHighFirstByte = $this->getProperty($uuid, 'timeHighBytes')[0];

        // then the byte should be clamped to 4 bits
        $this->assertSame(
            expected: 0b0000_1111,
            actual: $timeHighFirstByte,
            message: "Expected time-high MSB to get clamped to 4 bits"
        );
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::toRfcUuidString
     * @covers ::hexaStringFrom
     */
    public function interlops_version_in_time_high_MSB(): void
    {
        // given some Uuid
        $uuid = $this->createInstance(version: 0b0000_0101, timeHighBytes: [0b0000_1010, 0x0000_0110]);

        // when extracting its byte 6
        $seventhByteHexaString = substr(string: (string) $uuid, offset: 14, length: 2);
        sscanf($seventhByteHexaString, '%2x', $seventhByte);

        // then version should be interloped with time-high MSB
        $this->assertSame(
            expected: 0b0101_1010,
            actual: $seventhByte,
            message: "Uuid should interlop version in time-high MSB"
        );
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function clamps_clock_seq_high_to_6_bits(): void
    {
        // given some uuid with clock-seq-high byte taking more than 6 bits
        $uuid = $this->createInstance(clockSeqHighByte: 0b1111_1111);

        // when accessing its clock-seq-high byte
        $clockSeqHighByte = $this->getProperty($uuid, 'clockSeqHighBits');

        // then the byte should be clamped to 6 bits
        $this->assertSame(
            expected: 0b0011_1111,
            actual: $clockSeqHighByte,
            message: "Expected clock-seq-high to get clamped to 6 bits"
        );
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::toRfcUuidString
     * @covers ::hexaStringFrom
     */
    public function interlops_variant_in_clock_seq_high(): void
    {
        // given some Uuid
        $uuid = $this->createInstance(clockSeqHighByte: 0b0011_1111);

        // when extracting its byte 8
        $ninthByteHexaString = substr(string: (string) $uuid, offset: 19, length: 2);
        sscanf($ninthByteHexaString, '%2x', $ninthByte);

        // then variant should be interloped with clock-seq-high
        $this->assertSame(
            expected: 0b1011_1111,
            actual: $ninthByte,
            message: "Uuid should interlop variant in clock-seq-high"
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::toRfcUuidString
     * @covers ::hexaStringFrom
     */
    public function has_rfc_compliant_representation(): void
    {
        // given a new Uuid
        $uuid = $this->createInstance();

        // when using its RFC representation
        $representation = $uuid->toRfcUuidString();

        // then it should have RFC compliant format
        $this->assertMatchesRegularExpression(
            pattern: '/^[[:xdigit:]]{8}-([[:xdigit:]]{4}-){3}[[:xdigit:]]{12}$/',
            string: $representation,
            message: "The UUID doesn't comply with RFC representation 01234567-89ab-cdef-0123-456789abcdef",
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::toRfcUuidString
     * @covers ::getVersion
     * @covers ::hexaStringFrom
     */
    public function contains_version_in_byte_6_most_significant_bits(): void
    {
        // given some Uuid
        $uuid = $this->createInstance(version: 0b0000_0101);

        // when extracting its version digit and its version
        $versionDigit = $uuid->toRfcUuidString()[14];
        $version = $uuid->getVersion();

        // then they should be the same
        $this->assertEquals(
            actual: $versionDigit,
            expected: $version,
            message: "Uuid should have its version number as 13th digit, found {$versionDigit} instead of {$version}",
        );
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::toRfcUuidString
     * @covers ::hexaStringFrom
     */
    public function contains_variant_in_byte_8_most_significant_bits(): void
    {
        // given a new Uuid
        $uuid = $this->createInstance();

        // when extracting its 9th byte of data (byte 8, digits 19 and 20)
        $rfcRepresentation = (string) $uuid;
        $ninthByteHexaString = substr($rfcRepresentation, 19, 2);
        sscanf($ninthByteHexaString, '%2x', $ninthByte);
        // and when accessing its variant bits
        $variantBits = $this->getConstant('VARIANT_BITS');

        // then it should have its two most significant bytes set to its variant
        $this->assertSame(
            expected: $variantBits,
            actual: $ninthByte & 0b1100_0000,
            message: "Uuid should have the most significant bits of its byte 8 set to its variant",
        );
    }

    public function byteProvider(): Generator
    {
        $bytes = [
            'version' => 0b1111,
            'timeLowBytes' => [0x00, 0x01, 0x02, 0x03],
            'timeMidBytes' => [0x04, 0x05],
            'timeHighBytes' => [0x06, 0x07],
            'clockSeqHighByte' => 0x08,
            'clockSeqLowByte' => 0x09,
            'nodeBytes' => [0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f],
        ];

        yield 'time-low-0' => [
            $bytes,
            'time-low-0',
            $bytes['timeLowBytes'][0],
            0
        ];
        yield 'time-low-1' => [
            $bytes,
            'time-low-1',
            $bytes['timeLowBytes'][1],
            2
        ];
        yield 'time-low-2' => [
            $bytes,
            'time-low-2',
            $bytes['timeLowBytes'][2],
            4
        ];
        yield 'time-low-3' => [
            $bytes,
            'time-low-3',
            $bytes['timeLowBytes'][3],
            6
        ];
        yield 'time-mid-0' => [
            $bytes,
            'time-mid-0',
            $bytes['timeMidBytes'][0],
            9
        ];
        yield 'time-mid-1' => [
            $bytes,
            'time-mid-1',
            $bytes['timeMidBytes'][1],
            11
        ];
        yield 'time-high-0-and-version' => [
            $bytes,
            'time-high-0-and-version',
            $bytes['timeHighBytes'][0],
            14
        ];
        yield 'time-high-1' => [
            $bytes,
            'time-high-1',
            $bytes['timeHighBytes'][1],
            16
        ];
        yield 'clock-seq-high-and-variant' => [
            $bytes,
            'clock-seq-high-and-variant',
            $bytes['clockSeqHighByte'],
            19
        ];
        yield 'clock-seq-low' => [
            $bytes,
            'clock-seq-low',
            $bytes['clockSeqLowByte'],
            21
        ];
        yield 'node-0' => [
            $bytes,
            'node-0',
            $bytes['nodeBytes'][0],
            24
        ];
        yield 'node-1' => [
            $bytes,
            'node-1',
            $bytes['nodeBytes'][1],
            26
        ];
        yield 'node-2' => [
            $bytes,
            'node-2',
            $bytes['nodeBytes'][2],
            28
        ];
        yield 'node-3' => [
            $bytes,
            'node-3',
            $bytes['nodeBytes'][3],
            30
        ];
        yield 'node-4' => [
            $bytes,
            'node-4',
            $bytes['nodeBytes'][4],
            32
        ];
        yield 'node-5' => [
            $bytes,
            'node-5',
            $bytes['nodeBytes'][5],
            34
        ];
    }

    /**
     * @test
     * @dataProvider byteProvider
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::toRfcUuidString
     * @covers ::hexaStringFrom
     */
    public function puts_byte_at_correct_position(array $uuidBytes, string $byteName, int $expectedByteValue, int $positionInString): void
    {
        // given an Uuid as a string
        $uuid = $this->createInstance(...$uuidBytes);
        $uuidString = (string) $uuid;

        // when accessing the byte at given position in the string
        $byteHexaString = substr($uuidString, $positionInString, 2);
        sscanf($byteHexaString, '%2x', $byte);

        // then the byte should have the given value
        $this->assertSame(
            expected: $expectedByteValue,
            actual: $byte & $expectedByteValue,
            message: "Expected {$byteName} ($expectedByteValue) to be at position {$positionInString} in string {$uuidString}, found {$byteHexaString} ({$byte})"
        );
    }

    /*
     * @test
     * @covers ::fromString
     * @uses Alphonse\CleanArch\Domain\Fields\Identity\Uuid\Uuid
     */
    public function expects_rfc_format_representation(): void
    {
        $this->expectException(InvalidArgumentException::class);

        // given an invalid Uuid string
        $rfcUuidString = 'invalid string';

        // when creating an Uuid object from it
        $fromStringMethod = [$this->createInstance(), 'fromString'];
        call_user_func($fromStringMethod, $rfcUuidString);

        // then it should not be accepted
    }

    /*
     * @test
     * @covers ::fromString
     */
    public function rejects_invalid_versioned_string(): void
    {
        $this->expectException(InvalidArgumentException::class);

        // given a valid Uuid string but with wrong version
        $rfcUuidString = '5a0af6c1-f16a-5d2d-ae7d-5ea97a0a1c7b';

        // when creating an Uuid object from it
        $fromStringMethod = [$this->createInstance(), 'fromString'];
        call_user_func($fromStringMethod, $rfcUuidString);

        // then it should not be accepted
    }

    /*
     * @test
     * @covers ::fromString
     */
    public function creates_correct_uuid_from_string(): void
    {
        // given a valid RFC UUID-string representation
        $inputUuidString = 'b25fcb2e-576d-3b87-b5b2-cb2cd57eddd8';

        // when creating an Uuid from it and checking its string representation
        $fromStringMethod = [$this->createInstance(), 'fromString'];
        $uuid = call_user_func($fromStringMethod, $inputUuidString);
        $outputUuidString = (string) $uuid;

        // then it should match the one used to create the Uuid
        $this->assertEquals(
            expected: $inputUuidString,
            actual: $outputUuidString,
            message: "Uuid created from string doesn't match the string ($inputUuidString), got {$outputUuidString}"
        );
    }
}
