<?php
    namespace Vendor\Module\Setup;

    class InstallData implements \Magento\Framework\Setup\InstallDataInterface
    {
        protected $_attributeFactory;

        public function __construct(\Vendor\Module\Model\AttributeFactory $attributeFactory)
        {
            $this->_attributeFactory = $attributeFactory;
        }

        public function install(
            \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
            \Magento\Framework\Setup\ModuleContextInterface $context
        )
        {
            foreach (['Required Qty', 'Image Number', 'Notes'] as $name)
            {
                $attr = $this->_attributeFactory->create();
                $attr->addData(['name' => $name])->save();
            }
        }
    }
