<?php

namespace CBXWPBookmarkScoped\Sabberworm\CSS\Tests\CSSList;

use PHPUnit\Framework\TestCase;
use CBXWPBookmarkScoped\Sabberworm\CSS\Comment\Commentable;
use CBXWPBookmarkScoped\Sabberworm\CSS\CSSList\KeyFrame;
use CBXWPBookmarkScoped\Sabberworm\CSS\Property\AtRule;
use CBXWPBookmarkScoped\Sabberworm\CSS\Renderable;
/**
 * @covers \Sabberworm\CSS\CSSList\KeyFrame
 */
final class KeyFrameTest extends TestCase
{
    /**
     * @var KeyFrame
     */
    protected $subject;
    private function setUpTestcase()
    {
        $this->subject = new KeyFrame();
    }
    /**
     * @test
     */
    public function implementsAtRule()
    {
        $this->setUpTestcase();
        self::assertInstanceOf(AtRule::class, $this->subject);
    }
    /**
     * @test
     */
    public function implementsRenderable()
    {
        $this->setUpTestcase();
        self::assertInstanceOf(Renderable::class, $this->subject);
    }
    /**
     * @test
     */
    public function implementsCommentable()
    {
        $this->setUpTestcase();
        self::assertInstanceOf(Commentable::class, $this->subject);
    }
}
