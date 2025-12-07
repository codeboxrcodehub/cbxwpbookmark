<?php

declare (strict_types=1);
namespace CBXWPBookmarkScoped\Sabberworm\CSS\Tests\Unit\Position\Fixtures;

use CBXWPBookmarkScoped\Sabberworm\CSS\Position\Position;
use CBXWPBookmarkScoped\Sabberworm\CSS\Position\Positionable;
final class ConcretePosition implements Positionable
{
    use Position;
}
