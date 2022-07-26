<?php
declare(strict_types=1);

namespace LessDomain\Identifier;

use LessValueObject\String\Format\Resource\Identifier;

/**
 * @deprecated use GeneratorService
 */
interface IdentifierService
{
    public function generate(): Identifier;
}
