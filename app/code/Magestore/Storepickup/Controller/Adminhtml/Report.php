<?php 

namespace Magestore\Storepickup\Controller\Adminhtml;

/**
 * Action Report
 */
abstract class Report extends \Magento\Backend\App\Action
{
    /**
     * Action constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magestore_Report::report');
    }
}
