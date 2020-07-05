<?php
namespace Alvor\SalesRuleConfirmation\Model\Handlers;
use Magento\SalesRule\Model\Rule as SalesRule;

interface HandlerInterface
{
    const HANDLER_CODE = 'default';

    public function handleRule(SalesRule $rule);
}
