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
class Staempfli_ProductAttachment_Adminhtml_ProductattachmentController extends Mage_Adminhtml_Controller_Action
{
    public function uploadAction()
    {
        $product_id = $this->getRequest()->getParam('product');
        $store      = $this->getRequest()->getParam('store');

        if($_FILES) {
            $fileModel  = Mage::getModel('staempfli_productattachment/file');
            $files      = $_FILES['file_data'];
            $fileCount  = count($files['name']);
            $fileData   = array();
            $uploadPath = Mage::helper('staempfli_productattachment')->getUploadDir();

            // prepare files data array
            for($x=0;$x<$fileCount;$x++) {
                $fileData[$x]['name']       = $files['name'][$x];
                $fileData[$x]['type']       = $files['type'][$x];
                $fileData[$x]['tmp_name']   = $files['tmp_name'][$x];
                $fileData[$x]['error']      = $files['error'][$x];
                $fileData[$x]['size']       = $files['size'][$x];
            }

            foreach($fileData as $data) {
                $filename   = pathinfo($data['name'], PATHINFO_FILENAME);
                $extension  = pathinfo($data['name'], PATHINFO_EXTENSION);
                $name       = $filename . '.' . $extension;
                $file       = $uploadPath . DS . $name;

                $fileInfo = array(
                    'filename'      => $name,
                    'store_id'      => $store,
                    'title'         => '',
                    'description'   => ''
                );

                $exist = file_exists($file);

                if(move_uploaded_file($data['tmp_name'], $file)) {
                    if(!$exist) {
                        $fileModel->addFile($product_id, $fileInfo);
                    } else {
                        $fileModel->updateFile($product_id, $fileInfo);
                    }
                }
            }

        }
        $this->_redirect('*/productattachment/form/product/' . $product_id . '/store/' . $store);

    }

    public function formAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Return a list with all attached files for a product
     *
     * @param $product_id
     * @param $store_id
     */
    public function listAction($product_id, $store_id)
    {
        $filesArray     = array();
        $sessionFormKey = Mage::getSingleton('core/session')->getFormKey();
        $productId      = ($product_id) ? $product_id : $this->getRequest()->getParam('product_id');
        $formKey        = $this->getRequest()->getParam('form_key');
        $storeId        = ($store_id) ? $store_id : $this->getRequest()->getParam('store_id');

        // if $product_id & $store are set directly, we bypass the form key check
        if($product_id && $store_id) {
            $sessionFormKey = 0;
            $formKey        = 0;
        }

        if($formKey && $productId && $sessionFormKey === $formKey) {
            $fileCollection = Mage::getModel('staempfli_productattachment/file')->getFilesByProductId($productId);
            if(count($fileCollection) > 0) {
                foreach($fileCollection as $row) {
                    $filesArray[] = array(
                        'id'            => $row->getId(),
                        'filename'      => $row->getFilename(),
                        'title'         => ($row->getTitle()) ? $row->getTitle() : '',
                        'description'   => ($row->getDescription()) ? $row->getDescription() : '',
                        'created_at'    => $row->getCreatedAt(),
                        'updated_at'    => $row->getUpdatedAt(),
                        'sort_order'    => $row->getSortOrder(),
                        'store_id'      => $row->getStoreId(),
                        'store_name'    => Mage::helper('staempfli_productattachment')->getStoreNameById($row->getStoreId())
                    );
                    $data = $this->_prepareOutputData(
                        'success',
                        $filesArray,
                        'list'
                    );
                }
            } else {
                $data = $this->_prepareOutputData(
                    'error',
                    Mage::helper('staempfli_productattachment')->__('No records found.'),
                    'list'
                );
            }
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($data));
        return;
    }


    /**
     * Update  input field values
     */
    public function updateAction()
    {
        $updateParams   = array();
        $sessionFormKey = Mage::getSingleton('core/session')->getFormKey();
        $product_id     = $this->getRequest()->getParam('product_id');
        $formKey        = $this->getRequest()->getParam('form_key');
        $params         = $this->getRequest()->getParams();

        if($formKey && $product_id && $sessionFormKey === $formKey) {
            $fileModel = Mage::getModel('staempfli_productattachment/file');
            $allowedParams = array(
                'sort_order',
                'description',
                'title'
            );

            foreach($params as $key => $value) {
                foreach($allowedParams as $param) {
                    if(stripos($key, $param) !== false) {
                        $id = str_replace($param . '_', '', $key);
                        $updateParams[$id][str_replace('_' . $id, '', $key)] = $value;
                    }
                }
            }

            foreach($updateParams as $file_id => $fileData) {
                $fileData['product_id'] = $product_id;
                $fileModel->updateFile($file_id, $fileData);
            }

            $this->listAction($product_id, 0);
            return;
        } else {
            $data = $this->_prepareOutputData(
                'error',
                Mage::helper('staempfli_productattachment')->__('There was a problem during update.'),
                'update'
            );
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($data));
        return;
    }

    /**
     * Delete a file
     */
    public function deleteAction()
    {
        $sessionFormKey = Mage::getSingleton('core/session')->getFormKey();
        $file_id        = $this->getRequest()->getParam('file_id');
        $product_id     = $this->getRequest()->getParam('product_id');
        $formKey        = $this->getRequest()->getParam('form_key');

        if($formKey && $file_id && $sessionFormKey === $formKey) {
            $fileModel      = Mage::getModel('staempfli_productattachment/file');
            $fileCollection = $fileModel->getCollection()
                ->addFieldToFilter('file_id', $file_id);

            if($filename = $fileCollection->getFirstItem()->getFilename()) {
                if(unlink(Mage::helper('staempfli_productattachment')->getUploadDir() . DS . $filename)) {
                    $fileModel->deleteFile($file_id);
                    $this->listAction($product_id, 0);
                    return;
                } else {
                    $data = $this->_prepareOutputData(
                        'error',
                        Mage::helper('staempfli_productattachment')->__('Unable to delete File!'),
                        'delete'
                    );
                }
            } else {
                $data = $this->_prepareOutputData(
                    'error',
                    Mage::helper('staempfli_productattachment')->__('File not found!'),
                    'delete'
                );
            }
        } else {
            $data = $this->_prepareOutputData(
                'error',
                Mage::helper('staempfli_productattachment')->__('Wrong params.'),
                'delete'
            );
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($data));
        return;
    }

    /**
     * Standardized output format
     *
     * @param $status
     * @param $content
     * @param string $type
     * @return array
     */
    protected function _prepareOutputData($status, $content, $type = 'list')
    {
        $data = array();

        $data['status']     = $status;
        if($type === 'list') {
            $data['files']      = $content;
        } else {
            $data['content']    = $content;
        }
        $data['type']       = $type;
        $data['updated']    = Mage::helper('staempfli_productattachment')->getLocalizedDateTime();

        return $data;
    }

}