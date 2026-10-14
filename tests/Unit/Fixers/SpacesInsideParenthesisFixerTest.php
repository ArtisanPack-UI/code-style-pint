<?php

declare(strict_types=1);

use ArtisanPackUI\CodeStylePint\Fixers\SpacesInsideParenthesisFixer;
use PhpCsFixer\Tokenizer\Tokens;

function fixParenthesisSpacing(string $code): string
{
    Tokens::clearCache();

    $tokens = Tokens::fromCode($code);

    (new SpacesInsideParenthesisFixer)->fix(new SplFileInfo(__FILE__), $tokens);

    return $tokens->generateCode();
}

describe('SpacesInsideParenthesisFixer', function () {
    it('adds a space inside both parentheses', function () {
        expect(fixParenthesisSpacing("<?php\nfoo(\$a, \$b);\n"))
            ->toBe("<?php\nfoo( \$a, \$b );\n");
    });

    it('leaves empty parentheses alone', function () {
        expect(fixParenthesisSpacing("<?php\nfoo();\n"))->toBe("<?php\nfoo();\n");
    });

    it('normalises extra spaces to one', function () {
        expect(fixParenthesisSpacing("<?php\nfoo(   \$a   );\n"))->toBe("<?php\nfoo( \$a );\n");
    });

    it('keeps a newline before a closing parenthesis', function () {
        $code = "<?php\nfoo(\n    \$a,\n);\n";

        expect(fixParenthesisSpacing($code))->toBe($code);
    });

    it('fixes closing parentheses at the end of a long file', function () {
        // Every opening parenthesis gains a token, shifting the closing
        // parentheses near the end of the file past the token count the
        // closing-paren pass started with.
        $calls = str_repeat("foo(\$a);\n", 50);

        $fixed = fixParenthesisSpacing("<?php\n".$calls."function last(array \$style, string \$prefix): bool {}\n");

        expect($fixed)->toContain('function last( array $style, string $prefix ): bool {}')
            ->and(substr_count($fixed, 'foo( $a );'))->toBe(50)
            ->and($fixed)->not->toContain('$prefix)');
    });
});
