<?php

/**
 * Magestore.
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

namespace Magestore\Storepickup\Controller\Adminhtml\Report;

use Magento\Framework\Controller\ResultFactory;

/**
 * Index Grid Store Action.
 *
 * @category Magestore
 * @package  Magestore_Storepickup
 * @module   Storepickup
 * @author   Magestore Developer
 */
class Viewreport extends \Magestore\Storepickup\Controller\Adminhtml\Report
{
    /**
     * Index action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $storePickupId = $this->getRequest()->getParam('storepickup_id');
//        \Zend_Debug::dump($storePickupId);die();
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $this->initPage($resultPage);
        $resultPage->getConfig()->getTitle()->prepend(__('Choose Store'));

        return $resultPage;
    }
}
