<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Barcode
 * @subpackage ObjectNew1
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Error.php 23775 2011-03-01 17:25:24Z ralph $
 */

/** @see Zend_Barcode_ObjectNew1_ObjectNew1Abstract */
require_once 'Zend/Barcode/ObjectNew1/ObjectNew1Abstract.php';

/**
 * Class for generate Barcode
 *
 * @category   Zend
 * @package    Zend_Barcode
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Barcode_ObjectNew1_Error extends Zend_Barcode_ObjectNew1_ObjectNew1Abstract
{
    /**
     * All texts are accepted
     * @param string $value
     * @return boolean
     */
    public function validateText($value)
    {
        return true;
    }

    /**
     * Height is forced
     * @return integer
     */
    public function getHeight($recalculate = false)
    {
        return 40;
    }

    /**
     * Width is forced
     * @return integer
     */
    public function getWidth($recalculate = false)
    {
        return 400;
    }

    /**
     * Reset precedent instructions
     * and draw the error message
     * @return array
     */
    public function draw()
    {
        $this->_instructions = array();
        $this->_addText('ERROR:', 10, array(5 , 18), $this->_font, 0, 'left');
        $this->_addText($this->_text, 10, array(5 , 32), $this->_font, 0, 'left');
        return $this->_instructions;
    }

    /**
     * For compatibility reason
     * @return void
     */
    protected function _prepareBarcode()
    {
    }

    /**
     * For compatibility reason
     * @return void
     */
    protected function _checkParams()
    {
    }

    /**
     * For compatibility reason
     * @return void
     */
    protected function _calculateBarcodeWidth()
    {
    }
}
