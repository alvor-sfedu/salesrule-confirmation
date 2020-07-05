<?php
namespace Alvor\SalesRuleConfirmation\Model\ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Confirmation extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('alvor_salesrule_confirmation', 'entity_id');
    }

    /**
     * Delete all confirmations for rule by rule id
     *
     * @param int|string $ruleId Rule ID
     *
     * @return void
     */
    public function deleteAllConfirmationByRuleId($ruleId)
    {
        $this->getConnection()->delete($this->getMainTable(), "rule_id = {$ruleId}");
    }
}
