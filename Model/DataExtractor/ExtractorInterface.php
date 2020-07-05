<?php

namespace Alvor\SalesRuleConfirmation\Model\DataExtractor;

use \Magento\SalesRule\Model\Rule as SalesRule;

interface ExtractorInterface
{
    public function extractFromRule(SalesRule $rule): array;
}
