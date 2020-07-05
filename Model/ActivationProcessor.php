<?php
namespace Alvor\SalesRuleConfirmation\Model;

use Magento\SalesRule\Model\Rule as SalesRule;
use Alvor\SalesRuleConfirmation\Model\Confirmation;
use Alvor\SalesRuleConfirmation\Model\ResourceModel\Confirmation\CollectionFactory;
use Alvor\SalesRuleConfirmation\Model\Handlers\EcommerceManagerHandler;
use Alvor\SalesRuleConfirmation\Model\Handlers\FinancialManagerHandler;

class ActivationProcessor
{
    const BOTH_ACTIVATION_EXIST = 2;

    private $collectionFactory;

    public function __construct(
        CollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function canActivateRule($ruleId): bool
    {
        /** @var \Alvor\SalesRuleConfirmation\Model\ResourceModel\Confirmation\Collection $confirmationCollection */
        $confirmationCollection = $this->collectionFactory->create();
        $confirmationCollection->addFieldToSelect('confirmation_type');
        $confirmationCollection->distinct(true);
        $confirmationCollection->addFieldToFilter('rule_id', $ruleId);
        $confirmationCollection->addFieldToFilter('is_confirmed', Confirmation::CONFIRMED);
        $confirmationCollection->addFieldToFilter('confirmation_type',
            [
                ['eq' => EcommerceManagerHandler::HANDLER_CODE],
                ['eq' => FinancialManagerHandler::HANDLER_CODE]
            ]
        );

        return $confirmationCollection->getSize() == self::BOTH_ACTIVATION_EXIST;
    }
}
