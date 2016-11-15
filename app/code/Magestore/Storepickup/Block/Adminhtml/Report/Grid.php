<?php 

namespace Magestore\Storepickup\Block\Adminhtml\Report;

/**
 * Grid Grid
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;
    protected $request;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\ObjectManagerInterface $objectManager, array $data = array()

    )
    {
        parent::__construct($context, $backendHelper, $data);
        $this->_objectManager = $objectManager;
        $this->request = $request;

    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('Grid');
        $this->setDefaultSort('report_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareCollection()
    {
        $storePickupId = $this->request->getParam('storepickup_id');
//        \Zend_Debug::dump($storePickupId);
        $collection = $this->_objectManager->create('Magestore\Storepickup\Model\ResourceModel\Report\Collection');
        $this->setCollection($collection->addFieldToFilter('storepickup_id', $storePickupId));

        return parent::_prepareCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'report_id',
            [
                'header'           => __('ID'),
                'index'            => 'report_id',
                'type'             => 'number',
                'align'            => 'center',
                'width' => '10px',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );

        $this->addColumn(
            'order_id',
            [
                'header'           => __('Order Id'),
                'index'            => 'order_id',
                'type'             => 'number',
                'align'            => 'center',
                'width' => '10px',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );

        $this->addColumn(
            'storepickup_id',
            [
                'header'           => __('Storepickup Id'),
                'index'            => 'storepickup_id',
                'type'             => 'number',
                'header_css_class' => 'col-id',
                'align'            => 'center',
                'column_css_class' => 'col-id',
            ]
        );

        $this->addColumn(
            'product_id',
            [
                'header'           => __('ID'),
                'index'            => 'product_id',
                'type'             => 'number',
                'header_css_class' => 'col-id',
                'align'            => 'center',
                'column_css_class' => 'col-id',
            ]
        );

        $this->addColumn(
            'product_name',
            [
                'header'           => __('Name'),
                'index'            => 'product_name',
                'header_css_class' => 'col-name',
                'align'            => 'center',
                'column_css_class' => 'col-name',
            ]
        );

        $this->addColumn(
            'qty',
            [
                'header'           => __('Qty'),
                'index'            => 'qty',
                'type'             => 'number',
                'align'            => 'center',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name',
            ]
        );

        $this->addColumn(
            'date_report',
            [
                'header'           => __('Date'),
                'index'            => 'date_report',
                'type'             => 'date',
                'align'            => 'center',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name',
            ]
        );

//        $this->addColumn(
//            'status',
//            [
//                'header'  => __('Status'),
//                'index'   => 'status',
//                'type'    => 'options',
//                'options' => \Magestore\Storepickup\Model\Status::getAvailableStatuses(),
//            ]
//        );

//        $this->addColumn(
//            'edit',
//            [
//                'header'           => __('Action'),
//                'type'             => 'action',
//                'getter'           => 'getId',
//                'actions'          => [
//                    [
//                    'caption' => __('Edit'),
//                    'url'     => ['base' => '*/*/edit'],
//                    'field'   => 'entity_id',
//                    ],
//                ],
//                'filter'           => FALSE,
//                'sortable'         => FALSE,
//                'index'            => 'report_id',
//                'header_css_class' => 'col-action',
//                'column_css_class' => 'col-action',
//            ]
//        );

        $this->addExportType('*/*/exportCsv', __('CSV'));
        $this->addExportType('*/*/exportXml', __('XML'));
        $this->addExportType('*/*/exportExcel', __('Excel'));

        return parent::_prepareColumns();
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('report_id');
        $this->getMassactionBlock()->setFormFieldName('reports');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label'   => __('Delete'),
                'url'     => $this->getUrl('magestorereportadmin/*/massDelete'),
                'confirm' => __('Are you sure?'),
            ]
        );

        $statuses = \Magestore\Storepickup\Model\Status::getAvailableStatuses();

        array_unshift($statuses, ['label' => '', 'value' => '']);
        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label'      => __('Change status'),
                'url'        => $this->getUrl('magestorereportadmin/*/massStatus', ['_current' => TRUE]),
                'additional' => [
                    'visibility' => [
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => __('Status'),
                        'values' => $statuses,
                    ],
                ],
            ]
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => TRUE]);
    }

    /**
     * {@inheritdoc}
     */
//    public function getRowUrl($row)
//    {
//        return $this->getUrl('*/*/edit', ['report_id' => $row->getId()]);
//    }
}
