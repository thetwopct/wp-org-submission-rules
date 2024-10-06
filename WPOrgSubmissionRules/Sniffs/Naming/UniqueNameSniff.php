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
            T_FUNCTION,
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

        if ($tokens[$stackPtr]['code'] === T_FUNCTION || $tokens[$stackPtr]['code'] === T_CLASS) {
            $this->checkPrefix($phpcsFile, $stackPtr, $tokenContent);
        }

        if ($tokens[$stackPtr]['code'] === T_STRING || $tokens[$stackPtr]['code'] === T_CONSTANT_ENCAPSED_STRING) {
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