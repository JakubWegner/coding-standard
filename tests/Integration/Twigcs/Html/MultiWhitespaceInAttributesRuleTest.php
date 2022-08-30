<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\CodingStandard\Tests\Integration\Twigcs\Html;

use BitBag\CodingStandard\Tests\Integration\BaseIntegrationTest;
use BitBag\CodingStandard\Twigcs\Rule\Html\MultiWhitespaceInAttributesRule;
use BitBag\CodingStandard\Twigcs\Ruleset\Ruleset;
use BitBag\CodingStandard\Twigcs\Util\HtmlUtil;
use FriendsOfTwig\Twigcs\Validator\Violation;

class MultiWhitespaceInAttributesRuleTest extends BaseIntegrationTest
{
    /** @var MultiWhitespaceInAttributesRule */
    private $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rule = new MultiWhitespaceInAttributesRule(Violation::SEVERITY_ERROR, new HtmlUtil());
    }

    public function test_it_returns_violation_when_are_multiple_spaces_in_attributes()
    {
        $html = '<div class="someClass"      id="someId">some content</div>';
        $tokenStream = $this->getFinalTokenStream($html);

        $violations = $this->rule->check($tokenStream);

        self::assertIsArray($violations);
        self::assertCount(1, $violations);
        self::assertInstanceOf(Violation::class, $violations[0]);

        self::assertEquals(1, $violations[0]->getLine());
        self::assertEquals(23, $violations[0]->getColumn());
        self::assertEquals(sprintf(Ruleset::ERROR_MULTIPLE_WHITESPACES, 'div'), $violations[0]->getReason());
    }

    public function test_its_ok_when_there_are_not_multiple_spaces_in_attributes()
    {
        $html = '<div class="someClass" id="someId">some content</div> <span id="someId2">some content</span>';
        $tokenStream = $this->getFinalTokenStream($html);

        $violations = $this->rule->check($tokenStream);

        self::assertIsArray($violations);
        self::assertCount(0, $violations);
    }
}
