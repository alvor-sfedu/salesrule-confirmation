<?php

namespace Alvor\SalesRuleConfirmation\Model\DataExtractor;


use Magento\SalesRule\Model\Rule as SalesRule;
use Alvor\SalesRuleConfirmation\Helper\Rule;
use Alvor\SalesRuleConfirmation\Helper\Data as DataHelper;

class BaseInformation extends AbstractExtractor
{
    const DATE_FORMAT = 'Y-m-d';

    private $helper;

    public function __construct(
        Rule $ruleHelper,
        DataHelper $helper
    )
    {
        $this->helper = $helper;
        parent::__construct($ruleHelper);
    }

    public function extractFromRule(SalesRule $rule): array
    {
        return [
            $this->format('Store', $this->helper->getStoreName()),
            $this->format('Name', $rule->getName()),
            $this->format('Rule Identify', $rule->getRuleId()),
            $this->format('Description', nl2br($rule->getDescription())),
            $this->format(
                'Customer Groups',
                $this->ruleHelper->getCustomerGroupsAsString($rule->getCustomerGroupIds())
            ),
            $this->format('Coupon Type', $this->ruleHelper->getCouponDescription($rule->getCouponType()), true),
            $this->format('Coupon Code', $rule->getCouponType() == Rule::AUTO_GENERATION ? '' : $rule->getCouponCode()),
            $this->format('Uses Per Coupon', (int) $rule->getUsesPerCoupon() > 0 ? $rule->getUsesPerCoupon() : __('Unlimited')),
            $this->format('Uses Per Customer', (int) $rule->getUsesPerCustomer() > 0 ? $rule->getUsesPerCustomer() : __('Unlimited')),
            $this->format('From Date', $this->formatDate($rule->getFromDate())),
            $this->format('To Date', $this->formatDate($rule->getToDate())),
        ];
    }

    private function formatDate(string $rawDate): string
    {
        $date = new \DateTime($rawDate);
        return $date->format(self::DATE_FORMAT);
    }
}
