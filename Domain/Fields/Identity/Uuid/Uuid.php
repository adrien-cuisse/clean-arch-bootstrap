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
     * Bit-mask to apply on timestamp-high-and-version to extract timestamp-high bits
     */
    private const TIMESTAMP_HIGH_BITS_MASK = self::LOWEST_4_BITS_MASK;

    /**
     * Bit-mask to apply on timestamp-high-and-version to extract version bits
     */
    private const VERSION_BITS_MASK = self::HIGHEST_4_BITS_MASK;

    /**
     * The variant used by default when using constructor
     *
     * @link https://datatracker.ietf.org/doc/html/rfc4122#section-4.1.1
     */
    private const VARIANT = 0b10;

    /**
     * Variant bits used by default when using constructor, will be multiplexed with the clock-sequence-high bits
     */
    private const VARIANT_BITS = self::VARIANT << 6;

    /**
     * Bit-mask to remove the 2 most significant bits from the clock-sequence-high byte
     */
    private const CLOCK_SEQUENCE_HIGH_MASK = 0b0011_1111;

    /**
     * @var int[] - bytes 0 to 3
     */
    private array $timestampLowBytes;

    /**
     * @var int[] - bytes 4 to 5
     */
    private array $timestampMidBytes;

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
    private array $timestampHighBytes;

    /**
     * @var int - 6 least significants bits of byte 8
     */
    private int $clockSequenceHighBits;

    /**
     * @var int - byte 9
     */
    private int $clockSequenceLowByte;

    /**
     * @var int[] - bytes 10 to 15
     */
    private array $nodeBytes;

    /**
     * @throws InvalidUuidTimeLowBytesCountException - if the number of timestamp-low bytes is invalid
     * @throws InvalidUuidTimeMidBytesCountException - if the number of timestamp-mid bytes is invalid
     * @throws InvalidUuidTimeHighBytesCountException - if the number of timestamp-high bytes is invalid
     * @throws InvalidUuidVersionException - if the version exceeds 15
     * @throws InvalidUuidNodeBytesCountException - if the number of node bytes is invalid
     */
    protected function __construct(
        int $version,
        array $timestampLowBytes,
        array $timestampMidBytes,
        array $timestampHighBytes,
        int $clockSequenceHighByte,
        int $clockSequenceLowByte,
        array $nodeBytes,
    ) {
        if (count(value: $timestampLowBytes) !== 4) {
            throw new InvalidUuidTimeLowBytesCountException(bytes: $timestampLowBytes);
        }
        $this->timestampLowBytes = $this->clampToBytes(integers: $timestampLowBytes);

        if (count(value: $timestampMidBytes) !== 2) {
            throw new InvalidUuidTimeMidBytesCountException(bytes: $timestampMidBytes);
        }
        $this->timestampMidBytes = $this->clampToBytes(integers: $timestampMidBytes);

        if (count(value: $timestampHighBytes) !== 2) {
            throw new InvalidUuidTimeHighBytesCountException(bytes: $timestampHighBytes);
        }
        $this->timestampHighBytes = $this->clampToBytes(integers: $timestampHighBytes);
        $this->timestampHighBytes[0] &= self::TIMESTAMP_HIGH_BITS_MASK;

        if ($version > 0b0000_1111) {
            throw new InvalidUuidVersionException(version: $version);
        }
        $this->version = $version;
        $this->versionBits = $this->version << 4;

        $this->clockSequenceHighBits = $clockSequenceHighByte & self::CLOCK_SEQUENCE_HIGH_MASK;
        $this->clockSequenceLowByte = $this->clampToByte(value: $clockSequenceLowByte);

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
            $this->versionBits | $this->timestampHighBytes[0],
            $this->timestampHighBytes[1]
        ];

        $variantAndclockSequenceHighByte = self::VARIANT_BITS | $this->clockSequenceHighBits;

        return sprintf(
            "%s-%s-%s-%s%s-%s",
            $this->hexaStringFrom(bytes: $this->timestampLowBytes),
            $this->hexaStringFrom(bytes: $this->timestampMidBytes),
            $this->hexaStringFrom(bytes: $versionAndTimeHighBytes),
            $this->hexaStringFrom(bytes: [$variantAndclockSequenceHighByte]),
            $this->hexaStringFrom(bytes: [$this->clockSequenceLowByte]),
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

        [$timestampLow, $timestampMid, $timestampHighAndVersion, $clockSequenceAndVariant, $node] = explode(separator: '-', string: $rfcUuidString);

        $instance->timestampLowBytes = sscanf(string: $timestampLow, format: '%2x%2x%2x%2x');
        $instance->timestampMidBytes = sscanf(string: $timestampMid, format: '%2x%2x');
        $instance->timestampHighBytes = sscanf(string: $timestampHighAndVersion, format: '%2x%2x');
        $instance->timestampHighBytes[0] &= self::TIMESTAMP_HIGH_BITS_MASK;

        $instance->versionBits = sscanf(string: $timestampHighAndVersion, format: '%2x')[0] & self::VERSION_BITS_MASK;
        $instance->version = $instance->versionBits >> 4;

        $instance->clockSequenceHighBits = sscanf(string: $clockSequenceAndVariant, format: '%2x')[0] & self::CLOCK_SEQUENCE_HIGH_MASK;
        $instance->clockSequenceLowByte = sscanf(string: $clockSequenceAndVariant, format: '%2x%2x')[1];
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
