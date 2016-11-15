<?php 

namespace Magestore\Storepickup\Controller\Adminhtml\Report;

use Magento\Framework\Controller\ResultFactory;

/**
 * Action Grid
 */
class Grid extends \Magestore\Storepickup\Controller\Adminhtml\Report
{
    /**
     * Execute action
     */
    public function execute()
    {
        $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);

        return $resultLayout;
    }
}
