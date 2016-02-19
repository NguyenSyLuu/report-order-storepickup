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
 * @package     Magestore_OneStepCheckout
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
 * @package  Magestore_OneStepCheckout
 * @module   OneStepCheckout
 * @author   Magestore Developer
 */
class saveStorepickupDecription implements ObserverInterface
{
    protected $_checkoutSession;

    /**
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @codeCoverageIgnore
     */

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession
    ){
        $this->_checkoutSession = $checkoutSession;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $order = $observer->getEvent()->getOrder();
            $new = $order->getShippingDescription() . '<br/> nhaggShippingdecription__  ';
            if ($this->_checkoutSession->getData('storepickup_session')) {
                $storepickup_session = $this->_checkoutSession->getData('storepickup_session');
                $new.= $storepickup_session['store_id'].$storepickup_session['store_name'].$storepickup_session['store_address'];
            }

            $order->setShippingDescription($new);

        } catch (Exception $e) {

        }
    }
}