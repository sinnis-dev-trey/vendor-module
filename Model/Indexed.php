<?php
    namespace Vendor\Module\Model;

    class Indexed
        extends \Magento\Framework\Model\AbstractModel
        implements \Magento\Framework\DataObject\IdentityInterface
    {
        const CACHE_TAG = 'vendor_module_indexed';

        protected $_cacheTag;
        protected $_eventPrefix;

        protected function _construct()
        {
            $this->_init('Vendor\Module\Model\ResourceModel\Indexed');
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
