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
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_localeDate;

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
    protected $_getHtml;
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
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        Magento\Framework\Data\Form\Element\Date $Html,
        \Magestore\Storepickup\Model\StoreFactory $storeFactory
    ) {
        parent::__construct($context);
        $this->_factory = $factory;
        $this->_converter = $converter;
        $this->_storeFactory = $storeFactory;
        $this->_backendHelperJs = $backendHelperJs;
        $this->_backendSession = $backendSession;
        $this->_getHtml= $Html;
        $this->_localeDate = $localeDate;
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
    public function getDateFormat()
    {
        $date_format = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $date_format=str_replace('/','-',$$date_format);
        // return $date_format;
        //Edit by Tien
        // Fix for most languages
        switch ($date_format) {
            case 'd-M-yyyy':
            case 'dd-MM-yy':
            case 'd.M.yyyy':
            case 'dd.MM.yyyy':
            case 'dd-MM-yyyy':
            case 'dd.M.yyyy':
            case 'd.M.yyyy.':
            case 'd. MM. yyyy':
                return '%d-%m-%Y';
                break;
            case 'yyyy-MM-dd':
            case 'yyyy.MM.dd.':
            case 'yyyy. M. d.':
            case 'yyyy-M-d':
                return '%Y-%m-%d';
                break;
            case 'M-d-yy':
            case 'M.d.yy':
            case 'M-dd-yyyy':
            case 'M.dd.yyyy':
            case 'MM-dd-yyyy':
            case 'MM.dd.yyyy':
                return '%m-%d-%Y';
                break;

        }
    }
    public function getTimeFormat()
    {
        return $this->_localeDate->getTimeFormat(\IntlDateFormatter::SHORT);
    }

    public function getElementHtml23()
    {
        return $this->_getHtml->getElementHtml();
    }

}
