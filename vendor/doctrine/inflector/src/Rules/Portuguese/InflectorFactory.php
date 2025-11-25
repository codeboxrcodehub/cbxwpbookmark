<?php

declare (strict_types=1);
namespace CBXWPBookmarkScoped\Doctrine\Inflector\Rules\Portuguese;

use CBXWPBookmarkScoped\Doctrine\Inflector\GenericLanguageInflectorFactory;
use CBXWPBookmarkScoped\Doctrine\Inflector\Rules\Ruleset;
final class InflectorFactory extends GenericLanguageInflectorFactory
{
    protected function getSingularRuleset(): Ruleset
    {
        return Rules::getSingularRuleset();
    }
    protected function getPluralRuleset(): Ruleset
    {
        return Rules::getPluralRuleset();
    }
}
