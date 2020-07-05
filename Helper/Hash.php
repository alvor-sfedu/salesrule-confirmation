<?php
namespace Alvor\SalesRuleConfirmation\Helper;

use Magento\SalesRule\Model\Rule as SalesRule;

class Hash
{
    const DEFAULT_HASH_ALGORITHM = 'sha256';

    public function generateConfirmationHash(SalesRule $rule, string $confirmationPersonCode): string
    {
        $stringToHash = $rule->getName() . (string)$rule->getId() . $confirmationPersonCode;
        return hash(self::DEFAULT_HASH_ALGORITHM, $stringToHash);
    }
}
