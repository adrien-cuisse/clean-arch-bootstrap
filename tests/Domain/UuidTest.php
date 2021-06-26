<?php

namespace Alphonse\CleanArch\Tests\Domain;

use Alphonse\CleanArch\Domain\UuidInterface;
use Alphonse\CleanArch\Domain\UuidV4;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Alphonse\CleanArch\Domain\Uuid
 */
final class UuidTest extends TestCase
{
    /**
     * @return UuidInterface - an instance to test
     */
    public function createInstance(): UuidInterface
    {
        return new UuidV4;
    }

    /**
     * @test
     * @covers ::toRfcUuidString
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
     * @covers ::toRfcUuidString
     */
    public function has_different_representation_for_each_instance(): void
    {
        // given 2 different Uuid
        $firstUuid = $this->createInstance();
        $secondUuid = $this->createInstance();

        // when using their RFC representation
        $firstRepresentation = $firstUuid->toRfcUuidString();
        $secondRepresentation = $secondUuid->toRfcUuidString();

        // then they should be different
        $this->assertNotEquals(
            expected: $firstRepresentation,
            actual: $secondRepresentation,
            message: "Different UUIDs should have different RFC representation",
        );
    }

    /**
     * @test
     * @covers ::toRfcUuidString
     */
    public function contains_fixed_version_number(): void
    {
        // given a new Uuid
        $uuid = $this->createInstance();

        // when extracting its version digit and its intended internal version
        $versionDigit = $uuid->toRfcUuidString()[14];
        $internalVersion = $uuid->getVersion();

        // then they should be the same
        $this->assertEquals(
            actual: $versionDigit,
            expected: $internalVersion,
            message: "UUID should have its version number as 13th digit, found {$versionDigit} instead of {$internalVersion}",
        );
    }

    /**
     * @test
     * @covers ::toRfcUuidString
     */
    public function contains_fixed_variant_bits(): void
    {
        // given a new Uuid
        $uuid = $this->createInstance();

        // when extracting its 9th byte of data (byte 8, digits 19 and 20)
        $rfcRepresentation = $uuid->toRfcUuidString();
        $ninthByteHexaString = substr($rfcRepresentation, 19, 2);
        sscanf($ninthByteHexaString, '%x', $ninthByte);

        // then it should have its two most significant bytes set to 10
        $this->assertEquals(
            actual: $ninthByte & 0b1100_0000,
            expected: 0b1000_0000,
            message: "UUID should have the 2 most significant bits of its 9th byte set to 0b10",
        );
    }
}
