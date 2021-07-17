<?php

namespace Alphonse\CleanArch\Tests\Domain\Fields\Identity\Uuid;

use Generator;
use ReflectionClass;
use PHPUnit\Framework\TestCase;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\Uuid;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\UuidInterface;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidVersionException;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidNodeBytesCountException;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidStringException;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidTimeLowBytesCountException;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidTimeMidBytesCountException;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidTimeHighBytesCountException;

/**
 * @coversDefaultClass Alphonse\CleanArch\Domain\Fields\Identity\Uuid\Uuid
 * @uses Alphonse\CleanArch\Domain\Fields\Identity\Uuid\Uuid
 */
final class UuidTest extends TestCase
{
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

            public static function fromString(string $uuidString): static
            {
                return parent::fromString(rfcUuidString: $uuidString);
            }
        };
    }

    public function invalidBytesCountProvider(): Generator
    {
        yield [
            ['timeLowBytes' => []],
            InvalidUuidTimeLowBytesCountException::class,
            'time-low bytes',
        ];
        yield [
            ['timeMidBytes' => []],
            InvalidUuidTimeMidBytesCountException::class,
            'time-mid bytes',
        ];
        yield [
            ['timeHighBytes' => []],
            InvalidUuidTimeHighBytesCountException::class,
            'time-high bytes',
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
     * @covers ::__construct
     * @uses Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidTimeLowBytesCountException
     * @uses Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidTimeMidBytesCountException
     * @uses Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidTimeHighBytesCountException
     * @uses Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidNodeBytesCountException
     */
    public function rejects_invalid_bytes_count(array $invalidBytes, string $expectedExceptionClass, string $bytesName): void
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

    /**
     * @test
     * @testdox Interlops version in time-high MSB
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
     * @testdox Interlops variant in clock-seq-high byte
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
     * @testdox Has RFC-compliant string-representation
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::clampToByte
     * @covers ::clampToBytes
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

        yield 'time-low byte 0' => [
            $bytes,
            'time-low byte 0',
            $bytes['timeLowBytes'][0],
            0
        ];
        yield 'time-low byte 1' => [
            $bytes,
            'time-low byte 1',
            $bytes['timeLowBytes'][1],
            2
        ];
        yield 'time-low byte 2' => [
            $bytes,
            'time-low byte 2',
            $bytes['timeLowBytes'][2],
            4
        ];
        yield 'time-low byte 3' => [
            $bytes,
            'time-low byte 3',
            $bytes['timeLowBytes'][3],
            6
        ];
        yield 'time-mid byte 0' => [
            $bytes,
            'time-mid byte 0',
            $bytes['timeMidBytes'][0],
            9
        ];
        yield 'time-mid byte 1' => [
            $bytes,
            'time-mid byte 1',
            $bytes['timeMidBytes'][1],
            11
        ];
        yield 'time-high byte 0 and version' => [
            $bytes,
            'time-high byte 0 and version',
            $bytes['timeHighBytes'][0],
            14
        ];
        yield 'time-high byte 1' => [
            $bytes,
            'time-high byte 1',
            $bytes['timeHighBytes'][1],
            16
        ];
        yield 'clock-seq-high and variant' => [
            $bytes,
            'clock-seq-high and variant',
            $bytes['clockSeqHighByte'],
            19
        ];
        yield 'clock-seq-low' => [
            $bytes,
            'clock-seq-low',
            $bytes['clockSeqLowByte'],
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
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::toRfcUuidString
     * @covers ::hexaStringFrom
     */
    public function puts_byte_at_correct_position_in_string(array $uuidBytes, string $byteName, int $expectedByteValue, int $positionInString): void
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

    /**
     * @test
     * @testdox Creating an instance requires RFC-compliant Uuid-string
     * @covers ::fromString
     * @uses Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidStringException
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
     * @covers ::fromString
     * @covers ::__toString
     * @covers ::toRfcUuidString
     */
    public function creates_uuid_matching_base_string(): void
    {
        // given an rfc-compliant uuid-string
        $uuidString = '6b9b83fb-916b-471d-837f-1980f0bf78bd';

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
     * @covers ::fromString
     * @covers ::getVersion
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
            actual: $uuid->getVersion(),
            message: "Expected version 7 to be parsed from string, got {$uuid->getVersion()}",
        );
    }
}
