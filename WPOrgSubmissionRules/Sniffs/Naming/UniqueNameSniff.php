<?php
namespace WPOrgSubmissionRules\Sniffs\Naming;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class UniqueNameSniff implements Sniff
{
    /**
     * Required prefix (can be set via ruleset.xml)
     */
    public $requiredPrefix = '';

    /**
     * Register the tokens to look for (functions, classes, constants, actions, etc.)
     */
    public function register()
    {
        return [
            T_FUNCTION,  // For detecting functions
            T_CLASS,     // For detecting classes
            T_STRING,    // For detecting actions, filters, transients, etc.
            T_CONSTANT_ENCAPSED_STRING, // For transients or options
        ];
    }

    /**
     * Process the token
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $tokenContent = $tokens[$stackPtr]['content'];

        // Check for functions and classes that need prefixes
        if ($tokens[$stackPtr]['code'] === T_FUNCTION || $tokens[$stackPtr]['code'] === T_CLASS) {
            $this->checkPrefix($phpcsFile, $stackPtr, $tokenContent);
        }

        // Check for string tokens for transients, actions, options
        if ($tokens[$stackPtr]['code'] === T_STRING || $tokens[$stackPtr]['code'] === T_CONSTANT_ENCAPSED_STRING) {
            // Exclude standard WordPress functions and translations
            if (in_array($tokenContent, ['__', '_n', '_e', '_x'], true)) {
                return;
            }

            // For things like do_action(), set_transient(), etc.
            $this->checkPrefix($phpcsFile, $stackPtr, $tokenContent);
        }
    }

    /**
     * Check if the given name has the correct prefix.
     */
    private function checkPrefix(File $phpcsFile, $stackPtr, $name)
    {
        if (strpos($name, $this->requiredPrefix) !== 0) {
            $phpcsFile->addError(
                'The element "%s" must use the prefix "%s".',
                $stackPtr,
                'MissingPrefix',
                [$name, $this->requiredPrefix]
            );
        }
    }
}