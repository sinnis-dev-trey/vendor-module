<?php
    namespace Vendor\Module\Helper\Adminhtml;

    class Eav extends \Magento\Framework\App\Helper\AbstractHelper
    {
        protected $_eavCollection;
        protected $_productLoader;

        public function __construct(
            \Magento\Framework\App\Helper\Context $context,
            \Magento\Catalog\Model\ProductFactory $productLoader,
            \Vendor\Module\Model\EavFactory $eavFactory,
            array $data = []
        )
        {
            $this->_productLoader = $productLoader;
            $this->_eavCollection = $eavFactory;

            parent::__construct($context);
        }

        public function getChangesForToday()
        {
            $changes = [];
            $collection = $this->loadToday();

            if ($collection->getSize() > 0) {
                foreach ($collection as $key => $item)
                {
                    $simple = $this->_productLoader->create()->load($item['simple_id']);
                    $grouped = $this->_productLoader->create()->load($item['grouped_id']);

                    $changes[$key] = [
                        'grouped' => $grouped->getSku(). ': '. $grouped->getName(),
                        'simple' => $simple->getSku(). ': '. $simple->getName()
                    ];
                }
            }

            return $changes;
        }

        public function loadToday()
        {
            $collection = $this->_eavCollection->create()->getCollection()
                ->addFieldToFilter(
                    'ts',
                    [
                        'from' => date('Y-m-d H:i:s', strtotime('12:00am')),
                        'to' => date('Y-m-d H:i:s', strtotime('11:59pm'))
                    ]
                );

             return $collection;
        }

        public function save(array $row, int $prodId)
        {
            try {
                $eavModel = $this->_eavCollection->create();

                $eavModel->setData('simple_id', $row['simple_id']);
                $eavModel->setData('attr_id', $row['attr_id']);
                $eavModel->setData('value', $row['attr_value']);
                $eavModel->setData('grouped_id', $prodId);

                $eavModel->save();
            } catch (\PDOException $e) {
                return $e->getMessage();
            }

            return true;
        }

        public function createErrors($rowNum, $attr = 0, $value = 0, $id = 0)
        {
            $message = 'Error(s) on Row '. $rowNum. ': <br/>';

            if ($attr === 1) {
                $message .= 'Attribute Name wasn\'t found. Please review.<br/>';
            }

            if ($value === 1) {
                $message .= 'Value seems to be empty. Please review.<br/>';
            }

            if ($id === 1) {
                $message .= 'ID for selected product seems to be empty. Please try again.<br/>';
            }

            return $message;
        }
    }
