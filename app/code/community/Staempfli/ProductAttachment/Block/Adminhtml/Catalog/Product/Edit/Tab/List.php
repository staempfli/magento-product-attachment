<?php
/**
 * This file is part of the Staempfli project.
 *
 * Staempfli_ProductAttachment is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * PHP version 5
 *
 * @category  Staempfli
 * @package   Staempfli_ProductAttachment
 * @author    Staempfli Team <webteam@staempfli.com>
 * @copyright 2014 Staempfli AG (http://http://www.staempfli.com/)
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version   $Id:$
 * @since     1.0.0
 */
class Staempfli_ProductAttachment_Block_Adminhtml_Catalog_Product_Edit_Tab_List extends Mage_Adminhtml_Block_Widget implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('staempfli/productattachment/list.phtml');
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * @return bool
     */
    public function isReadonly()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('staempfli_productattachment')->__('Product Files');
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('staempfli_productattachment')->__('Product Files');
    }

    /**
     * Check if tab can be displayed
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Check if tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }

}