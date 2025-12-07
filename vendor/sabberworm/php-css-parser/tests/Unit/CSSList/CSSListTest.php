<?php

declare (strict_types=1);
namespace CBXWPBookmarkScoped\Sabberworm\CSS\Tests\Unit\CSSList;

use PHPUnit\Framework\TestCase;
use CBXWPBookmarkScoped\Sabberworm\CSS\CSSElement;
use CBXWPBookmarkScoped\Sabberworm\CSS\Tests\Unit\CSSList\Fixtures\ConcreteCSSList;
/**
 * @covers \Sabberworm\CSS\CSSList\CSSList
 */
final class CSSListTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function implementsCSSElement()
    {
        $subject = new ConcreteCSSList();
        self::assertInstanceOf(CSSElement::class, $subject);
    }
}
