<?php
namespace Alvor\SalesRuleConfirmation\Model;


use Alvor\SalesRuleConfirmation\Model\DataExtractor\ExtractorInterface;

class DataExtractorPool
{
    private $extractors;

    public function __construct(
        array $extractors
    )
    {
        $this->extractors = $extractors;
    }

    public function getExtractorsByGroupType($groupType): array
    {
        return $this->extractors[$groupType] ?? [];
    }
}
