<?php

namespace GateSoftware\GateGallery\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

    /**
     * @inheritDoc
     */
    //todo refractor to declarative schema
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()->newTable(
          $setup->getTable('gategallery_gallery'),
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Gallery id'
        )->addColumn(
            'name',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'gallery name'
        )->addIndex(
            $setup->getIdxName('gategallery_gallery', ['name']),
            ['name']
        )->setComment('GateSoftware Gallery');
        $setup->getConnection()->createTable($table);
        $setup->endSetup();
    }
}
