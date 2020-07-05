<?php

namespace Alvor\SalesRuleConfirmation\Model\DataExtractor;


use Magento\SalesRule\Model\Rule as SalesRule;

class ActionCondition extends AbstractExtractor
{
    public function extractFromRule(SalesRule $rule): array
    {
        return [$this->format(
            'Apply Discount To:',
            $rule->getActions()->asArray() ? $rule->getActions()->asStringRecursive() : __('Apply to all'),
            false,
            true
        )];
    }
}
