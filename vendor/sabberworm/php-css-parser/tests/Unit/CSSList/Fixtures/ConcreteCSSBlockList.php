<?php

declare (strict_types=1);
namespace CBXWPBookmarkScoped\Sabberworm\CSS\Tests\Unit\CSSList\Fixtures;

use CBXWPBookmarkScoped\Sabberworm\CSS\CSSList\CSSBlockList;
use CBXWPBookmarkScoped\Sabberworm\CSS\OutputFormat;
final class ConcreteCSSBlockList extends CSSBlockList
{
    /**
     * @return never
     */
    public function isRootList()
    {
        throw new \BadMethodCallException('Not implemented', 1740395831);
    }
    /**
     * @param OutputFormat|null $outputFormat
     *
     * @return never
     */
    public function render($outputFormat)
    {
        throw new \BadMethodCallException('Not implemented', 1740395836);
    }
}
