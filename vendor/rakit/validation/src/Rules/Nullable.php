<?php

namespace CBXWPBookmarkScoped\Rakit\Validation\Rules;

use CBXWPBookmarkScoped\Rakit\Validation\Rule;
class Nullable extends Rule
{
    /**
     * Check the $value is valid
     *
     * @param mixed $value
     * @return bool
     */
    public function check($value): bool
    {
        return \true;
    }
}
