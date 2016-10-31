<?php

/**
 * Magestore
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Storelocator
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

namespace Magestore\Storepickup\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magestore\Storepickup\Setup\InstallSchema as StorepickupShema;

/**
 *
 *
 * @category Magestore
 * @package  Magestore_Pdfinvoiceplus
 * @module   Pdfinvoiceplus
 * @author   Magestore Developer
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Schema table.
     */
    const SCHEMA_REPORT = 'magestore_storepickup_report';
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '0.1.1', '<')) {
            $this->changeColumnImage($setup);
        }
        if (version_compare($context->getVersion(), '1.1.0', '<')) {
            $this->addOwnerInformation($setup);
        }
        if (version_compare($context->getVersion(), '1.1.1', '<')) {

            /*
         * Create table magestore_storepickup_report
         */
            $table = $installer->getConnection()->newTable(
                $installer->getTable(self::SCHEMA_REPORT)
            )->addColumn(
                'report_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Report Id'
            )->addColumn(
                'order_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Order Id'
            )->addColumn(
                'storepickup_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Storepickup Id'
            )->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Product ID'
            )->addColumn(
                'product_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Product Name'
            )->addColumn(
                'qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => true],
                'Qty'
            )->addColumn(
                'date_report',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                null,
                ['nullable' => true],
                'Date Report'
            )->addIndex(
                $installer->getIdxName(
                    $installer->getTable(self::SCHEMA_REPORT),
                    ['order_id'],
                    AdapterInterface::INDEX_TYPE_INDEX
                ),
                ['order_id'],
                ['type' => AdapterInterface::INDEX_TYPE_INDEX]
            )->addIndex(
                $installer->getIdxName(
                    $installer->getTable(self::SCHEMA_REPORT),
                    ['product_id'],
                    AdapterInterface::INDEX_TYPE_INDEX
                ),
                ['product_id'],
                ['type' => AdapterInterface::INDEX_TYPE_INDEX]
            )->setComment(
                'Report Table'
            );

            $installer->getConnection()->createTable($table);
            /*
             * End create table magestore_storepickup_report
             */
            $this->addOwnerInformation($setup);
        }
        $installer->endSetup();
    }

    /**
     *
     * rename column storepickup_id in table magestore_storelocator_image to pickup_id
     *
     * @param SchemaSetupInterface $setup
     */
    public function changeColumnImage(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->dropForeignKey(
            $setup->getTable(StorepickupShema::SCHEMA_IMAGE),
            $setup->getFkName(
                StorepickupShema::SCHEMA_IMAGE,
                'storepickup_id',
                StorepickupShema::SCHEMA_STORE,
                'storepickup_id'
            )
        );

        $setup->getConnection()->dropIndex(
            $setup->getTable(StorepickupShema::SCHEMA_IMAGE),
            $setup->getIdxName(
                $setup->getTable(StorepickupShema::SCHEMA_IMAGE),
                ['storepickup_id'],
                AdapterInterface::INDEX_TYPE_INDEX
            )
        );

        $setup->getConnection()->changeColumn(
            $setup->getTable(StorepickupShema::SCHEMA_IMAGE),
            'storepickup_id',
            'pickup_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'length' => null,
                'comment' => 'Storelocator Id',
                'unsigned' => true
            ]
        );

        $setup->getConnection()->addIndex(
            $setup->getTable(StorepickupShema::SCHEMA_IMAGE),
            $setup->getIdxName(
                $setup->getTable(StorepickupShema::SCHEMA_IMAGE),
                ['pickup_id'],
                AdapterInterface::INDEX_TYPE_INDEX
            ),
            ['pickup_id'],
            AdapterInterface::INDEX_TYPE_INDEX
        );

        $setup->getConnection()->addForeignKey(
            $setup->getFkName(
                StorepickupShema::SCHEMA_IMAGE,
                'pickup_id',
                StorepickupShema::SCHEMA_STORE,
                'storepickup_id'
            ),
            $setup->getTable(StorepickupShema::SCHEMA_IMAGE),
            'pickup_id',
            $setup->getTable(StorepickupShema::SCHEMA_STORE),
            'storepickup_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );

    }
    public function addOwnerInformation(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable(StorepickupShema::SCHEMA_STORE),
            'owner_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT
        );
        $setup->getConnection()->addColumn(
            $setup->getTable(StorepickupShema::SCHEMA_STORE),
            'owner_email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT
        );
        $setup->getConnection()->addColumn(
            $setup->getTable(StorepickupShema::SCHEMA_STORE),
            'owner_phone',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT
        );


    }
}