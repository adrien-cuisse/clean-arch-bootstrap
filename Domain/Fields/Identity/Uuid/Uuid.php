<?php

namespace Alphonse\CleanArch\Domain\Fields\Identity\Uuid;

use ReflectionClass;
use InvalidArgumentException;

/**
 * @see UuidInterface
 */
abstract class Uuid implements UuidInterface
{
    /**
     * The variant used
     * @link https://datatracker.ietf.org/doc/html/rfc4122#section-4.1.1
     */
    private const VARIANT = 0b10;

    /**
     * Variant bits, will be multiplexed with the clock-seq-high bits
     */
    private const VARIANT_BITS = self::VARIANT << 6;

    /**
     * Bit-mask to clamp an integer to byte-range
     */
    private const BYTE_MASK = 0b1111_1111;

    /**
     * Bit-mask to extract the 4 least significant bits from a byte
     */
    private const LOWEST_4_BITS_MASK = 0b0000_1111;

    /**
     * Bit-mask to extract the 4 most significant bits from a byte
     */
    private const HIGHEST_4_BITS_MASK = 0b1111_0000;

    /**
     * Bit-mask to apply on time-high-and-version to extract time-high bits
     */
    private const TIME_HIGH_BITS_MASK = self::LOWEST_4_BITS_MASK;

    /**
     * Bit-mask to apply on time-high-and-version to extract version bits
     */
    private const VERSION_BITS_MASK = self::HIGHEST_4_BITS_MASK;
    /**
     * Bit-mask to remove the 2 most significant bits from the clock-seq-high byte
     */
    private const CLOCK_SEQ_HIGH_MASK = 0b0011_1111;

    /**
     * @var int[] - bytes 0 to 3
     */
    private array $timeLowBytes;

    /**
     * @var int[] - bytes 4 to 5
     */
    private array $timeMidBytes;

    /**
     * @var int - the version of the Uuid
     */
    private int $version;

    /**
     * @var int - 4 most significant bits of the byte 6
     */
    private int $versionBits;

    /**
     * @var int[] - 4 least significant bits of byte 6, byte 7
     */
    private array $timeHighBytes;

    /**
     * @var int - 6 least significants bits of byte 8
     */
    private int $clockSeqHighBits;

    /**
     * @var int - byte 9
     */
    private int $clockSeqLowByte;

    /**
     * @var int[] - bytes 10 to 15
     */
    private array $nodeBytes;

    /**
     * @throws InvalidUuidTimeLowBytesCountException - if the number of time-low bytes is invalid
     * @throws InvalidUuidTimeMidBytesCountException - if the number of time-mid bytes is invalid
     * @throws InvalidUuidTimeHighBytesCountException - if the number of time-high bytes is invalid
     * @throws InvalidUuidVersionException - if the version exceeds 15
     * @throws InvalidUuidNodeBytesCountException - if the number of node bytes is invalid
     */
    protected function __construct(
        int $version,
        array $timeLowBytes,
        array $timeMidBytes,
        array $timeHighBytes,
        int $clockSeqHighByte,
        int $clockSeqLowByte,
        array $nodeBytes,
    ) {
        if (count(value: $timeLowBytes) !== 4) {
            throw new InvalidUuidTimeLowBytesCountException(bytes: $timeLowBytes);
        }
        $this->timeLowBytes = $this->clampToBytes(integers: $timeLowBytes);

        if (count(value: $timeMidBytes) !== 2) {
            throw new InvalidUuidTimeMidBytesCountException(bytes: $timeMidBytes);
        }
        $this->timeMidBytes = $this->clampToBytes(integers: $timeMidBytes);

        if (count(value: $timeHighBytes) !== 2) {
            throw new InvalidUuidTimeHighBytesCountException(bytes: $timeHighBytes);
        }
        $this->timeHighBytes = $this->clampToBytes(integers: $timeHighBytes);
        $this->timeHighBytes[0] &= self::TIME_HIGH_BITS_MASK;

        if ($version > 0b0000_1111) {
            throw new InvalidUuidVersionException(version: $version);
        }
        $this->version = $version;
        $this->versionBits = $this->version << 4;

        $this->clockSeqHighBits = $clockSeqHighByte & self::CLOCK_SEQ_HIGH_MASK;
        $this->clockSeqLowByte = $this->clampToByte(value: $clockSeqLowByte);

        if (count(value: $nodeBytes) !== 6) {
            throw new InvalidUuidNodeBytesCountException(bytes: $nodeBytes);
        }
        $this->nodeBytes = $this->clampToBytes(integers: $nodeBytes);
    }

