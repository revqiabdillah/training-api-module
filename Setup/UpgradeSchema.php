<?php
namespace Icube\TrainingApi\Setup;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements  UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup,ModuleContextInterface $context){
        $setup->startSetup();
        if (version_compare($context->getVersion(), '2.0.0') < 0) {
            $tableName = 'icube_training_api';
            $setup->getConnection()->addColumn(
                $setup->getTable($tableName),
                'hobby',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'length' => 255,
                    'after' => 'name',
                    'comment' => 'name of the hobby'
                ]
            );
            $setup->endSetup();
        }
    }
}