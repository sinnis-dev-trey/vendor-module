<?php
    namespace Vendor\Module\Helper\Adminhtml;

    class Compile extends \Magento\Framework\App\Helper\AbstractHelper
    {
        protected $_block;

        protected $_eavHelper;

        protected $_eavCollection;
        protected $_indexedCollection;
        protected $_productCollection;

        protected $_attrLoader;
        protected $_productLoader;

        public function __construct(
            \Magento\Framework\App\Helper\Context $context,
            \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection,
            \Magento\Catalog\Model\ProductFactory $_productLoader,
            \Vendor\Module\Block\Adminhtml\Compiler $block,
            \Vendor\Module\Helper\Adminhtml\Eav $eavHelper,
            \Vendor\Module\Model\AttributeFactory $_attrLoader,
            \Vendor\Module\Model\IndexedFactory $indexedFactory,
            \Vendor\Module\Model\EavFactory $eavFactory,
            array $data = []
        )
        {
            $this->_block = $block;

            $this->_eavHelper = $eavHelper;

            $this->_eavCollection = $eavFactory;
            $this->_indexedCollection = $indexedFactory;
            $this->_productCollection = $productCollection;

            $this->_attrLoader = $_attrLoader; 
            $this->_productLoader = $_productLoader;

            parent::__construct($context);
        }

        public function compileToday()
        {
            $errors = [];
            $errorMessages = [];
            $todaysCollection = $this->_eavHelper->loadToday();

            if ($todaysCollection->getSize() > 0) {
                foreach ($todaysCollection as $row)
                {
                    try {
                        $indexedModel = $this->_indexedCollection->create();
                        $simple = $this->_productLoader->create()->load($row['simple_id']);
 
                        $attr = $row['attr_id'];
                        $value = $row['value'];

                        if ($this->deleteGroupedIndex($row['grouped_id'], $simple->getSku()) !== true) {
                            return false;
                        }

                        if ($attr == 1 || $attr == '1') {
                            $indexedModel->setData('required_qty', $value);
                        }

                        if ($attr == 2 || $attr == '2') {
                            $indexedModel->setData('img_number', $value);
                        }

                        if ($attr == 3 || $attr == '3') {
                            $indexedModel->setData('notes', $value);
                        }

                        $indexedModel->setData('group_id', $row['grouped_id']);
                        $indexedModel->setData('sku', $simple->getSku());
                        $indexedModel->setData('title', $simple->getName());
                        $indexedModel->setData('img_name', 'product'. $simple->getData('thumbnail'));
                        $indexedModel->setData('rrp', $simple->getPriceInfo()->getPrice('regular_price')->getValue());
                        $indexedModel->setData('availability', ($simple->isSaleable() ? 'In Stock' : 'Out of Stock'));

                        $indexedModel->save();
                    } catch (\PDOException $e) {
                        $errors[] = $this->createErrorMessage($row['simple_id']. '_'. $row['attr_id']);
                    }
                }
            } else {
                $errors[] = 'Error! Nothing to index';
            }

            return (empty($errors) ? true: $errors);
        }

        public function deleteGroupedIndex($groupId, $sku)
        {
            try {
                $collection = $this->_indexedCollection->create()->getCollection()
                    ->addFieldToFilter('group_id', $groupId)
                    ->addFieldToFilter('sku', $sku);

                if ($collection->getSize() > 0) {
                    foreach ($collection as $item)
                    {
                        $model = $this->_indexedCollection->create();
                        $model->load($item->getData('id'));

                        $model->delete();
                    }
                }

                return true;
            } catch (\PDOException $e) {
                 return 'Error: '. $e->getMessage;
            }
        }

        public function createErrorMessage($idCode)
        {
            $el = explode('_', $idCode);

            $simple = $this->_productLoader->create()->load($el[0]);
            $attr = $this->_attrLoader->create()->load($el[1]);

            return 'Error setting '. $attr->getData('name'). ' for '. $simple->getName(). '.<br/>';
        }

        public function loadProductsById($groupId)
        {
            return $this->_indexedCollection->create()->getCollection()
                ->addFieldToFilter('group_id', $groupId);
        }
   }
