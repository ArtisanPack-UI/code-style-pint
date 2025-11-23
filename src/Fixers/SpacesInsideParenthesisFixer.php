<?php

declare(strict_types=1);

namespace ArtisanPackUI\CodeStylePint\Fixers;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

/**
 * Fixer to add spaces inside parentheses (WordPress style).
 *
 * This fixer ensures that there is exactly one space after opening
 * parentheses and before closing parentheses in control structures,
 * function declarations, and function calls.
 */
final class SpacesInsideParenthesisFixer extends AbstractFixer
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'There must be a space after opening parenthesis and before closing parenthesis.',
            []
        );
    }

    public function getName(): string
    {
        return 'ArtisanPackUI/spaces_inside_parenthesis';
    }

    public function getPriority(): int
    {
        // Run after other spacing fixers
        return 0;
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound('(');
    }

    protected function applyFix(SplFileInfo $file, Tokens $tokens): void
    {
        for ($index = $tokens->count() - 1; $index >= 0; --$index) {
            $token = $tokens[$index];

            if (! $token->equals('(')) {
                continue;
            }

            $this->fixOpeningParenthesis($tokens, $index);
        }

        for ($index = 0, $count = $tokens->count(); $index < $count; ++$index) {
            $token = $tokens[$index];

            if (! $token->equals(')')) {
                continue;
            }

            $this->fixClosingParenthesis($tokens, $index);
        }
    }

    private function fixOpeningParenthesis(Tokens $tokens, int $index): void
    {
        $nextMeaningfulIndex = $tokens->getNextMeaningfulToken($index);

        if (null === $nextMeaningfulIndex) {
            return;
        }

        // Don't add space if the next token is a closing parenthesis (empty parentheses)
        if ($tokens[$nextMeaningfulIndex]->equals(')')) {
            return;
        }

        $nextIndex = $index + 1;

        // Check if there's already a space
        if ($tokens[$nextIndex]->isWhitespace()) {
            // Ensure it's exactly one space (not newline or multiple spaces)
            $content = $tokens[$nextIndex]->getContent();
            if (! str_contains($content, "\n") && $content !== ' ') {
                $tokens[$nextIndex] = new Token([T_WHITESPACE, ' ']);
            }
        } else {
            // Add a space
            $tokens->insertAt($index + 1, new Token([T_WHITESPACE, ' ']));
        }
    }

    private function fixClosingParenthesis(Tokens $tokens, int $index): void
    {
        $prevMeaningfulIndex = $tokens->getPrevMeaningfulToken($index);

        if (null === $prevMeaningfulIndex) {
            return;
        }

        // Don't add space if the previous token is an opening parenthesis (empty parentheses)
        if ($tokens[$prevMeaningfulIndex]->equals('(')) {
            return;
        }

        $prevIndex = $index - 1;

        // Check if there's already a space
        if ($tokens[$prevIndex]->isWhitespace()) {
            // Ensure it's exactly one space (not newline or multiple spaces)
            $content = $tokens[$prevIndex]->getContent();
            if (! str_contains($content, "\n") && $content !== ' ') {
                $tokens[$prevIndex] = new Token([T_WHITESPACE, ' ']);
            }
        } else {
            // Add a space
            $tokens->insertAt($index, new Token([T_WHITESPACE, ' ']));
        }
    }
}
