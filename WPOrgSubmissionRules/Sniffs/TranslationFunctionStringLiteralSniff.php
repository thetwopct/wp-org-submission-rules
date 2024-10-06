<?php
namespace WPOrgSubmissionRules\Sniffs\I18n;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

class GettextStringLiteralSniff implements Sniff
{
    /**
     * Returns the tokens that this sniff is interested in.
     */
    public function register()
    {
        return [T_STRING];
    }

    /**
     * Processes the tokens and identifies errors.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $functionName = $tokens[$stackPtr]['content'];

        $gettextFunctions = [
            '__', '_e', '_x', 'esc_html__', 'esc_html_e', 'esc_html_x',
            'esc_attr__', 'esc_attr_e', 'esc_attr_x'
        ];

        if (in_array($functionName, $gettextFunctions, true) === false) {
            return;
        }

        $openingParenthesis = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $stackPtr);
        $closingParenthesis = $tokens[$openingParenthesis]['parenthesis_closer'];
        $parameters = $this->getFunctionParameters($phpcsFile, $openingParenthesis, $closingParenthesis);

        if (isset($parameters[0]) && $tokens[$parameters[0]]['code'] !== T_CONSTANT_ENCAPSED_STRING) {
            $phpcsFile->addError(
                'The text parameter of %s() must be a string literal, not a variable or function call.',
                $stackPtr,
                'TextNotLiteral',
                [$functionName]
            );
        }

        if (isset($parameters[1]) && $tokens[$parameters[1]]['code'] !== T_CONSTANT_ENCAPSED_STRING) {
            $phpcsFile->addError(
                'The text domain parameter of %s() must be a string literal.',
                $stackPtr,
                'TextDomainNotLiteral',
                [$functionName]
            );
        }
    }

    /**
     * Retrieves the parameters of a function call.
     */
    private function getFunctionParameters(File $phpcsFile, $openParenthesis, $closeParenthesis)
    {
        $tokens = $phpcsFile->getTokens();
        $params = [];
        $level = 0;

        for ($i = $openParenthesis + 1; $i < $closeParenthesis; $i++) {
            // Skip nested parentheses
            if ($tokens[$i]['code'] === T_OPEN_PARENTHESIS) {
                $level++;
            } elseif ($tokens[$i]['code'] === T_CLOSE_PARENTHESIS) {
                if ($level === 0) {
                    break;
                }
                $level--;
            }

            // Find commas, they separate parameters
            if ($tokens[$i]['code'] === T_COMMA && $level === 0) {
                $params[] = $i;
            }
        }

        // Add closing parenthesis as the end of the last parameter
        $params[] = $closeParenthesis;
        return $params;
    }
}