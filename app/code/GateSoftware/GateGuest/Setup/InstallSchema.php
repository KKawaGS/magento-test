<?php

namespace GateSoftware\GateGuest\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface {

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
            $table = $setup->getConnection()->newTable(
                $setup->getTable('gate_guest_book')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Id field'
            )->addColumn(
                'first_name',
                Table::TYPE_TEXT,
                255,
                ['nullble' => false],
                'First name'
            )->addColumn(
                'last_name',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Last name'
            )->addColumn(
                'email',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Email address'
            )->addColumn(
                'ip',
                Table::TYPE_TEXT,
                16,
                ['nullable' => false],
                'IP address'
            )->addIndex(
                $setup->getIdxName('gate_guest_book', ['ip']), ['ip']
            )->setComment('Guest Book');

            $setup->getConnection()->createTable($table);
        $setup->endSetup();
    }
}
