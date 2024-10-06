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
     * List of PHP native functions that shouldn't require a prefix.
     */
    private $phpFunctions = [
        'defined',
        'exit',
        'isset',
        'empty',
        'array_merge',
        'in_array',
        'count',
        'is_numeric',
        'is_array',
        'strpos',
        'strlen',
        // Add more PHP native functions as needed
    ];

    /**
     * List of PHP constants that shouldn't require a prefix.
     */
    private $phpConstants = [
        'ABSPATH',
        'PHP_VERSION',
        'PHP_OS',
        'E_ERROR',
        'E_WARNING',
        'E_PARSE',
        // Add more PHP constants as needed
    ];

    /**
     * Register the tokens to look for (classes, constants, and strings).
     */
    public function register()
    {
        return [
            T_CLASS,
            T_STRING,
            T_CONSTANT_ENCAPSED_STRING,
        ];
    }

    /**
     * Process the token
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $tokenContent = $tokens[$stackPtr]['content'];

        // Skip native PHP functions and constants
        if (in_array($tokenContent, $this->phpFunctions, true) || in_array(trim($tokenContent, '\'"'), $this->phpConstants, true)) {
            return;
        }

        // Check class names for prefix
        if ($tokens[$stackPtr]['code'] === T_CLASS) {
            $this->checkPrefix($phpcsFile, $stackPtr, $tokenContent);
        }

        // Check string or constant values
        if ($tokens[$stackPtr]['code'] === T_STRING || $tokens[$stackPtr]['code'] === T_CONSTANT_ENCAPSED_STRING) {
            // Skip gettext functions like __, _n, etc.
            if (in_array($tokenContent, ['__', '_n', '_e', '_x'], true)) {
                return;
            }

            $this->checkPrefix($phpcsFile, $stackPtr, $tokenContent);
        }
    }

    /**
     * Check if the given name has the correct prefix.
     */
    private function checkPrefix(File $phpcsFile, $stackPtr, $name)
    {
        // Case-insensitive check for prefix
        if (stripos($name, $this->requiredPrefix) !== 0) {
            $phpcsFile->addError(
                'The element "%s" must use the prefix "%s".',
                $stackPtr,
                'MissingPrefix',
                [$name, $this->requiredPrefix]
            );
        }
    }
}