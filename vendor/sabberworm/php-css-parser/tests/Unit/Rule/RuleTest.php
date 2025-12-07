<?php

namespace CBXWPBookmarkScoped\Sabberworm\CSS\Tests\Unit\Rule;

use PHPUnit\Framework\TestCase;
use CBXWPBookmarkScoped\Sabberworm\CSS\CSSElement;
use CBXWPBookmarkScoped\Sabberworm\CSS\Parsing\ParserState;
use CBXWPBookmarkScoped\Sabberworm\CSS\Settings;
use CBXWPBookmarkScoped\Sabberworm\CSS\Rule\Rule;
use CBXWPBookmarkScoped\Sabberworm\CSS\Value\RuleValueList;
use CBXWPBookmarkScoped\Sabberworm\CSS\Value\Value;
use CBXWPBookmarkScoped\Sabberworm\CSS\Value\ValueList;
/**
 * @covers \Sabberworm\CSS\Rule\Rule
 */
final class RuleTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function implementsCSSElement()
    {
        $subject = new Rule('beverage-container');
        self::assertInstanceOf(CSSElement::class, $subject);
    }
    /**
     * @return array<string, array{0: string, 1: list<class-string>}>
     */
    public static function provideRulesAndExpectedParsedValueListTypes()
    {
        return ['src (e.g. in @font-face)' => ["\r\n                    src: url('../fonts/open-sans-italic-300.woff2') format('woff2'),\r\n                         url('../fonts/open-sans-italic-300.ttf') format('truetype');\r\n                ", [RuleValueList::class, RuleValueList::class]]];
    }
    /**
     * @test
     *
     * @param string $rule
     * @param list<class-string> $expectedTypeClassnames
     *
     * @dataProvider provideRulesAndExpectedParsedValueListTypes
     */
    public function parsesValuesIntoExpectedTypeList($rule, array $expectedTypeClassnames)
    {
        $subject = Rule::parse(new ParserState($rule, Settings::create()));
        $value = $subject->getValue();
        self::assertInstanceOf(ValueList::class, $value);
        $actualClassnames = \array_map(
            /**
             * @param Value|string $component
             * @return string
             */
            static function ($component) {
                return \is_string($component) ? 'string' : \get_class($component);
            },
            $value->getListComponents()
        );
        self::assertSame($expectedTypeClassnames, $actualClassnames);
    }
}
