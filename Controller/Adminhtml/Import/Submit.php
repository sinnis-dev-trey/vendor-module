<?php
    namespace Vendor\Module\Controller\Adminhtml\Import;

    class Submit extends \Magento\Framework\App\Action\Action
    {
        protected $_resultLayoutFactory;
        protected $eavHelper;

        public function __construct(
            \Magento\Framework\App\Action\Context $context,
            \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
            \Vendor\Module\Helper\Adminhtml\Eav $eavHelper
        )
        {
            parent::__construct($context);
            $this->_resultLayoutFactory = $resultLayoutFactory;
            $this->eavHelper = $eavHelper;
        }

        public function execute()
        {
            $files = (array) $this->getRequest()->getFiles();
            $post = (array) $this->getRequest()->getPost();

            if (!empty($files)) {
                $importFile = $files['import_file'];

                if (!$importFile['error']) {
                    $resultLayout = $this->_resultLayoutFactory->create();
                    $block = $resultLayout->getLayout()->createBlock('Vendor\Module\Block\Adminhtml\Import');
                    $toImport = $block->parseFile($importFile['tmp_name']);
                }
            }

            if (!empty($post)) {
                if (!empty($toImport)) {
                    foreach ($toImport['to_import'] as $import)
                    {
                        if (!$this->eavHelper->save($import, $post['product'])) {
                            continue;
                        }
                    }

                    if (!empty($toImport['err_rows'])) {
                        foreach ($toImport['err_rows'] as $rowNum => $err)
                        {
                            $this->messageManager->addError($this->eavHelper->createErrors(
                                $rowNum, $err['attribute'] ?? 0, $err['value'] ?? 0, $err['id'] ?? 0
                            ));
                        }
                    }

                    $this->messageManager->addSuccess(__('Rows that could be imported, have been! Go Polar Bears!'));
                } else {
                    $this->messageManager->addError(__('Something went wrong validating the import. Please try again.'));
                }
            } else {
                $this->messageManager->addError(__('Something went wrong with getting the selected product. Please try again.'));
            }

            $this->_redirect('sinattr/import');
        }
    }

