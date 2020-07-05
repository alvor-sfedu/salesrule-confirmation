<?php
namespace Alvor\SalesRuleConfirmation\Model\ResourceModel\Confirmation;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

use Alvor\SalesRuleConfirmation\Model\Confirmation;
use Alvor\SalesRuleConfirmation\Model\ResourceModel\Confirmation as ConfirmationResource;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(Confirmation::class, ConfirmationResource::class);
    }

    /**
     * Add rule id filter
     *
     * @param string $ruleId
     * @return Collection
     */
    public function addRuleIdFilter(string $ruleId)
    {
        return $this->addFieldToFilter('rule_id', ['eq' => $ruleId]);
    }

    /**
     * Add confirmation type filter
     *
     * @param string|array $confirmationType Confirmation type
     *
     * @return void
     */
    public function addConfirmationTypeFilter($confirmationType)
    {
        if (is_array($confirmationType)) {
            $this->addFieldToFilter('confirmation_type', ['in' => $confirmationType]);
        } else {
            $this->addFieldToFilter('confirmation_type', ['eq' => (string)$confirmationType]);
        }
    }
}
