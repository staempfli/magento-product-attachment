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
class Staempfli_ProductAttachment_Helper_Data extends Mage_Core_Helper_Abstract
{

    const UPLOAD_DIR    = 'productattachment';
    const LOG_FILE      = 'staempfli_productattachment.log';

    /**
     * @return bool|string
     */
    public function getUploadDir()
    {
        $dir = realpath(Mage::getBaseDir('media') . DS . self::UPLOAD_DIR);

        if(!$dir) {
            return $this->createUploadDir();
        }

        return $dir;
    }


    /**
     * @return bool|string
     */
    public function createUploadDir()
    {
        if(!mkdir(Mage::getBaseDir('media') . DS . self::UPLOAD_DIR, 0777, true)) {
            return false;
        }

        return realpath(Mage::getBaseDir('media') . DS . self::UPLOAD_DIR);
    }

    /**
     * Returns maximum post size in bytes.
     *
     * @return null|int     The maximum post size in bytes
     */
    public function getPostMaxSize()
    {
        $iniMax = strtolower($this->getNormalizedIniPostMaxSize());
        if ('' === $iniMax) {
            return;
        }
        $max = ltrim($iniMax, '+');
        if (0 === strpos($max, '0x')) {
            $max = intval($max, 16);
        } elseif (0 === strpos($max, '0')) {
            $max = intval($max, 8);
        } else {
            $max = intval($max);
        }
        switch (substr($iniMax, -1)) {
            case 't': $max *= 1024;
            case 'g': $max *= 1024;
            case 'm': $max *= 1024;
            case 'k': $max *= 1024;
        }
        return $max;
    }
    /**
     * Returns the normalized "post_max_size" ini setting.
     *
     * @return string
     */
    public function getNormalizedIniPostMaxSize()
    {
        return strtoupper(trim(ini_get('post_max_size')));
    }

    /**
     * Returns the store name by id
     *
     * @param $store_id
     * @return mixed
     */
    public function getStoreNameById($store_id)
    {
        $stores = $this->getStores();

        switch($store_id) {
            case 0:
                return $this->__('Default');
                break;
            default:
                if(isset($stores[$store_id]['name'])) {
                    return $stores[$store_id]['name'];
                } else {
                    return $this->__('Not found');
                }
                break;
        }
    }

    /**
     * Returns all stores
     *
     * @return array
     */
    public function getStores()
    {
        $stores = array();

        foreach (Mage::app()->getStores() as $store) {
            $stores[$store->getStoreId()] = $store->getData();
        }

        return $stores;
    }

    /**
     * Returns the localized datetime string
     * @return string
     * @throws Zend_Date_Exception
     */
    public function getLocalizedDateTime()
    {
        $datetime = Zend_Date::now();
        $datetime->setLocale(Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE))->setTimezone(Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE));
        return $datetime->get(Zend_Date::DATETIME_MEDIUM);
    }
}