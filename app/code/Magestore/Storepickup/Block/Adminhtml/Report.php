<?php 

namespace Magestore\Storepickup\Block\Adminhtml;

/**
 * Grid Container Report
 */
class Report extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_report';
        $this->_blockGroup = 'Magestore_Storepickup';
        $this->_headerText = __('Report grid');
        $this->_addButtonLabel = __('Add New Report');

        parent::_construct();
    }
}
