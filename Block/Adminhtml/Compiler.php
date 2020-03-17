<?php
    namespace Vendor\Module\Block\Adminhtml;

    class Compiler extends \Magento\Framework\View\Element\Template
    {
        protected $_backendUrl;
        protected $helper;

        public function __construct(
            \Magento\Framework\View\Element\Template\Context $context,
            \Magento\Backend\Model\UrlInterface $backendUrl,
            \Vendor\Module\Helper\Adminhtml\Eav $helper,
            array $data = []
        )
        {
            parent::__construct($context, $data);
         
            $this->_backendUrl = $backendUrl;
            $this->helper = $helper;
        }

        public function getChangeLog()
        {
            return $this->helper->getChangesForToday();
        }

        public function getTodaysChanges()
        {
            return $this->helper->loadToday();
        }
    }