    /**
     * @see UuidInterface
     */
    final public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * Puts all bits in the right place and generates the string representation
     *
     * @see UuidInterface
     */
    final public function toRfcUuidString(): string
    {
        $versionAndTimeHighBytes = [
            $this->versionBits | $this->timeHighBytes[0],
            $this->timeHighBytes[1]
        ];

        $variantAndclockSeqHighByte = self::VARIANT_BITS | $this->clockSeqHighBits;

        return sprintf(
            "%s-%s-%s-%s%s-%s",
            $this->hexaStringFrom(bytes: $this->timeLowBytes),
            $this->hexaStringFrom(bytes: $this->timeMidBytes),
            $this->hexaStringFrom(bytes: $versionAndTimeHighBytes),
            $this->hexaStringFrom(bytes: [$variantAndclockSeqHighByte]),
            $this->hexaStringFrom(bytes: [$this->clockSeqLowByte]),
            $this->hexaStringFrom(bytes: $this->nodeBytes)
        );
    }

    /**
     * @see Stringable
     */
    final public function __toString(): string
    {
        return $this->toRfcUuidString();
    }

    /**
     * Creates an UUID from a RFC representation string
     *
     * @param string $rfcUuidString - the RFC representation to build the Uuid from
     *
     * @return static
     */
    protected static function fromString(string $rfcUuidString): static
    {
        $rfcValidationRegex = '/^[[:xdigit:]]{8}-([[:xdigit:]]{4}-){3}[[:xdigit:]]{12}$/';
        if (preg_match(pattern: $rfcValidationRegex, subject: $rfcUuidString) !== 1) {
            throw new InvalidUuidStringException(uuidString: $rfcUuidString);
        }

        $instance = (new ReflectionClass(static::class))->newInstanceWithoutConstructor();

        [$timeLow, $timeMid, $timeHighAndVersion, $clockSeq, $node] = explode(separator: '-', string: $rfcUuidString);

        $instance->timeLowBytes = sscanf(string: $timeLow, format: '%2x%2x%2x%2x');
        $instance->timeMidBytes = sscanf(string: $timeMid, format: '%2x%2x');
        $instance->timeHighBytes = sscanf(string: $timeHighAndVersion, format: '%2x%2x');
        $instance->timeHighBytes[0] &= self::TIME_HIGH_BITS_MASK;
        $instance->versionBits = sscanf(string: $timeHighAndVersion, format: '%2x')[0] & self::VERSION_BITS_MASK;
        $instance->version = $instance->versionBits >> 4;
        $instance->clockSeqHighBits = sscanf(string: $clockSeq, format: '%2x')[0] & self::CLOCK_SEQ_HIGH_MASK;
        $instance->clockSeqLowByte = sscanf(string: $clockSeq, format: '%2x%2x')[1];
        $instance->nodeBytes = sscanf(string: $node, format: '%2x%2x%2x%2x%2x%2x');

        return $instance;
    }

    /**
     * Generates a random byte, an unsigned integers between 0 and 255
     *
     * @return int - an integers in between 0 and 255
     */
    final protected function randomByte(): int
    {
        $ascii = openssl_random_pseudo_bytes(length: 1);

        return ord(character: $ascii);
    }

    /**
     * Generates random bytes, with bytes being unsigned integers between 0 and 255
     *
     * @param int $numberOfBytes - how many bytes to generate
     *
     * @return int[] - array of integers between 0 and 255 of specified size
     */
    final protected function randomBytes(int $numberOfBytes): array
    {
        $binaryString = openssl_random_pseudo_bytes(length: $numberOfBytes);

        return array_map(
            callback: fn (string $ascii) => ord(character: $ascii),
            array: str_split($binaryString),
        );
    }

    /**
     * Removes overflowing bits from the given value
     *
     * @param int $value - the integer to clamp
     *
     * @return int - 8 lowest bits of the given value
     */
    private function clampToByte(int $value): int
    {
        return $value & self::BYTE_MASK;
    }

    /**
     * Clamps the given integers to bytes, ensuring no invalid value is send from extending classes
     *
     * @param int[] $integers - the integers to turn to bytes
     *
     * @return array - 8 lowest bits of each given integer
     */
    private function clampToBytes(array $integers): array
    {
        return array_map(
            callback: fn (int $value) => $this->clampToByte($value),
            array: $integers
        );
    }

    // public function bytesFromBinaryString(string $binaryString)

    /**
     * Writes the given bytes in an hexadecimal string
     *
     * @param int[] $bytes - array of unsigned byte to write
     *
     * @return string - the bytes as an hexadecimal string
     */
    private function hexaStringFrom(array $bytes): string
    {
        $bytesCount = count(value: $bytes);

        $binaryString = pack("C{$bytesCount}", ...$bytes);

        return bin2hex(string: $binaryString);
    }
}
