<?php 

namespace Magestore\Storepickup\Model;

/**
 * Model Report
 */
class Report extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Model constructor
     *
     * @param \Magento\Framework\Model\Context                   $context
     * @param \Magento\Framework\Registry                        $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb      $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = array()
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magestore\Storepickup\Model\ResourceModel\Report');
    }
}
