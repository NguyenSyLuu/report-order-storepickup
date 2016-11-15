<?php 

namespace Magestore\Storepickup\Model\ResourceModel\Report;

/**
 * Collection Collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Magestore\Storepickup\Model\Report','Magestore\Storepickup\Model\ResourceModel\Report');
    }
}
