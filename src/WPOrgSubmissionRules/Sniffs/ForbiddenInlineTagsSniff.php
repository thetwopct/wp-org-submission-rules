<?php
namespace WPOrgSubmissionRules\Sniffs;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class ForbiddenInlineTagsSniff implements Sniff
{
    public function register()
    {
        return [T_INLINE_HTML];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $content = strtolower($tokens[$stackPtr]['content']);

        if (strpos($content, '<script') !== false || strpos($content, '<style') !== false) {
            $phpcsFile->addError(
				'Inline <script> or <style> tags are forbidden.',
				$stackPtr,
				'ForbiddenTags'
			);
        }
    }
}