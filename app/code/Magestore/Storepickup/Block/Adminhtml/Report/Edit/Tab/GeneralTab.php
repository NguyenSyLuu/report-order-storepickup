<?php 

namespace Magestore\Storepickup\Block\Adminhtml\Report\Edit\Tab;

use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Class Tab GeneralTab
 */
class GeneralTab extends \Magento\Backend\Block\Widget\Form\Generic implements TabInterface
{
    /**
     * Tab constructor
     *
     * @param \Magento\Backend\Block\Template\Context   $context
     * @param \Magento\Framework\Registry               $registry
     * @param \Magento\Framework\Data\FormFactory       $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = array()
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * get registry model.
     *
     * @return \Magento\Framework\Model\AbstractModel|null
     */
    public function getRegistryModel()
    {
        return $this->_coreRegistry->registry('registry_model');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('General information');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('General information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareForm()
    {
        $model = $this->getRegistryModel();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('helloword_');

        $fieldset = $form->addFieldset('general_fieldset', ['legend' => __('General Information')]);

        if ($model->getId()) {
            $fieldset->addField('report_id', 'hidden', ['name' => 'report_id']);
        }

        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'status',
            'select',
            [
                'name' => 'status',
                'label' => __('Status'),
                'title' => __('Status'),
                'options' => \Magestore\Storepickup\Model\Status::getAvailableStatuses(),
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
