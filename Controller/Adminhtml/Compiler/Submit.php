<?php
    namespace Vendor\Module\Controller\Adminhtml\Compiler;

    class Submit extends \Magento\Framework\App\Action\Action
    {
        protected $_resultLayoutFactory;
        protected $compileHelper;

        public function __construct(
            \Magento\Framework\App\Action\Context $context,
            \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
            \Vendor\Module\Helper\Adminhtml\Compile $compileHelper
        )
        {
            parent::__construct($context);
            $this->_resultLayoutFactory = $resultLayoutFactory;
            $this->compileHelper = $compileHelper;
        }

        public function execute()
        {
            $messages = $this->compileHelper->compileToday();
            $this->messageManager->addSuccess(__('Rows that could be indexed, have been'));

            if (is_array($messages)) {
                foreach ($messages as $message)
                {
                    $this->messageManager->addError(__($message));
                }
            }

            $this->_redirect('sinattr/compiler');
        }
    }

