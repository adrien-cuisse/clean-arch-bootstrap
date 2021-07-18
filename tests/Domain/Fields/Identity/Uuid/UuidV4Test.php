<?php

namespace Alphonse\CleanArch\Tests\Domain\Fields\Identity\Uuid;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\UuidV4;
use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\UuidV4Interface;

/**
 * @coversDefaultClass Alphonse\CleanArch\Domain\Fields\Identity\Uuid\UuidV4
 * @uses Alphonse\CleanArch\Domain\Fields\Identity\Uuid\Uuid
 * @uses Alphonse\CleanArch\Domain\Fields\Identity\Uuid\UuidV4
 */
final class UuidV4Test extends TestCase
{
    public function createInstance(): UuidV4Interface
    {
        return new UuidV4;
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::randomByte
     * @covers ::randomBytes
     */
    public function is_version_4(): void
    {
        // given an UuidV4
        $uuid = $this->createInstance();

        // when checking its version
        $version = $uuid->getVersion();

        // then it should be 4
        $this->assertEquals(
            expected: 4,
            actual: $version,
            message: 'UuidV4 version should be 4'
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::randomByte
     * @covers ::randomBytes
     */
    public function each_instance_has_unique_representation(): void
    {
        // given a set of 100 Uuids
        $uuids = [];
        while (count($uuids) < 100) {
            $uuids[] = (string) $this->createInstance();
        }

        // when looking for duplicate uuids
        $collisions = [];
        foreach ($uuids as $index => $uuid) {
            if (in_array($uuid, array_slice($uuids, $index + 1))) {
                $collisions[] = $uuid;
            }
        }

        // then there should be none
        $this->assertCount(
            expectedCount: 0,
            haystack: $collisions,
            message: sprintf(
                "Expected all Uuids to be unique, got collisions on %s",
                implode(', ' . PHP_EOL, $collisions),
            ),
        );
    }
}
