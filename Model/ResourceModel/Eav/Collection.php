<?php
    namespace Vendor\Module\Model\ResourceModel\Eav;

    class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
    {
        protected $_idFieldName = 'id';
        protected $_eventPrefix = 'vendor_module_eav';
        protected $_eventObject = 'eav_collection';

        protected function _construct()
        {
            $this->_init(
                'Vendor\Module\Model\Eav',
                'Vendor\Module\Model\ResourceModel\Eav'
            );
        }
    }
