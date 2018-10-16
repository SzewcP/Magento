<?php

namespace Virtua\Technology\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()->newTable(
            $setup->getTable('virtua_technology')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true]
        )->addColumn(
            'name',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false]
        )->addColumn(
            'description',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false]
        )->addColumn(
            'logo',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false]
        )->addColumn(
            'url',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false]
        );

        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
