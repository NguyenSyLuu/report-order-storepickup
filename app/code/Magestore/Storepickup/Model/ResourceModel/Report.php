<?php 

namespace Magestore\Storepickup\Model\ResourceModel;

/**
 * Resource Model Report
 */
class Report extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('magestore_storepickup_report','report_id');
    }
}
