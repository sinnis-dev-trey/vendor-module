<?php
    namespace Vendor\Module\Block\Adminhtml;

    class Import extends \Magento\Framework\View\Element\Template
    {
        protected $prodRepo;

        protected $_backendUrl;
        protected $helper;

        public function __construct(
            \Magento\Framework\View\Element\Template\Context $context,
            \Magento\Backend\Model\UrlInterface $backendUrl,
            \Magento\Catalog\Api\ProductRepositoryInterface $productRepo,
            \Vendor\Module\Helper\Adminhtml\Import $helper,
            array $data = []
        )
        {
            parent::__construct($context, $data);
         
            $this->prodRepo = $productRepo;
            $this->_backendUrl = $backendUrl;
            $this->helper = $helper;
        }

        public function loadProduct(string $sku)
        {
            try {
                return $this->prodRepo->get($sku);
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e){
                return 'hilarious message: '. $e->getMessage();
            }
        }

        public function getProductCollection()
        {
            return $this->helper->getGroupedProductCollection();
        }

        public function parseFile($file)
        {
            $errors = [];
            $passed = [];

            $attrCollection = $this->helper->getAttributesCollection();

            foreach (array_map('str_getcsv', file($file)) as $num => $row)
            {
                $product = $this->loadProduct($row[0]);

                if (is_object($product)) {
                    $id = $product->getId();
                    $attrId = array_search($row[1], $attrCollection);
                    $value = $row[2];

                    if ($id <= 0 || $id === false || $id === null) {
                        $errors[$num]['id'] = 1;
                    }

                    if ($attrId === false) {
                        $errors[$num]['attribute'] = 1;
                    }

                    if (empty($value)) {
                        $errors[$num]['value'] = 1;
                    }

                    if (!empty($errors)) {
                        continue;
                    }

                    $passed[$num] = [
                        'simple_id' => $id,
                        'attr_id' => $attrId,
                        'attr_value' => $value
                    ];
                }
            }

            return ['to_import' => $passed, 'err_rows' => $errors];
        }
    }

