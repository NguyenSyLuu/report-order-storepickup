<?php

namespace Magestore\Storepickup\Model\Quote\Address\Total;

class Storepickup extends \Magento\Quote\Model\Quote\Address\Total\Shipping
{
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {	
		if ($total->getShippingMethod()=="storepickup_storepickup")
		{
        $amount = $total->getShippingAmount();
        $shippingDescription = $total->getShippingDescription().'nhaggdecription';
        $title = 'Pikcup date:';

        return [
            'code' => $this->getCode(),
            'title' => $title,
            'value' => '0'
        ];
		}
    }


}