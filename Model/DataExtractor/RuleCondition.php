<?php

namespace Alvor\SalesRuleConfirmation\Model\DataExtractor;


use Magento\SalesRule\Model\Rule as SalesRule;

class RuleCondition extends AbstractExtractor
{
    public function extractFromRule(SalesRule $rule): array
    {
        $ruleConditions = $rule->getConditions();
        if ($ruleConditions->getConditions()) {
            return [$this->format('Accept Rule if:', $ruleConditions->asStringRecursive(), false, true)];
        }

        return [$this->format('Accept Rule if:', 'No Conditions set', true)];
    }
}
