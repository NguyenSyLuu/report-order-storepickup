<?php 

namespace Magestore\Storepickup\Controller\Adminhtml\Report;

use Magento\Framework\Controller\ResultFactory;

/**
 * Action MassStatus
 */
class MassStatus extends \Magestore\Storepickup\Controller\Adminhtml\Report
{
    /**
     * Execute action
     */
    public function execute()
    {
        $reportIds = $this->getRequest()->getParam('reports');
        $status = $this->getRequest()->getParam('status');

        if (!is_array($reportIds) || empty($reportIds)) {
            $this->messageManager->addError(__('Please select record(s).'));
        } else {
            /** @var \Magestore\Storepickup\Model\ResourceModel\Report\Collection $collection */
            $collection = $this->_objectManager->create('Magestore\Storepickup\Model\ResourceModel\Report\Collection');
            $collection->addFieldToFilter('report_id', ['in' => $reportIds]);
            try {
                foreach ($collection as $item) {
                    $item->setStatus($status)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been changed status.', count($reportIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);;

        return $resultRedirect->setPath('*/*/');
    }
}
