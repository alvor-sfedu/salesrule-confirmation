<?php
namespace Alvor\SalesRuleConfirmation\Model\DataExtractor;


use Magento\Framework\Phrase;
use Magento\SalesRule\Model\Rule as SalesRule;
use Magento\OfflineShipping\Model\Source\SalesRule\FreeShippingOptions;
use Alvor\SalesRuleConfirmation\Helper\Rule;


class ActionInformation extends AbstractExtractor
{
    private $freeShippingOptions;

    public function __construct(
        Rule $ruleHelper,
        FreeShippingOptions $options
    )
    {
        $this->freeShippingOptions = $options;
        parent::__construct($ruleHelper);
    }

    public function extractFromRule(SalesRule $rule): array
    {
        return [
            $this->format('Apply', $this->ruleHelper->getApplyToDescription($rule->getSimpleAction())),
            $this->format('Discount Amount', $rule->getDiscountAmount()),
            $this->format('Discount Qty', $this->prepareDiscountQty($rule->getDiscountQty()) ?? 'No', true),
            $this->format('Discount Step', (int) $rule->getDiscountStep() > 0 ? $rule->getDiscountStep() :  'No', true),
            $this->format('Apply To Shipping', $rule->getApplyToShipping() ? 'Yes' : 'No', true),
            $this->format('Free Shipping', $this->getFreeShippingTitle($rule->getSimpleFreeShipping())),
            $this->format('Stop Further Rule processing', $rule->getStopRulesProcessing() ? 'Yes' : 'No', true),
        ];
    }

    private function prepareDiscountQty($discountQty)
    {
        if ($discountQty instanceof \Zend_Db_Expr && (string) $discountQty === 'NULL') {
            return null;
        }

        return $discountQty;
    }

    private function getFreeShippingTitle($code): Phrase
    {
        foreach ($this->freeShippingOptions->toOptionArray() as $option) {
            if ($option['value'] == $code) {
                return $option['label'];
            }
        }

        return __('');
    }
}
