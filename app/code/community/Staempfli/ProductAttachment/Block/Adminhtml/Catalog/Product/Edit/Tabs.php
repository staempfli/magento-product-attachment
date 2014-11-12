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
class Staempfli_ProductAttachment_Block_Adminhtml_Catalog_Product_Edit_Tabs extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add tab under Product Information section
     * Tab will not be added of product type is 'Downloadable'
     * Tab name : 'Product Files'
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if(Mage::registry('current_product')->getTypeID() !== Mage_Downloadable_Model_Product_Type::TYPE_DOWNLOADABLE)
        {
            $product = $this->getProduct();
            if (!($setId = $product->getAttributeSetId())) {
                $setId = $this->getRequest()->getParam('set', null);
            }
            if ($setId) {
                $this->addTab('staempfli_productattachment', array(
                        'label'     => Mage::helper('staempfli_productattachment')->__('Product Files'),
                        'content'   => $this->getLayout()
                            ->createBlock('staempfli_productattachment/adminhtml_catalog_product_edit_tab_list')->_toHtml(),
                    ));
            }
        }
    }
}