<?php 

namespace Magestore\Storepickup\Controller\Adminhtml\Report;

use Magento\Framework\Controller\ResultFactory;

/**
 * Action Save
 */
class Save extends \Magestore\Storepickup\Controller\Adminhtml\Report
{
    /**
     * Execute action
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if ($data = $this->getRequest()->getPostValue()) {
            /** @var \Magestore\Storepickup\Model\Report $model */
            $model = $this->_objectManager->create('Magestore\Storepickup\Model\Report');

            if ($id = $this->getRequest()->getParam('report_id')) {
                $model->load($id);
            }

            $model->setData($data);

            try {
                $model->save();

                $this->messageManager->addSuccess(__('The record has been saved.'));
                $this->_getSession()->setFormData(false);

                if ($this->getRequest()->getParam('back') === 'edit') {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        [
                            'report_id' => $model->getId(),
                            '_current' => true,
                        ]
                    );
                } elseif ($this->getRequest()->getParam('back') === 'new') {
                    return $resultRedirect->setPath(
                        '*/*/new',
                        ['_current' => true]
                    );
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->messageManager->addException($e, __('Something went wrong while saving the record.'));
            }

            $this->_getSession()->setFormData($data);

            return $resultRedirect->setPath(
                '*/*/edit',
                ['report_id' => $this->getRequest()->getParam('report_id')]
            );
        }

        return $resultRedirect->setPath('*/*/');
    }
}
