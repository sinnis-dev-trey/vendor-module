<?php
    namespace Vendor\Module\Model;

    class Eav
        extends \Magento\Framework\Model\AbstractModel
        implements \Magento\Framework\DataObject\IdentityInterface
    {
        const CACHE_TAG = 'vendor_module_eav';

        protected $_cacheTag;
        protected $_eventPrefix;

        protected function _construct()
        {
            $this->_init('Vendor\Module\Model\ResourceModel\Eav');
        }

        public function getIdentities ()
        {
            return [self::CACHE_TAG. '_'. $this->getId()];
        }

        public function getDefaultValues()
        {
            return [];
        }
    }
