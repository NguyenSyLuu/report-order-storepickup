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

use Magento\Framework\Event\ObserverInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class GiftMessageConfigObserver
 *
 * @category Magestore
 * @package  Magestore_StorePickup
 * @module   StorePickup
 * @author   Magestore Developer
 */
class adminhtmlSaveStorepickupDecription implements ObserverInterface
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
    /**
     * @var \Magento\Sales\Api\Data\OrderAddressInterface
     */
    protected $_orderAddressInterface;

    public function __construct(
        \Magento\Backend\Model\Session $backendSession,
        \Magestore\Storepickup\Model\StoreFactory $storeCollection,
        \Magento\Sales\Api\Data\OrderAddressInterface $orderAddressInterface
    ){
        $this->_backendSession = $backendSession;
        $this->_storeCollection = $storeCollection;
        $this->_orderAddressInterface = $orderAddressInterface;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //die('asdasd');
        try {
            /** @var \Magento\Sales\Model\Order $order */
            $order = $observer->getEvent()->getOrder();
            if($order->getShippingMethod(true)->getCarrierCode()=="storepickup") {
                $new = $order->getShippingDescription();
                if ($this->_backendSession->getData('storepickup')) {
                    $storepickup_session = $this->_backendSession->getData('storepickup',true);
                    if(isset($storepickup_session['shipping_date']) &&isset($storepickup_session['shipping_time'])) $new .= "<p>".__('Pickup date').' : ' . $storepickup_session['shipping_date'].'</p><p>' .__('Pickup time'). ' : ' . $storepickup_session['shipping_time'].'</p><img src="http://maps.google.com/maps/api/staticmap?center='.$storepickup_session['latitude']. ',' . $storepickup_session['longitude'] . '&zoom=15&size=200x200&markers=color:red|label:S|' . $storepickup_session['latitude'] . ',' . $storepickup_session['longitude'] . '&sensor=false" /><p></p>';
                }

                $order->setShippingDescription($new);
                $datashipping = array();
                $storeId = $storepickup_session['store_id'];
                $collectionstore = $this->_storeCollection->create();
                $store = $collectionstore->load($storeId, 'storepickup_id');
                $datashipping['firstname'] = __('Store');
                $datashipping['lastname'] = $store->getData('store_name');
                $datashipping['street'][0] = $store->getData('address');
                $datashipping['city'] = $store->getCity();
                $datashipping['region'] = $store->getState();
                $datashipping['postcode'] = $store->getData('zipcode');
                $datashipping['country_id'] = $store->getData('country_id');
                $datashipping['company'] = '';
                if ($store->getFax()) {
                    $datashipping['fax'] = $store->getFax();
                } else {
                    unset($datashipping['fax']);
                }

                if ($store->getPhone()) {
                    $datashipping['telephone'] = $store->getPhone();
                } else {
                    unset($datashipping['telephone']);
                }

                $datashipping['save_in_address_book'] = 1;

                $order->getShippingAddress()->addData($datashipping);
                $order->getShippingAddress()->save();
            }

        } catch (Exception $e) {

        }
    }
}