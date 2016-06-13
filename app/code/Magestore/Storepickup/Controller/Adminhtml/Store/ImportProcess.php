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

namespace Magestore\Storepickup\Controller\Adminhtml\Store;

use Magestore\Storepickup\Controller\Adminhtml\Store;

/**
 * Adminhtml Storepickup ProcessImport Action
 *
 * @category Magestore
 * @package  Magestore_Storepickup
 * @module   Storepickup
 * @author   Magestore Developer
 */
class ImportProcess extends Store
{
    
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if (isset($_FILES['filecsv'])) {
            if (substr($_FILES['filecsv']["name"], -4)!='.csv') {
                $this->messageManager->addError(__('Please choose a CSV file'));
                return $resultRedirect->setPath('*/*/importstore');
            }
			
			$fileName = $_FILES['filecsv']['tmp_name'];
			$csvObject = $this->_objectManager->create('Magento\Framework\File\Csv');
			$helperRegion = $this->_objectManager->create('Magestore\Storepickup\Helper\Region');
			$data = $csvObject->getData($fileName);

			$store = $this->_createMainModel();

			$storeData = array();

			try {
                $total = 0;
				$error_message = '';
				$flag = 1;
                foreach ($data as $col => $row) {
					if ($col == 0) {
						$index_row = $row;
					} else {

						for ($i = 0; $i < count($row); $i++) {
							$storeData[$index_row[$i]] = $row[$i];
						}

						if(!isset($storeData['monday_status'])){
							$this->messageManager->addError(__('Please follow the sample file\'s format to import stores properly.'));
							return $resultRedirect->setPath('*/*/importstore');
						}

						if ($storeData['monday_status'] == 0 || !$storeData['monday_status']) {
							$storeData['monday_status'] = 1;
						}

						if ($storeData['tuesday_status'] == 0 || !$storeData['tuesday_status']) {
							$storeData['tuesday_status'] = 1;
						}

						if ($storeData['wednesday_status'] == 0 || !$storeData['wednesday_status']) {
							$storeData['wednesday_status'] = 1;
						}

						if ($storeData['thursday_status'] == 0 || !$storeData['thursday_status']) {
							$storeData['thursday_status'] = 1;
						}

						if ($storeData['friday_status'] == 0 || !$storeData['friday_status']) {
							$storeData['friday_status'] = 1;
						}

						if ($storeData['saturday_status'] == 0 || !$storeData['saturday_status']) {
							$storeData['saturday_status'] = 1;
						}

						if ($storeData['sunday_status'] == 0 || !$storeData['sunday_status']) {
							$storeData['sunday_status'] = 1;
						}

						if ($storeData['monday_time_interval'] == 0 || !$storeData['monday_time_interval']) {
							$storeData['monday_time_interval'] = 15;
						}

						if ($storeData['tuesday_time_interval'] == 0 || !$storeData['tuesday_time_interval']) {
							$storeData['tuesday_time_interval'] = 15;
						}

						if ($storeData['wednesday_time_interval'] == 0 || !$storeData['wednesday_time_interval']) {
							$storeData['wednesday_time_interval'] = 15;
						}

						if ($storeData['thursday_time_interval'] == 0 || !$storeData['thursday_time_interval']) {
							$storeData['thursday_time_interval'] = 15;
						}

						if ($storeData['friday_time_interval'] == 0 || !$storeData['friday_time_interval']) {
							$storeData['friday_time_interval'] = 15;
						}

						if ($storeData['saturday_time_interval'] == 0 || !$storeData['saturday_time_interval']) {
							$storeData['saturday_time_interval'] = 15;
						}

						if ($storeData['sunday_time_interval'] == 0 || !$storeData['sunday_time_interval']) {
							$storeData['sunday_time_interval'] = 15;
						}

						$storeData['state_id'] = $helperRegion->validateState($storeData['country'],$storeData['state']);

						if($storeData['state_id'] == \Magestore\Storepickup\Helper\Region::STATE_ERROR){
							$_state = $storeData['state'] == '' ? 'null' : $storeData['state'];
							if($flag == 1)
								$error_message .= ' <br />'.$flag.': '.$_state.' of <strong>'.$storeData['store_name'].'</strong><br />';
							else
								$error_message .= $flag.': '.$_state.' of <strong>'.$storeData['store_name'].'</strong><br />';
						}

						$_state_id = $storeData['state_id'] > \Magestore\Storepickup\Helper\Region::STATE_ERROR;

						if ($storeData['store_name'] && $storeData['address'] && $storeData['country'] && $_state_id) {
							$store->setData($storeData);
							$store->setId(null);

							if ($store->import()) {
								$total++;
							}

						}
					}
				}

				if($error_message != ''){
					$error_msg = 'The States that don\'t match any State: '.$error_message;
					$this->messageManager->addNotice($error_msg);
				}

				if ($total != 0) {
					$this->messageManager->addSuccess('Imported successful total ' . $total . ' stores');
				} else {
					$this->messageManager->addSuccess('No store imported');
				}
				
				return $resultRedirect->setPath('*/*/index');							
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/importstore');
            }
        }
    }
    
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magestore_Storepickup::storepickup');
    }
}
