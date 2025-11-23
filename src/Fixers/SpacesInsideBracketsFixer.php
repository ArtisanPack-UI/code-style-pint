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
 * Fixer to add spaces inside brackets for variable array indices (WordPress style).
 *
 * This fixer ensures that there is exactly one space after opening bracket
 * and before closing bracket when accessing arrays with variable indices.
 * Literal indices (strings, numbers) are not affected.
 */
final class SpacesInsideBracketsFixer extends AbstractFixer
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'There must be a space after opening bracket and before closing bracket for variable array indices.',
            []
        );
    }

    public function getName(): string
    {
        return 'ArtisanPackUI/spaces_inside_brackets';
    }

    public function getPriority(): int
    {
        // Run after other spacing fixers
        return 0;
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound('[');
    }

    protected function applyFix(SplFileInfo $file, Tokens $tokens): void
    {
        // First pass: identify array accesses with variable indices and store their positions
        $bracketPairs = [];

        for ($index = 0, $count = $tokens->count(); $index < $count; ++$index) {
            $token = $tokens[$index];

            if (! $token->equals('[')) {
                continue;
            }

            // Check if this is array access (not array declaration)
            if (! $this->isArrayAccess($tokens, $index)) {
                continue;
            }

            $closingBracketIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_INDEX_SQUARE_BRACE, $index);

            // Check if the content is a variable (not a literal)
            if ($this->hasVariableIndex($tokens, $index, $closingBracketIndex)) {
                $bracketPairs[] = [$index, $closingBracketIndex];
            }
        }

        // Second pass: fix brackets in reverse order to avoid index shifting issues
        foreach (array_reverse($bracketPairs) as [$openingIndex, $closingIndex]) {
            // Fix closing bracket first to avoid index shifts
            $this->fixClosingBracket($tokens, $closingIndex);
            // Then fix opening bracket
            $this->fixOpeningBracket($tokens, $openingIndex);
        }
    }

    private function isArrayAccess(Tokens $tokens, int $index): bool
    {
        $prevMeaningfulIndex = $tokens->getPrevMeaningfulToken($index);

        if (null === $prevMeaningfulIndex) {
            return false;
        }

        $prevToken = $tokens[$prevMeaningfulIndex];

        // Array access follows variables, $this, array access, function calls, or closing parenthesis
        return $prevToken->isGivenKind([T_VARIABLE, T_STRING])
            || $prevToken->equals(']')
            || $prevToken->equals(')')
            || $prevToken->isObjectOperator();
    }

    private function hasVariableIndex(Tokens $tokens, int $openingIndex, int $closingIndex): bool
    {
        for ($i = $openingIndex + 1; $i < $closingIndex; ++$i) {
            $token = $tokens[$i];

            // If we find a variable, it's a variable index
            if ($token->isGivenKind(T_VARIABLE)) {
                return true;
            }

            // If we find a literal string or number with no variables, it's not a variable index
            if ($token->isGivenKind([T_CONSTANT_ENCAPSED_STRING, T_LNUMBER, T_DNUMBER])) {
                // Check if there are any variables in the rest of the index
                $hasVariable = false;
                for ($j = $i + 1; $j < $closingIndex; ++$j) {
                    if ($tokens[$j]->isGivenKind(T_VARIABLE)) {
                        $hasVariable = true;
                        break;
                    }
                }

                return $hasVariable;
            }
        }

        return false;
    }

    private function fixOpeningBracket(Tokens $tokens, int $index): void
    {
        $nextMeaningfulIndex = $tokens->getNextMeaningfulToken($index);

        if (null === $nextMeaningfulIndex) {
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

    private function fixClosingBracket(Tokens $tokens, int $index): void
    {
        $prevMeaningfulIndex = $tokens->getPrevMeaningfulToken($index);

        if (null === $prevMeaningfulIndex) {
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
