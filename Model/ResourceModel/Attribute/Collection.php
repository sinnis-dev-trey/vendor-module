<?php
    namespace Vendor\Module\Model\ResourceModel\Attribute;

    class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
    {
        protected $_idFieldName = 'id';
        protected $_eventPrefix = 'vendor_module_attribute';
        protected $_eventObject = 'attr_collection';

        protected function _construct()
        {
            $this->_init(
                'Vendor\Module\Model\Attribute',
                'Vendor\Module\Model\ResourceModel\Attribute'
            );
        }
    }
