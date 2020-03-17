<?php
    namespace Vendor\Module\Block;

    class Grouped extends \Magento\GroupedProduct\Block\Product\View\Type\Grouped
    {
        protected $helper;

        public function __construct(
            \Magento\Catalog\Block\Product\Context $context,
            \Magento\Framework\Stdlib\ArrayUtils $utils,
            \Vendor\Module\Helper\Adminhtml\Compile $helper,
            array $data = []
        )
        {
            parent::__construct($context, $utils);

            $this->helper = $helper;
        }

        public function getGroupedProducts($id)
        {
            return $this->helper->loadProductsById($id);
        }
    }

