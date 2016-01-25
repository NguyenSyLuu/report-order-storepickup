<?php
namespace Magestore\Storepickup\Model\Config\Source;

class Paymentmethods implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     */
    protected $_collectionFactory;
    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory $collectoryFactory
    )
    {
        $this->_collectionFactory = $collectoryFactory;
    }

    public function toOptionArray()
    {
        $storeCollection = $this->_collectionFactory->create();
        if(!count($storeCollection))return;

        $options = array();

        foreach($storeCollection as $item)
        {
            //var_dump($item);die();
            $title = $item->getTitle() ? $item->getTitle() : $item->getId();
            $options[] = array('value'=> $item->getId(), 'label' => $title);
        }

        return $options;
    }
}
