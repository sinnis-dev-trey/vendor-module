<?php
    namespace Vendor\Module\Model\ResourceModel\Indexed;

    class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
    {
        protected $_idFieldName = 'id';
        protected $_eventPrefix = 'vendor_module_indexed';
        protected $_eventObject = 'indexed_collection';

        protected function _construct()
        {
            $this->_init(
                'Vendor\Module\Model\Indexed',
                'Vendor\Module\Model\ResourceModel\Indexed'
            );
        }
    }
