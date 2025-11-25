<?php

declare (strict_types=1);
namespace CBXWPBookmarkScoped\Carbon\Doctrine;

use CBXWPBookmarkScoped\Carbon\Carbon;
use DateTime;
use CBXWPBookmarkScoped\Doctrine\DBAL\Platforms\AbstractPlatform;
use CBXWPBookmarkScoped\Doctrine\DBAL\Types\VarDateTimeType;
class DateTimeType extends VarDateTimeType implements CarbonDoctrineType
{
    /** @use CarbonTypeConverter<Carbon> */
    use CarbonTypeConverter;
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Carbon
    {
        return $this->doConvertToPHPValue($value);
    }
}
