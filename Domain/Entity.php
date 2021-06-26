<?php

namespace Alphonse\CleanArch\Domain;

/**
 * A business object
 */
abstract class Entity implements EntityInterface
{
    use Identifiable;
}
