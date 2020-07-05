<?php
namespace Alvor\SalesRuleConfirmation\Model\DataExtractor;

use Magento\SalesRule\Model\Rule as SalesRule;

class ConditionalGifts extends AbstractExtractor
{
    public function extractFromRule(SalesRule $rule): array
    {
        return [
            $this->format('Maximum items with zero price', $rule->getTotalQtyLimit()),
            $this->format('Sort By (attribute code)', $rule->getSortBy()),
            $this->format('Sort Direction', $rule->getSortDir())
        ];
    }

    private function prepareDirection(string $rawDirectionCode)
    {
        if ($rawDirectionCode === \Zend_Db_Select::SQL_ASC) {
            return 'Ascending';
        }

        return 'Descending';
    }
}
