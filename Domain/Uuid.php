<?php

namespace Alphonse\CleanArch\Domain;

/**
 * @see UuidInterface
 */
abstract class Uuid implements UuidInterface
{
    /**
     * @var int[] - bytes 0 to 3
     */
    private array $timeLowBytes;

    /**
     * @var int[] - bytes 4 to 5
     */
    private array $timeMidBytes;

    /**
     * @var int - 4 most significant bits of the byte 6
     */
    private int $versionBits;

    /**
     * @var int[] - 4 least significant bits of byte 6, byte 7
     */
    private array $timeHighBytes;

    /**
     * @var int - 2 most significant bits of byte 8
     */
    private int $variantBits = 0b10 << 6;

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

    public function __construct()
    {
        $this->timeLowBytes = $this->clampToBytes(integers: $this->getTimeLowBytes());
        $this->timeMidBytes = $this->clampToBytes(integers: $this->getTimeMidBytes());
        $this->timeHighBytes = $this->clampToBytes(integers: $this->getTimeHighBytes());

        // only keep needed bits
        $this->timeHighBytes[0] &= 0b1111;
        $this->versionBits = ($this->getVersion() & 0b1111) << 4;
        $this->clockSeqHighBits = $this->getClockSeqHighByte() & 0b0011_1111;
        $this->clockSeqLowByte = $this->getClockSeqLowByte() & 0xff;

        $this->nodeBytes = $this->clampToBytes(integers: $this->getNodeBytes());
    }

    /**
     * @see UuidInterface
     */
    abstract public function getVersion(): int;

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

        $variantAndclockSeqHighByte = $this->variantBits | $this->clockSeqHighBits;

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
     * Generates random bytes, with bytes being unsigned integers between 0 and 255
     *
     * @param int $numberOfBytes - how many bytes to generate
     */
    final protected function randomBytes(int $numberOfBytes): array
    {
        $binaryString = random_bytes(length: $numberOfBytes);

        return array_map(
            callback: fn (string $ascii) => ord(character: $ascii) & 0xff,
            array: str_split($binaryString),
        );
    }
    /**
     * @return int[] - 4 unsigned chars
     */
    abstract protected function getTimeLowBytes(): array;

    /**
     * @return int[] - 2 unsigned chars
     */
    abstract protected function getTimeMidBytes(): array;

    /**
     * @return int[] - 2 unsigned chars, 4 most significants bits of byte at index 0 don't need to be removed
     */
    abstract protected function getTimeHighBytes(): array;

    /**
     * @return int - unsigned char, the most most significant bits don't need to be removed
     */
    abstract protected function getClockSeqHighByte(): int;

    /**
     * @return int - unsigned char
     */
    abstract protected function getClockSeqLowByte(): int;

    /**
     * @return int[] - 6 unsigned chars
     */
    abstract protected function getNodeBytes(): array;

    /**
     * Clamps the given integers to bytes, ensuring no invalid value is send from extending classes
     *
     * @param int[] $integers - the integers to turn to bytes
     *
     * @return - an array of bytes
     */
    private function clampToBytes(array $integers): array
    {
        return array_map(
            callback: fn (int $value) => $value & 0xff,
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
