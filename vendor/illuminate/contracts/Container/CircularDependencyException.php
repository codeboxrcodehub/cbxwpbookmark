<?php

namespace CBXWPBookmarkScoped\Illuminate\Contracts\Container;

use Exception;
use CBXWPBookmarkScoped\Psr\Container\ContainerExceptionInterface;
class CircularDependencyException extends Exception implements ContainerExceptionInterface
{
    //
}
