<?php
    namespace Vendor\Module\Setup;

    class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
    {
        public function install(
            \Magento\Framework\Setup\SchemaSetupInterface $setup,
            \Magento\Framework\Setup\ModuleContextInterface $context
        )
        {
            $installer = $setup;
            $installer->startSetup();

            $intConf = ['unsigned' => true, 'nullable' => false];

            if (!$installer->tableExists('vendor_module_eav')) {
                $table = $installer->getConnection()->newTable($installer->getTable('vendor_module_eav'))
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                        'ID'
                    )
                    ->addColumn('grouped_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 11, $intConf, 'Grouped ID')
                    ->addColumn('simple_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 11, $intConf, 'Simple ID')
                    ->addColumn('attr_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 11, $intConf, 'Attribute ID')
                    ->addColumn('value', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, [], 'Value')
                    ->addColumn('ts', \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, 20, ['default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT], 'Date')
                    ->setComment('Module EAV Table');

                $installer->getConnection()->createTable($table);
            }

            if (!$installer->tableExists('vendor_module_attribute')) {
                $table = $installer->getConnection()->newTable($installer->getTable('vendor_module_attribute'))
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                        'ID'
                    )
                    ->addColumn('name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 50, [], 'Name')
                    ->setComment('Module Attributes Table');

                $installer->getConnection()->createTable($table);
            }

            if (!$installer->tableExists('vendor_module_indexed')) {
                $defaultOpts = ['nullable' => false];

                $table = $installer->getConnection()->newTable($installer->getTable('vendor_module_indexed'))
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                        'ID'
                    )
                    ->addColumn(
                        'group_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        11,
                        ['nullable' => false, 'unsigned' => true],
                        'Grouped ID'
                    )
                    ->addColumn(
                        'sku',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        30,
                        $defaultOpts,
                        'SKU'
                    )
                    ->addColumn(
                        'title',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        100,
                        $defaultOpts,
                        'Title'
                    )
                    ->addColumn(
                        'img_name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        100,
                        $defaultOpts,
                        'Image Name'
                    )
                    ->addColumn(
                        'notes',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        $defaultOpts,
                        'Notes'
                    )
                    ->addColumn(
                        'rrp',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                        '6,2',
                        $defaultOpts,
                        'RRP'
                    )
                    ->addColumn(
                        'required_qty',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        11,
                        $defaultOpts,
                        'Required Qty'
                    )
                    ->addColumn(
                        'img_number',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        11,
                        $defaultOpts,
                        'Img Number'
                    )
                    ->addColumn(
                        'availability',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        50,
                        $defaultOpts,
                        'Availability'
                    )
                    ->setComment('Module Indexed Table');

                $installer->getConnection()->createTable($table);
            }

            $installer->endSetup();
        }
    }
