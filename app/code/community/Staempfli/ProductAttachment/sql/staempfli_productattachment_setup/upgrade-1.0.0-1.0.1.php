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

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn(
        $installer->getTable('staempfli_productattachment/file'),
        'path',
        array(
            'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
            'size'      => 255,
            'comment'   => 'file path',
            'default'   => '',
        )
    );

$installer->getConnection()->addColumn(
        $installer->getTable('staempfli_productattachment/file'),
        'type',
        array(
            'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
            'size'      => 10,
            'comment'   => 'file type',
            'default'   => '',
        )
    );

$installer->endSetup();
