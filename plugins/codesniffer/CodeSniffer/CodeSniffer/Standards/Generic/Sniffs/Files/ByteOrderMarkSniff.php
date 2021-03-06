<?php
/**
 * Generic_Sniffs_Files_ByteOrderMarkSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2011 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Generic_Sniffs_Files_ByteOrderMarkSniff.
 *
 * A simple sniff for detecting BOMs that may corrupt application work.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Piotr Karas <office@mediaself.pl>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2010-2011 mediaSELF Sp. z o.o.
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.3.5
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 * @see       http://en.wikipedia.org/wiki/Byte_order_mark
 */
class Generic_Sniffs_Files_ByteOrderMarkSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * List of supported BOM definitions.
     *
     * Use encoding names as keys and hex BOM representations as values.
     *
     * @var array
     */
    public $bomDefinitions = array(
                              'UTF-8'       => 'efbbbf',
                              'UTF-16 (BE)' => 'feff',
                              'UTF-16 (LE)' => 'fffe',
                             );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_INLINE_HTML);

    }//end register()


    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr )
    {
        // The BOM will be the very first token in the file.
        if ($stackPtr !== 0) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        foreach ($this->bomDefinitions as $bomName => $expectedBomHex) {
            $bomByteLength = (strlen($expectedBomHex) / 2);
            $htmlBomHex    = bin2hex(substr($tokens[$stackPtr]['content'], 0, $bomByteLength));
            if ($htmlBomHex === $expectedBomHex) {
                $errorData = array($bomName);
                $error     = _AM_XOOPSSECURE_CODESNIFFER_FCBOMCA;
                $phpcsFile->addError($error, $stackPtr, 'Found', $errorData);
                break;
            }
        }

    }//end process()


}//end class
;
