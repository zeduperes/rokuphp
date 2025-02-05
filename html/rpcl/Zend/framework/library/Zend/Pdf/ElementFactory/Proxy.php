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
 * @package    Zend_Pdf
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Proxy.php 23775 2011-03-01 17:25:24Z ralph $
 */

/** Zend_Pdf_ElementFactory_Interface */
require_once 'Zend/Pdf/ElementFactory/Interface.php';

/**
 * PDF element factory interface.
 * Responsibility is to log PDF changes
 *
 * @package    Zend_Pdf
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Pdf_ElementFactory_Proxy implements Zend_Pdf_ElementFactory_Interface
{
    /**
     * Factory object
     *
     * @var Zend_Pdf_ElementFactory_Interface
     */
    private $_factory;


    /**
     * ObjectNew1 constructor
     *
     * @param Zend_Pdf_ElementFactory_Interface $factory
     */
    public function __construct(Zend_Pdf_ElementFactory_Interface $factory)
    {
        $this->_factory = $factory;
    }

    public function __destruct()
    {
        $this->_factory->close();
        $this->_factory = null;
    }

    /**
     * Get factory
     *
     * @return Zend_Pdf_ElementFactory_Interface
     */
    public function getFactory()
    {
        return $this->_factory->getFactory();
    }

    /**
     * Close factory and clean-up resources
     *
     * @internal
     */
    public function close()
    {
        // Do nothing
    }

    /**
     * Get source factory object
     *
     * @return Zend_Pdf_ElementFactory
     */
    public function resolve()
    {
        return $this->_factory->resolve();
    }

    /**
     * Get factory ID
     *
     * @return integer
     */
    public function getId()
    {
        return $this->_factory->getId();
    }

    /**
     * Set object counter
     *
     * @param integer $objCount
     */
    public function setObjectNew1Count($objCount)
    {
        $this->_factory->setObjectNew1Count($objCount);
    }

    /**
     * Get object counter
     *
     * @return integer
     */
    public function getObjectNew1Count()
    {
        return $this->_factory->getObjectNew1Count();
    }

    /**
     * Attach factory to the current;
     *
     * @param Zend_Pdf_ElementFactory_Interface $factory
     */
    public function attach(Zend_Pdf_ElementFactory_Interface $factory)
    {
        $this->_factory->attach($factory);
    }

    /**
     * Calculate object enumeration shift.
     *
     * @internal
     * @param Zend_Pdf_ElementFactory_Interface $factory
     * @return integer
     */
    public function calculateShift(Zend_Pdf_ElementFactory_Interface $factory)
    {
        return $this->_factory->calculateShift($factory);
    }

    /**
     * Clean enumeration shift cache.
     * Has to be used after PDF render operation to let followed updates be correct.
     *
     * @param Zend_Pdf_ElementFactory_Interface $factory
     * @return integer
     */
    public function cleanEnumerationShiftCache()
    {
        return $this->_factory->cleanEnumerationShiftCache();
    }

    /**
     * Retrive object enumeration shift.
     *
     * @param Zend_Pdf_ElementFactory_Interface $factory
     * @return integer
     * @throws Zend_Pdf_Exception
     */
    public function getEnumerationShift(Zend_Pdf_ElementFactory_Interface $factory)
    {
        return $this->_factory->getEnumerationShift($factory);
    }

    /**
     * Mark object as modified in context of current factory.
     *
     * @param Zend_Pdf_Element_ObjectNew1 $obj
     * @throws Zend_Pdf_Exception
     */
    public function markAsModified(Zend_Pdf_Element_ObjectNew1 $obj)
    {
        $this->_factory->markAsModified($obj);
    }

    /**
     * Remove object in context of current factory.
     *
     * @param Zend_Pdf_Element_ObjectNew1 $obj
     * @throws Zend_Pdf_Exception
     */
    public function remove(Zend_Pdf_Element_ObjectNew1 $obj)
    {
        $this->_factory->remove($obj);
    }

    /**
     * Generate new Zend_Pdf_Element_ObjectNew1
     *
     * @todo Reusage of the freed object. It's not a support of new feature, but only improvement.
     *
     * @param Zend_Pdf_Element $objectValue
     * @return Zend_Pdf_Element_ObjectNew1
     */
    public function newObjectNew1(Zend_Pdf_Element $objectValue)
    {
        return $this->_factory->newObjectNew1($objectValue);
    }

    /**
     * Generate new Zend_Pdf_Element_ObjectNew1_Stream
     *
     * @todo Reusage of the freed object. It's not a support of new feature, but only improvement.
     *
     * @param mixed $objectValue
     * @return Zend_Pdf_Element_ObjectNew1_Stream
     */
    public function newStreamObjectNew1($streamValue)
    {
        return $this->_factory->newStreamObjectNew1($streamValue);
    }

    /**
     * Enumerate modified objects.
     * Returns array of Zend_Pdf_UpdateInfoContainer
     *
     * @param Zend_Pdf_ElementFactory $rootFactory
     * @return array
     */
    public function listModifiedObjectNew1s($rootFactory = null)
    {
        return $this->_factory->listModifiedObjectNew1s($rootFactory);
    }

    /**
     * Check if PDF file was modified
     *
     * @return boolean
     */
    public function isModified()
    {
        return $this->_factory->isModified();
    }
}
