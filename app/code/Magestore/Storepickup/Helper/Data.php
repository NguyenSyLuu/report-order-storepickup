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
 * @package     Magestore_Storepickup
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

namespace Magestore\Storepickup\Helper;

/**
 * Helper Data.
 * @category Magestore
 * @package  Magestore_Storepickup
 * @module   Storepickup
 * @author   Magestore Developer
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Backend\Block\Widget\Grid\Column\Renderer\Options\Converter
     */
    protected $_converter;

    /**
     * @var \Magestore\Storepickup\Model\Factory
     */
    protected $_factory;

    /**
     * @var \Magestore\Storepickup\Model\StoreFactory
     */
    protected $_storeFactory;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $_backendSession;

    /**
     * @var array
     */
    protected $_sessionData = null;

    /**
     * @var \Magento\Backend\Helper\Js
     */
    protected $_backendHelperJs;

    /**
     * @var \Magestore\Storepickup\Model\ResourceModel\Store\CollectionFactory
     *
     */
    protected $_storeCollectionFactory;

    /**
     * Block constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magestore\Storepickup\Model\Factory $factory,
        \Magento\Backend\Block\Widget\Grid\Column\Renderer\Options\Converter $converter,
        \Magento\Backend\Helper\Js $backendHelperJs,
        \Magento\Backend\Model\Session $backendSession,
        \Magestore\Storepickup\Model\ResourceModel\Store\CollectionFactory $storeCollectionFactory,
        \Magestore\Storepickup\Model\StoreFactory $storeFactory
    ) {
        parent::__construct($context);
        $this->_factory = $factory;
        $this->_converter = $converter;
        $this->_storeFactory = $storeFactory;
        $this->_backendHelperJs = $backendHelperJs;
        $this->_storeCollectionFactory = $storeCollectionFactory;
        $this->_backendSession = $backendSession;
    }

    /**
     * get selected stores in serilaze grid store.
     *
     * @return array
     */
    public function getTreeSelectedStores()
    {
        $sessionData = $this->_getSessionData();

        if ($sessionData) {
            return $this->_converter->toTreeArray(
                $this->_backendHelperJs->decodeGridSerializedInput($sessionData)
            );
        }

        $entityType = $this->_getRequest()->getParam('entity_type');
        $id = $this->_getRequest()->getParam('enitity_id');

        /** @var \Magestore\Storepickup\Model\AbstractModelManageStores $model */
        $model = $this->_factory->create($entityType)->load($id);

        return $model->getId() ? $this->_converter->toTreeArray($model->getStorepickupIds()) : [];
    }

    /**
     * get selected rows in serilaze grid of tag, holiday, specialday.
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getTreeSelectedValues()
    {
        $sessionData = $this->_getSessionData();

        if ($sessionData) {
            return $this->_converter->toTreeArray(
                $this->_backendHelperJs->decodeGridSerializedInput($sessionData)
            );
        }

        $storepickupId = $this->_getRequest()->getParam('storepickup_id');
        $methodGetterId = $this->_getRequest()->getParam('method_getter_id');

        /** @var \Magestore\Storepickup\Model\Store $store */
        $store = $this->_storeFactory->create()->load($storepickupId);
        $ids = $store->runGetterMethod($methodGetterId);

        return $store->getId() ? $this->_converter->toTreeArray($ids) : [];
    }

    /**
     * Get session data.
     *
     * @return array
     */
    protected function _getSessionData()
    {
        $serializedName = $this->_getRequest()->getParam('serialized_name');
        if ($this->_sessionData === null) {
            $this->_sessionData = $this->_backendSession->getData($serializedName, true);
        }

        return $this->_sessionData;
    }
    public function getDefaultStore()
    {
        return $this->scopeConfig->getValue('carriers/storepickup/default_store', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    public function isDisplayPickuptime()
    {
        return $this->scopeConfig->getValue('carriers/storepickup/display_pickuptime', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    public function getListStore()
    {
        /** @var \Magestore\Storepickup\Model\ResourceModel\Store\Collection $collection */
        $collection = $this->_storeCollectionFactory->create();
        $collection->addFieldToSelect(['storepickup_id', 'store_name','address','phone','latitude','longitude']);

        return $collection->getData();
    }
    public function getListStoreJson()
    {
        /** @var \Magestore\Storepickup\Model\ResourceModel\Store\Collection $collection */
        $collection = $this->_storeCollectionFactory->create();
        $collection->addFieldToSelect(['storepickup_id', 'store_name','address','phone','latitude','longitude','city','state','zipcode','country_id','fax']);

        return \Zend_Json::encode($collection->getData());
    }
    public function getChangeTimeURL()
    {
        return $this->getURL('storepickup/checkout/changedate');
    }
    public function generateTimes($mintime, $maxtime, $sys_min_time = '0:0', $interval_time = 30)
    {

        //$sys_min_time = strtotime(date('H:i:s',$sys_min_time));
        $timeHI = explode(':', $mintime);
        $mintime= mktime($timeHI[0], $timeHI[1], 0, '01', '01', '2000');
        $timeHI = explode(':', $maxtime);
        $maxtime= mktime($timeHI[0], $timeHI[1], 0, '01', '01', '2000');
        $timeHI = explode(':', $sys_min_time);
        $sys_min_time= mktime($timeHI[0], $timeHI[1], 0, '01', '01', '2000');
        $listTime = "";

        $i = $mintime;

        while ($i <= $maxtime) {
            if ($i >= $sys_min_time) {
                $time = date('H:i', $i);
                $listTime .= '<option value="' . $time . '">' . $time . '</option>';
                //$listTime[$time] = $time;
            }

            $i += $interval_time*60;
        }

        return $listTime;
    }

}
