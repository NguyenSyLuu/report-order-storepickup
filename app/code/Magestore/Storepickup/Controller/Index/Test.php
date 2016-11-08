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

namespace Magestore\Storepickup\Controller\Index;

/**
 * @category Magestore
 * @package  Magestore_Storepickup
 * @module   Storepickup
 * @author   Magestore Developer
 */
class Test extends \Magestore\Storepickup\Controller\Index
{
    /**
     * Execute action.
     */
    public function execute()
    {
        $t = time();
        echo($t . "<br>");
        echo(date("Y-m-d h:m:s", $t));
//        $model = $this->_objectManager->create('\Magestore\Storepickup\Model\ResourceModel\Store\Grid\Collection');
//        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//        $productCollection = $objectManager->create('\Magestore\Storepickup\Model\Report');
//        $this->_logger->info($order_id);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//        $productCollection = $objectManager->create('Magento\Catalog\Model\Product');
        $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
//        $result = $productCollection->load(1);
//        \Zend_Debug::dump($result->load(1)->getData());
//        $result = $productCollection->addFieldToFilter('entity_id', 2);
        $result = $productCollection->load(2);
        \Zend_Debug::dump($result->getData());

        die();
    }
        public function xlog($message = 'null')
    {
        $log = print_r($message, true);
        \Magento\Framework\App\ObjectManager::getInstance()
            ->get('Psr\Log\LoggerInterface')
            ->debug($log);
}
}
