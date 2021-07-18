<?php

namespace Alphonse\CleanArch\Domain\Fields\Identity\Uuid;

use ReflectionClass;

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
     * Bit-mask to apply on clock-sequence-high byte when variant is stored in 1 bit
     */
    private const VARIANT_1_BIT_CLOCK_SEQUENCE_MASK = 0b0111_1111;

    /**
     * Bit-mask to apply on clock-sequence-high byte when variant is stored in 2 bits
     */
    private const VARIANT_2_BITS_CLOCK_SEQUENCE_MASK = 0b0011_1111;

    /**
     * Bit-mask to apply on clock-sequence-high byte when variant is stored in 3 bits
     */
    private const VARIANT_3_BITS_CLOCK_SEQUENCE_MASK = 0b0001_1111;

    /**
     * Bit-mask to apply on clock-sequence-high byte for backward compatiblity variant
     */
    private const BACKWARD_COMPATIBILITY_VARIANT_CLOCK_SEQUENCE_MASK = self::VARIANT_1_BIT_CLOCK_SEQUENCE_MASK;

    /**
     * Bit-mask to apply on clock-sequence-high byte for RFC variant
     */
    private const RFC_VARIANT_CLOCK_SEQUENCE_MASK = self::VARIANT_2_BITS_CLOCK_SEQUENCE_MASK;

    /**
     * Bit-mask to apply on clock-sequence-high byte for Microsoft variant
     */
    private const MICROSOFT_VARIANT_CLOCK_SEQUENCE_MASK = self::VARIANT_3_BITS_CLOCK_SEQUENCE_MASK;

    /**
     * Bit-mask to apply on clock-sequence-high byte for reserved variant
     */
    private const FUTURE_VARIANT_CLOCK_SEQUENCE_MASK = self::VARIANT_3_BITS_CLOCK_SEQUENCE_MASK;

    /**
     * The variant used by the Apollo Network Computing System
     *
     * @link https://en.wikipedia.org/wiki/Universally_unique_identifier#Variants
     */
    private const BACKWARD_COMPATIBILITY_VARIANT = 0b0;

    /**
     * Bit-mask to apply on clock-sequence-high byte for Microsoft variant, will be multiplexed with the clock-sequence-high bits
     */
    private const BACKWARD_COMPATIBILITY_VARIANT_BITS = self::BACKWARD_COMPATIBILITY_VARIANT << 7;

    /**
     * The variant used by default when using constructor, as defined in RFC4122
     *
     * @link https://datatracker.ietf.org/doc/html/rfc4122#section-4.1.1
     */
    private const RFC_VARIANT = 0b10;

    /**
     * Variant bits used by default when using constructor, will be multiplexed with the clock-sequence-high bits
     */
    private const RFC_VARIANT_BITS = self::RFC_VARIANT << 6;

    /**
     * The variant used by old Windows platforms
     *
     * @link https://en.wikipedia.org/wiki/Universally_unique_identifier#Variants
     */
    private const MICROSOFT_VARIANT = 0b110;

    /**
     * Bit-mask to apply on clock-sequence-high byte for Microsoft variant, will be multiplexed with the clock-sequence-high bits
     */
    private const MICROSOFT_VARIANT_BITS = self::MICROSOFT_VARIANT << 5;

    /**
     * The variant reserved for future specification
     *
     * @link https://en.wikipedia.org/wiki/Universally_unique_identifier#Variants
     */
    private const FUTURE_VARIANT = 0b111;

    /**
     * Bit-mask to apply on clock-sequence-high byte for future variant, will be multiplexed with the clock-sequence-high bits
     */
    private const FUTURE_VARIANT_BITS = self::FUTURE_VARIANT << 5;

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
     * @var int - the variant used, may differ from default when made from string
     *
     * @link https://datatracker.ietf.org/doc/html/rfc4122#section-4.1.1
     */
    private int $variant = self::RFC_VARIANT;

    /**
     * @var int - variant bits, will be multiplexed with the clock-sequence-high bits
     */
    private int $variantBits = self::RFC_VARIANT_BITS;

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
     * @throws InvalidUuidTimestampLowBytesCountException - if the number of timestamp-low bytes is invalid
     * @throws InvalidUuidTimestampMidBytesCountException - if the number of timestamp-mid bytes is invalid
     * @throws InvalidUuidTimestampHighBytesCountException - if the number of timestamp-high bytes is invalid
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
            throw new InvalidUuidTimestampLowBytesCountException(bytes: $timestampLowBytes);
        }
        $this->timestampLowBytes = $this->clampToBytes(integers: $timestampLowBytes);

        if (count(value: $timestampMidBytes) !== 2) {
            throw new InvalidUuidTimestampMidBytesCountException(bytes: $timestampMidBytes);
        }
        $this->timestampMidBytes = $this->clampToBytes(integers: $timestampMidBytes);

        if (count(value: $timestampHighBytes) !== 2) {
            throw new InvalidUuidTimestampHighBytesCountException(bytes: $timestampHighBytes);
        }
        $this->timestampHighBytes = $this->clampToBytes(integers: $timestampHighBytes);
        $this->timestampHighBytes[0] &= self::TIMESTAMP_HIGH_BITS_MASK;

        if ($version > 15) {
            throw new InvalidUuidVersionException(version: $version);
        }
        $this->version = $version;
        $this->versionBits = $this->version << 4;

        $this->clockSequenceHighBits = $clockSequenceHighByte & self::RFC_VARIANT_CLOCK_SEQUENCE_MASK;
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
     * @see UuidInterface
     */
    final public function getVariant(): string
    {
        return match($this->variant) {
            self::BACKWARD_COMPATIBILITY_VARIANT => 'Reserved (NCS backward compatibility)',
            self::RFC_VARIANT => 'RFC',
            self::MICROSOFT_VARIANT => 'Microsoft (backward compatibility)',
            self::FUTURE_VARIANT => 'Reserved (future definition)',
        };
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

        $variantAndclockSequenceHighByte = $this->variantBits | $this->clockSequenceHighBits;

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

        $instance->clockSequenceHighBits = sscanf(string: $clockSequenceAndVariant, format: '%2x')[0];

        $variantDigit = sscanf(string: $clockSequenceAndVariant, format: '%1c')[0];

        [$instance->variant, $instance->variantBits, $instance->clockSequenceHighBits] = match($variantDigit) {
            '0', '1', '2', '3', '4', '5', '6', '7' => [
                self::BACKWARD_COMPATIBILITY_VARIANT,
                self::BACKWARD_COMPATIBILITY_VARIANT_BITS,
                $instance->clockSequenceHighBits & self::BACKWARD_COMPATIBILITY_VARIANT_CLOCK_SEQUENCE_MASK
            ],
            '8', '9', 'a', 'b' => [
                self::RFC_VARIANT,
                self::RFC_VARIANT_BITS,
                $instance->clockSequenceHighBits & self::RFC_VARIANT_CLOCK_SEQUENCE_MASK,
            ],
            'c', 'd' => [
                self::MICROSOFT_VARIANT,
                self::MICROSOFT_VARIANT_BITS,
                $instance->clockSequenceHighBits & self::MICROSOFT_VARIANT_CLOCK_SEQUENCE_MASK,
            ],
            'e', 'f' => [
                self::FUTURE_VARIANT,
                self::FUTURE_VARIANT_BITS,
                $instance->clockSequenceHighBits & self::FUTURE_VARIANT_CLOCK_SEQUENCE_MASK,
            ],
        };

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
