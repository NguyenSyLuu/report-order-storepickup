<?php
/**
 * Magestore
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Giftvoucher
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

namespace Magestore\Storepickup\Controller\Checkout;

class DisableDate extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    /**
     * @var \Magestore\Storepickup\Model\ResourceModel\Store\CollectionFactory
     */
    protected $_storeCollectionFactory;
    /**
     * @var \Magestore\Storepickup\Model\ResourceModel\Holiday\CollectionFactory
     */
    protected $_holidayCollectionFactory;
    /**
     * @var \Magestore\Storepickup\Model\ResourceModel\Schedule\CollectionFactory
     */
    protected $_scheduleCollectionFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magestore\Storepickup\Model\ResourceModel\Store\CollectionFactory $storeCollectionFactory,
        \Magestore\Storepickup\Model\ResourceModel\Holiday\CollectionFactory $holidayCollectionFactory,
        \Magestore\Storepickup\Model\ResourceModel\Schedule\CollectionFactory $scheduleCollectionFactory,
        \Magento\Checkout\Model\Session $checkoutSession
    )
    {
        parent::__construct($context);
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_storeCollectionFactory = $storeCollectionFactory;
        $this->_holidayCollectionFactory = $holidayCollectionFactory;
        $this->_scheduleCollectionFactory = $scheduleCollectionFactory;
        $this->_checkoutSession = $checkoutSession;
    }
    public function execute()
    {
        $date = array();
        $holiday_date = array();
        $specialday_date = array();
        $closed = array();
        $storeId = $this->getRequest()->getParam('store_id');
        $collectionstore = $this->_storeCollectionFactory->create();
        $collectionschdule = $this->_scheduleCollectionFactory->create();
        $collectionsholidays = $this->_holidayCollectionFactory->create();
        $store = $collectionstore->addFieldToFilter('storepickup_id',$storeId)->getFirstItem();
        $scheduleID = $store->getScheduleId();
        $schedule= $collectionschdule->addFieldToFilter('schedule_id',$scheduleID)->getFirstItem();

        if ($storeId == '') {
            $closed = array(1, 2, 3, 4, 5, 6, 0);
        }
        $date['specialdate'] = $specialday_date;
        $date['holidaydate'] = $holiday_date;
        $date['closed'] = $closed;
        return $this->getResponse()->setBody(\Zend_Json::encode($schedule));
    }
}
