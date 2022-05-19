<?php

namespace GateSoftware\GateGallery\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{

    /**
     * @inheritDoc
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        // TODO: Implement install() method.
        $setup->startSetup();

        $setup->getConnection()->insert(
            $setup->getTable(gategallery_gallery),
            [
                'name' => 'Default'
            ]
        );

        $setup->endSetup();
    }
}
