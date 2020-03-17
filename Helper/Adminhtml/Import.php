<?php
    namespace Vendor\Module\Helper\Adminhtml;

    class Import extends \Magento\Framework\App\Helper\AbstractHelper
    {
        protected $_productCollection;
        protected $_attrCollection;

        public function __construct(
            \Magento\Framework\App\Helper\Context $context,
            \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection,
            \Vendor\Module\Model\AttributeFactory $attrFactory,
            array $data = []
        )
        {
            $this->_productCollection = $productCollection;
            $this->_attrCollection = $attrFactory;

            parent::__construct($context);
        }

        public function getGroupedProductCollection()
        {
            $collection = $this->_productCollection;
            $collection->addFilter('type_id', 'grouped');
            $collection->load();

            return $collection;
        }

        public function getAttributesCollection()
        {
            $attributes = [];

            $attr = $this->_attrCollection->create();
            $collection = $attr->getCollection();

            foreach ($collection as $attr)
            {
                $attributes[$attr->getData('id')] = $attr->getData('name');
            }

            if (!empty($attributes)) {
                return $attributes;
            }

            return false;
        }
    }
