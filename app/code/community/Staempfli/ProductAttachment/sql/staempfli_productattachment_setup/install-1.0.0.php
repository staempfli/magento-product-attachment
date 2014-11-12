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

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$fileTable = $installer->getConnection()->newTable($installer->getTable('staempfli_productattachment/file'))
    ->addColumn(
        'file_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        11,
        array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true,
        ),
        'Primary key of the file entry'
    )
    ->addColumn(
        'product_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        10,
        array(),
        'Product ID'
    )
    ->addColumn(
        'store_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        5,
        array(
            'unsigned' => true,
            'nullable' => false,
            'default'  => 0
        ),
        'store_id'
    )
    ->addColumn(
        'filename',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        255,
        array(),
        'filename of the uploaded file'
    )
    ->addColumn(
        'title',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        255,
        array(),
        'title of the uploaded file'
    )
    ->addColumn(
        'description',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        null,
        array(),
        'file description'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(
            'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT
        ),
        'created at'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(
            'default' => Varien_Db_Ddl_Table::TIMESTAMP_UPDATE
        ),
        'updated at'
    )
    ->addColumn(
        'sort_order',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        11,
        array(
            'unsigned' => true,
            'nullable' => false,
            'default'  => 0
        ),
        'sort order'
    )
    ->addIndex('product_id', 'product_id')
    ->addIndex('store_id', 'store_id')
    ->addIndex('sort_order', 'sort_order')
;
$installer->getConnection()->createTable($fileTable);
$installer->endSetup();
