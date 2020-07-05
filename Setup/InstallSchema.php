<?php
namespace Alvor\SalesRuleConfirmation\Setup;


use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Config;

use Magento\Customer\Model\Customer;

class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $this->createConfirmationTable($setup);
        $setup->endSetup();
    }

    private function createConfirmationTable(SchemaSetupInterface $setup)
    {
        $confirmationTable = $setup->getConnection()
            ->newTable($setup->getTable('alvor_salesrule_confirmation'))
            ->addColumn('entity_id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'nullable' => false,
                'primary'  => true,
                'unsigned' => true
            ], 'Entity Id')
            ->addColumn('rule_id', Table::TYPE_INTEGER, null, [
                'identity' => false,
                'nullable' => false,
                'primary'  => false,
                'unsigned' => true
            ], 'Sales rule ID')
            ->addColumn('confirmation_type', Table::TYPE_TEXT, 255, [
                'nullable' => false,
                'default'  => ''
            ])
            ->addColumn('confirmation_key', Table::TYPE_TEXT, 255, [
                'nullable' => false,
                'defualt'  => ''
            ])
            ->addColumn('is_confirmed', Table::TYPE_SMALLINT, null, [
                'nullable' => true,
                'unsigned' => true
            ], 'Is confirmed')
            ->addIndex(
                $setup->getIdxName($setup->getTable('alvor_salesrule_confirmation'), 'rule_id'),
                'rule_id')
            ->addForeignKey(
                $setup->getFkName(
                    $setup->getTable('alvor_salesrule_confirmation'),
                    'rule_id',
                    $setup->getTable('salesrule'),
                    'rule_id'),
                'rule_id',
                $setup->getTable('salesrule'), 'rule_id')
            ->setComment('Sales rule confirmation');
        $setup->getConnection()->createTable($confirmationTable);
    }
}
