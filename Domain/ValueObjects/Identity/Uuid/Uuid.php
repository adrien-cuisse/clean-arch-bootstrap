<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid;

use ReflectionClass;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;

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
     * The variant used by the Apollo Network Computing System
     *
     * @link https://en.wikipedia.org/wiki/Universally_unique_identifier#Variants
     */
    private const APOLLO_NCS_VARIANT = 0b0;

    /**
     * The variant used by default when using constructor, as defined in RFC4122
     *
     * @link https://datatracker.ietf.org/doc/html/rfc4122#section-4.1.1
     */
    private const RFC_VARIANT = 0b10;

    /**
     * How many bits the RFC variant takes
     */
    private const RFC_VARIANT_SIZE = 2;

    /**
     * The variant used by old Windows platforms
     *
     * @link https://en.wikipedia.org/wiki/Universally_unique_identifier#Variants
     */
    private const MICROSOFT_VARIANT = 0b110;

    /**
     * The variant reserved for future specification
     *
     * @link https://en.wikipedia.org/wiki/Universally_unique_identifier#Variants
     */
    private const FUTURE_VARIANT = 0b111;

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
     * @var int - the variant used, may differ from default when made from string, 2 most significants bits of byte 8
     *
     * @link https://datatracker.ietf.org/doc/html/rfc4122#section-4.1.1
     */
    private int $variant = self::RFC_VARIANT;

    /**
     * @var int - byte 8
     */
    private int $clockSequenceHighAndVariantByte;

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
        if (count($timestampLowBytes) !== 4) {
            throw new InvalidUuidTimestampLowBytesCountException($timestampLowBytes);
        }
        $this->timestampLowBytes = $this->clampToBytes($timestampLowBytes);

        if (count($timestampMidBytes) !== 2) {
            throw new InvalidUuidTimestampMidBytesCountException($timestampMidBytes);
        }
        $this->timestampMidBytes = $this->clampToBytes($timestampMidBytes);

        if (count($timestampHighBytes) !== 2) {
            throw new InvalidUuidTimestampHighBytesCountException($timestampHighBytes);
        }
        $this->timestampHighBytes = $this->clampToBytes($timestampHighBytes);
        $this->timestampHighBytes[0] &= self::TIMESTAMP_HIGH_BITS_MASK;

        if ($version > 0b0000_1111) {
            throw new InvalidUuidVersionException($version);
        }
        $this->version = $version;
        $this->versionBits = $this->version << 4;

        $variantBits = ($this->variant << (8 - self::RFC_VARIANT_SIZE));
        $this->clockSequenceHighAndVariantByte = $variantBits | $this->clampToByte($clockSequenceHighByte);
        $this->clockSequenceLowByte = $this->clampToByte($clockSequenceLowByte);

        if (count($nodeBytes) !== 6) {
            throw new InvalidUuidNodeBytesCountException($nodeBytes);
        }
        $this->nodeBytes = $this->clampToBytes($nodeBytes);
    }

    /**
     * @see UuidInterface
     */
    final public function version(): int
    {
        return $this->version;
    }

    /**
     * @see UuidInterface
     */
    final public function variant(): string
    {
        return match ($this->variant) {
            self::APOLLO_NCS_VARIANT => 'Apollo NCS (backward compatibility)',
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
    final public function rfcFormat(): string
    {
        $versionAndTimeHighBytes = [
            $this->versionBits | $this->timestampHighBytes[0],
            $this->timestampHighBytes[1]
        ];

        $clockSequenceAndVariantBytes = [
            $this->clockSequenceHighAndVariantByte,
            $this->clockSequenceLowByte
        ];

        return sprintf(
            "%s-%s-%s-%s-%s",
            $this->hexaStringFrom($this->timestampLowBytes),
            $this->hexaStringFrom($this->timestampMidBytes),
            $this->hexaStringFrom($versionAndTimeHighBytes),
            $this->hexaStringFrom($clockSequenceAndVariantBytes),
            $this->hexaStringFrom($this->nodeBytes)
        );
    }

    /**
     * @see IdentityInterface
     */
    final public function nativeFormat(): string
    {
        return $this->rfcFormat();
    }

    /**
     * @see Stringable
     */
    final public function __toString(): string
    {
        return $this->rfcFormat();
    }

    /**
     * @see ValueObjectInterface
     */
    final public function equals(ValueObjectInterface $other): bool
    {
        if ($other instanceof UuidInterface) {
            return $this->rfcFormat() === $other->rfcFormat();
        }

        return false;
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
            throw new InvalidUuidStringException($rfcUuidString);
        }

        $instance = (new ReflectionClass(static::class))->newInstanceWithoutConstructor();

        [$timestampLow, $timestampMid, $timestampHighAndVersion, $clockSequenceAndVariant, $node] = explode('-', $rfcUuidString);

        $instance->timestampLowBytes = sscanf(string: $timestampLow, format: '%2x%2x%2x%2x');
        $instance->timestampMidBytes = sscanf(string: $timestampMid, format: '%2x%2x');
        $instance->timestampHighBytes = sscanf(string: $timestampHighAndVersion, format: '%2x%2x');
        $instance->timestampHighBytes[0] &= self::TIMESTAMP_HIGH_BITS_MASK;

        $instance->versionBits = sscanf(string: $timestampHighAndVersion, format: '%2x')[0] & self::VERSION_BITS_MASK;
        $instance->version = $instance->versionBits >> 4;

        $variantDigit = sscanf(string: $clockSequenceAndVariant, format: '%1c')[0];
        $instance->variant = match ($variantDigit) {
            '0', '1', '2', '3', '4', '5', '6', '7' => self::APOLLO_NCS_VARIANT,
            '8', '9', 'a', 'b' => self::RFC_VARIANT,
            'c', 'd' => self::MICROSOFT_VARIANT,
            'e', 'f' => self::FUTURE_VARIANT,
        };

        $instance->clockSequenceHighAndVariantByte = sscanf(string: $clockSequenceAndVariant, format: '%2x')[0];
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
            callback: fn (string $ascii) => ord($ascii),
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
        $bytesCount = count($bytes);

        $binaryString = pack("C{$bytesCount}", ...$bytes);

        return bin2hex(string: $binaryString);
    }
}
