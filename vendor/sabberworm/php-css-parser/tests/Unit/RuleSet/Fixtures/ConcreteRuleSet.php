<?php

declare (strict_types=1);
namespace CBXWPBookmarkScoped\Sabberworm\CSS\Tests\Unit\RuleSet\Fixtures;

use CBXWPBookmarkScoped\Sabberworm\CSS\OutputFormat;
use CBXWPBookmarkScoped\Sabberworm\CSS\RuleSet\RuleSet;
final class ConcreteRuleSet extends RuleSet
{
    /**
     * @param OutputFormat|null $outputFormat
     *
     * @return never
     */
    public function render($outputFormat)
    {
        throw new \BadMethodCallException('Nothing to see here :/', 1744067015);
    }
}
