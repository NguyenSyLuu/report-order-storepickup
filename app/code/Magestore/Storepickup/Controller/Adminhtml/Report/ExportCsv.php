<?php 

namespace Magestore\Storepickup\Controller\Adminhtml\Report;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;

/**
 * Action ExportCsv
 */
class ExportCsv extends \Magestore\Storepickup\Controller\Adminhtml\Report
{
    /**
     * Execute action
     */
    public function execute()
    {
        $fileName = 'Reports.csv';

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $content = $resultPage->getLayout()->createBlock('Magestore\Storepickup\Block\Adminhtml\Report\Grid')->getCsv();

        /** @var \Magento\Framework\App\Response\Http\FileFactory $fileFactory */
        $fileFactory = $this->_objectManager->get('Magento\Framework\App\Response\Http\FileFactory');
        return $fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
