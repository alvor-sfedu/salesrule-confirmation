<?php
namespace Alvor\SalesRuleConfirmation\ViewModel\Email;

use Magento\SalesRule\Model\Rule;
use Alvor\SalesRuleConfirmation\Model\DataExtractorPool;
use Magento\Backend\Helper\Data as BackendHelper;

class Confirmation
{
    private $extractorsPool;
    private $backendHelper;

    private $rule;

    public function __construct(
        DataExtractorPool $dataExtractorPool,
        BackendHelper $backendHelper
    )
    {
        $this->extractorsPool = $dataExtractorPool;
        $this->backendHelper = $backendHelper;
    }

    public function init(Rule $rule)
    {
        $this->rule = $rule;
    }

    public function getMainData(): array
    {
        return $this->getExtractedDataByAlias('main');
    }


    private function getExtractedDataByAlias(string $alias): array
    {
        $result = [];
        /** @var \Alvor\SalesRuleConfirmation\Model\DataExtractor\ExtractorInterface $extractor */
        foreach ($this->extractorsPool->getExtractorsByGroupType($alias) as $extractor) {
            $result = array_merge($result, $extractor->extractFromRule($this->rule));
        }
        return $result;
    }

    public function getAdminUrl(): string
    {
        return $this->backendHelper->getHomePageUrl();
    }
}
