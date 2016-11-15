<?php 

namespace Magestore\Storepickup\Controller\Adminhtml\Report;

use Magento\Framework\Controller\ResultFactory;

/**
 * Action NewAction
 */
class NewAction extends \Magestore\Storepickup\Controller\Adminhtml\Report
{
    /**
     * Execute action
     */
    public function execute()
    {
        $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);

        return $resultForward->forward('edit');
    }
}
