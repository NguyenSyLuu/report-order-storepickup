<?php 

namespace Magestore\Storepickup\Controller\Adminhtml\Report;

use Magento\Framework\Controller\ResultFactory;

/**
 * Action Index
 */
class Index extends \Magestore\Storepickup\Controller\Adminhtml\Report
{
    /**
     * Execute action
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Magestore_Report::report');

        return $resultPage;
    }
}
