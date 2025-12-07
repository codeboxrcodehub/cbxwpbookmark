<?php

declare (strict_types=1);
namespace CBXWPBookmarkScoped\Sabberworm\CSS\Tests\Unit\Value;

use PHPUnit\Framework\TestCase;
use CBXWPBookmarkScoped\Sabberworm\CSS\CSSElement;
use CBXWPBookmarkScoped\Sabberworm\CSS\Tests\Unit\Value\Fixtures\ConcreteValue;
use CBXWPBookmarkScoped\Sabberworm\CSS\Value\Value;
/**
 * @covers \Sabberworm\CSS\Value\Value
 */
final class ValueTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function implementsCSSElement()
    {
        $subject = new ConcreteValue();
        self::assertInstanceOf(CSSElement::class, $subject);
    }
}
