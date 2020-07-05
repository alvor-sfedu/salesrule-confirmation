<?php

namespace Alvor\SalesRuleConfirmation\Model\DataExtractor;
use Alvor\SalesRuleConfirmation\Helper\Rule;

abstract class AbstractExtractor implements ExtractorInterface
{
    protected $ruleHelper;

    public function __construct(
        Rule $ruleHelper
    )
    {
        $this->ruleHelper = $ruleHelper;
    }

    /**
     * Format Data
     *
     * @param string $name
     * @param mixed $value
     * @param bool $mustTranslateValue
     * @return array
     */
    protected function format(string $name, $value, bool $mustTranslateValue = false, bool $preWrap = false): array
    {
        return [
            'name'  => $name,
            'value' => $mustTranslateValue ? __($value) : $value,
            'pre_wrap' => $preWrap
        ];
    }

}
