<?php
namespace WPOrgSubmissionRules\Sniffs\ForbiddenTags;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class ForbiddenInlineTagsSniff implements Sniff
{
    public function register()
    {
        // We want to detect inline HTML and also PHP strings.
        return [T_INLINE_HTML, T_CONSTANT_ENCAPSED_STRING, T_DOUBLE_QUOTED_STRING];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $content = strtolower($tokens[$stackPtr]['content']);

        // Check for inline <script> or <style> tags in any type of content.
        if (strpos($content, '<script') !== false || strpos($content, '<style') !== false) {
            $phpcsFile->addError(
                'Inline <script> or <style> tags are forbidden.',
                $stackPtr,
                'ForbiddenTags'
            );
        }
    }
}