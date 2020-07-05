<?php
namespace Alvor\SalesRuleConfirmation\Plugins\Condition\Product;

use Magento\SalesRule\Model\Rule\Condition\Product\Subselect;

class FixSubselectAsString
{
    /**
     * This plugin should fix problem with missing asString Function at SubSelect class
     *
     * @param Subselect $subselect subject
     * @param \Closure $proceed closure
     * @param string $format format
     * @return \Magento\Framework\Phrase
     */
    public function aroundAsString(Subselect $subselect, \Closure $proceed, string $format = '')
    {
        return __(
            'If %1 %2 %3 for a subselection of items in cart matching %4 of these conditions:',
            $subselect->getAttributeName(),
            $subselect->getOperatorName(),
            $subselect->getValueName(),
            $subselect->getAggregatorName()
        );
    }
}
