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
 * @package     Magestore_StorePickup
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

namespace Magestore\Storepickup\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 *
 * @category Magestore
 * @package  Magestore_StorePickup
 * @module   StorePickup
 * @author   Magestore Developer
 */
class StorepickupSaveOrderReport implements ObserverInterface
{
    /**
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @codeCoverageIgnore
     */
    protected $_checkoutSession;

    /**
     * @var \Magestore\Storepickup\Model\StoreFactory
     */
    protected $_storeCollection;
//    /**
//     * @var \Magestore\Storepickup\Model\ReportFactory
//     */
//    protected $_reportCollection;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @var \Magento\Sales\Api\Data\OrderAddressInterface
     */
    protected $_orderAddressInterface;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magestore\Storepickup\Model\StoreFactory $storeCollection,
//        \Magestore\Storepickup\Model\ReportFactory $reportCollection,
        \Magento\Sales\Api\Data\OrderAddressInterface $orderAddressInterface
    )
    {
        $this->_checkoutSession = $checkoutSession;
        $this->_storeCollection = $storeCollection;
//        $this->_reportCollection = $reportCollection;
        $this->_orderAddressInterface = $orderAddressInterface;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();


        $order_id = $order->getIncrementId();
//        $this->_logger->info($order_id);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $reportCollection = $objectManager->create('\Magestore\Storepickup\Model\Report');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Checkout\Model\Session');
        $sessionStore = $customerSession->getData('storepickup_session');
        $shippingMethod = $order->getShippingMethod();
//        \Zend_Debug::dump($order->getShippingMethod());
//        \Zend_Debug::dump("+++++++++++++++++++");

//        \Zend_Debug::dump($sessionStore);
        $sessionStore = $customerSession->getData('storepickup_session',false);
//        \Zend_Debug::dump("--------------------");
//        \Zend_Debug::dump($sessionStore);
//        die();
//        $this->_checkoutSession->getData('storepickup_session');
        if ($shippingMethod == 'storepickup_storepickup') {
            $t = time();
            $date = date("Y-m-d", $t);
            foreach ($order->getAllItems() as $item) {
                $ProdustIds[] = $item->getProductId();
                $proName[] = $item->getName(); // product name
                $reportCollection->setData('order_id', $order_id)
                    ->setData('storepickup_id', $sessionStore['store_id'])
                    ->setData('product_id', $item->getProductId())
                    ->setData('product_name', $item->getName())
                    ->setData('date_report', $date)
                    ->setData('qty', $item->getQtyOrdered())
                    ->save();

            }
        }
//        $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
//        $productCollection = $objectManager->create('Magestore\Storepickup\eportModel\ResourceModel\Report\CollectionFactory');
//        $this->xlog($productCollection->load(1));
//        foreach($order->getAllItems() as $item){
//            $ProdustIds[]= $item->getProductId();
//
//            $proName[] = $item->getName(); // product name
//            $this->xlog($item->getId());
//        }
//    die();

//        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//
//        $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
//
//        $collection = $productCollection->create();
////        $order = $observer->getEvent()->getOrder();
////        $quoteRepository = $this->_objectManager->create('Magento\Quote\Model\QuoteRepo‌​sitory');
//        $model = $this->_objectManager->create('\Magestore\Storepickup\Model\ResourceModel\Store\Grid\Collection');
////        $quote = $quoteRepository->get($order->getQuoteId());
////        echo "<pre>";print_R($quote->getData());
////        die("StorepickupSaveOrderReport");
    }
//    public function xlog($message = 'null')
//    {
//        $log = print_r($message, true);
//        \Magento\Framework\App\ObjectManager::getInstance()
//            ->get('Psr\Log\LoggerInterface')
//            ->debug($log);
//    }
}