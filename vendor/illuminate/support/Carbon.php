<?php

namespace CBXWPBookmarkScoped\Illuminate\Support;

use CBXWPBookmarkScoped\Carbon\Carbon as BaseCarbon;
use CBXWPBookmarkScoped\Carbon\CarbonImmutable as BaseCarbonImmutable;
class Carbon extends BaseCarbon
{
    /**
     * {@inheritdoc}
     */
    public static function setTestNow($testNow = null)
    {
        BaseCarbon::setTestNow($testNow);
        BaseCarbonImmutable::setTestNow($testNow);
    }
}
