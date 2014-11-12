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
 * @author    Staempfli Webteam <webteam@staempfli.com>
 * @copyright 2014 Staempfli AG (http://http://www.staempfli.com/)
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version   $Id:$
 * @since     1.0.0
 */
class Staempfli_ProductAttachment_Model_File extends Mage_Core_Model_Abstract
{
    /**
     * Inits the resource model and resource collection model
     */
    protected function _construct()
    {
        $this->_init('staempfli_productattachment/file');
    }

    /**
     * Return the files by product id
     *
     * @param $id
     * @param int $store
     * @return mixed
     */
    public function getFilesByProductId($id, $store = 0)
    {
        $collection = Mage::getModel('staempfli_productattachment/file')->getCollection()->addFieldToFilter('product_id',$id);
            if(intval($store) !== 0) {
                $collection->addFieldToFilter('store_id',$store);
            }
            $collection->setOrder('sort_order', 'ASC');

        return $collection;
    }

    /**
     * Return the highest current sort order
     * @param $product_id
     * @return int
     */
    public function getCurrentSortOrder($product_id)
    {
        $current = Mage::getModel('staempfli_productattachment/file')->getCollection()
            ->addFieldToFilter('product_id', $product_id)
            ->setOrder('sort_order', 'DESC');

        if($sortOrder = $current->getFirstItem()->getSortOrder()) {
            return $sortOrder;
        }
        return 0;
    }

    /**
     * Store file data in database
     *
     * @param $product_id
     * @param $data
     * @return $this
     */
    public function addFile($product_id, $data)
    {
        $fileData = array();
        $fileData['product_id'] = $product_id;

        $currentSortOrder = $this->getCurrentSortOrder($product_id);
        $fileData['sort_order'] = intval($currentSortOrder) + 1;

        foreach($data as $key => $value) {
            $fileData[$key] = $value;
        }
        $this->setData($fileData);

        try {
            $this->save();
        } catch(Exception $e) {
            Mage::log($e->getMessage(), Zend_log::ERR, Staempfli_ProductAttachment_Helper_Data::LOG_FILE);
        }

        return $this;
    }

    /**
     * Update a file entry
     *
     * @param $file_id
     * @param array $data
     * @return $this
     */
    public function updateFile($file_id, $data = array())
    {
        $file = $this->load($file_id);

        if(isset($data['title'])) {
            $file->setTitle($data['title']);
        }

        if(isset($data['description'])) {
            $file->setDescription($data['description']);
        }

        if(isset($data['sort_order'])) {
            $file->setSortOrder($data['sort_order']);
        }

        if(isset($data['updated_at'])) {
            $file->setUpdatedAt(time());
        }

        try {
            $file->save();
        } catch(Exception $e) {
            Mage::log($e->getMessage(), Zend_log::ERR, Staempfli_ProductAttachment_Helper_Data::LOG_FILE);
        }
        return $this;
    }

    /**
     * Delete a file from database
     *
     * @param $file_id
     * @return $this
     */
    public function deleteFile($file_id)
    {
        $file = $this->load($file_id);
        try {
            $file->delete();
        } catch(Exception $e) {
            Mage::log($e->getMessage(), Zend_log::ERR, Staempfli_ProductAttachment_Helper_Data::LOG_FILE);
        }
        return $this;
    }

}